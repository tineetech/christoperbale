<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\PenjualanAddress;
use App\Models\PenjualanDetail;
use App\Models\PenjualanDraft;
use App\Models\PenjualanDraftItem;
use App\Models\PenjualanShipment;
use App\Models\PengaturanWeb;
use App\Models\StokBarang;
use App\Models\StokMovement;
use App\Models\UserVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $serverKey = PengaturanWeb::where('key', 'Midtrans Server Key')->value('value') ?? '';

        if ($serverKey && $orderId && $statusCode && $grossAmount) {
            $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            $received = $payload['signature_key'] ?? '';
            if (!hash_equals($expected, $received)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }
        }

        $pembayaran = Pembayaran::where('order_id_midtrans', $orderId)->first();
        if (!$pembayaran) {
            return response()->json(['status' => 'ok']);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';
        $paymentType = $payload['payment_type'] ?? '';
        $transactionId = $payload['transaction_id'] ?? null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->finalize($pembayaran, $paymentType, $transactionId);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->finalize($pembayaran, $paymentType, $transactionId);
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
            $pembayaran->update(['status' => $transactionStatus]);
            $this->notifyPaymentFailed($pembayaran, $transactionStatus);
        } elseif ($transactionStatus === 'pending') {
            $pembayaran->update(['status' => 'pending']);
        }

        return response()->json(['status' => 'ok']);
    }

    private function notifyPaymentFailed(Pembayaran $pembayaran, string $status)
    {
        $draft = PenjualanDraft::with('creator')
            ->where('id', $pembayaran->penjualan_draft_id)
            ->first();

        if (!$draft) {
            return;
        }

        $exists = Notifikasi::where('tipe', 'pembayaran')
            ->where('payload->pembayaran_id', $pembayaran->id)
            ->where('payload->status', $status)
            ->exists();

        if ($exists) {
            return;
        }

        $buyer = $draft->creator;
        $buyerName = $buyer->full_name ?: $buyer->nama ?? 'Customer';
        $buyerPhone = $buyer->phone ?? '-';
        $buyerEmail = $buyer->email ?? '-';

        $statusLabel = [
            'deny'    => 'ditolak',
            'cancel'  => 'dibatalkan',
            'expire'  => 'kadaluarsa',
            'failure' => 'gagal',
        ][$status] ?? 'gagal';

        Notifikasi::create([
            'judul' => 'Pembayaran Gagal / ' . ucfirst($statusLabel),
            'isi' => 'Pembayaran untuk pesanan ' . $draft->kode_penjualan . ' berstatus ' . $statusLabel . ' pada ' . now()->translatedFormat('l, d F Y H:i:s') . '. Pembeli: ' . $buyerName . ' (' . $buyerPhone . ') - ' . $buyerEmail . '. Total: Rp' . number_format((float) $pembayaran->amount, 0, ',', '.'),
            'tipe' => 'pembayaran',
            'link' => route('dashboard.pembayaran'),
            'payload' => [
                'kode_penjualan' => $draft->kode_penjualan,
                'pembayaran_id' => $pembayaran->id,
                'penjualan_draft_id' => $draft->id,
                'status' => $status,
                'pembeli' => [
                    'nama' => $buyerName,
                    'no_hp' => $buyerPhone,
                    'email' => $buyerEmail,
                ],
            ],
            'created_by' => $draft->created_by,
        ]);
    }

    public function syncStatus(Pembayaran $pembayaran)
    {
        $serverKey = PengaturanWeb::where('key', 'Midtrans Server Key')->value('value') ?? '';
        $orderId = $pembayaran->order_id_midtrans;

        if (!$serverKey || !$orderId) {
            return false;
        }

        $isProduction = strtolower(PengaturanWeb::where('key', 'Midtrans Environment')->value('value') ?? 'sandbox') === 'production';
        $base = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        $ch = curl_init($base . '/v2/' . urlencode($orderId) . '/status');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':'),
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return false;
        }

        $data = json_decode($response, true);
        $transactionStatus = $data['transaction_status'] ?? '';
        $fraudStatus = $data['fraud_status'] ?? '';
        $paymentType = $data['payment_type'] ?? '';
        $transactionId = $data['transaction_id'] ?? null;

        $success = ($transactionStatus === 'settlement')
            || ($transactionStatus === 'capture' && $fraudStatus === 'accept');

        if ($success) {
            $this->finalize($pembayaran, $paymentType, $transactionId);
            return true;
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
            $pembayaran->update(['status' => $transactionStatus]);
            $this->notifyPaymentFailed($pembayaran, $transactionStatus);
        }

        return false;
    }

    public function finalize($pembayaran, $paymentType, $transactionId)
    {
        DB::transaction(function () use ($pembayaran, $paymentType, $transactionId) {
            $locked = Pembayaran::where('id', $pembayaran->id)->lockForUpdate()->first();

            if (!$locked || $locked->status === 'paid') {
                return;
            }

            $draft = PenjualanDraft::with('items')
                ->where('id', $locked->penjualan_draft_id)
                ->first();

            if (!$draft) {
                $locked->update(['status' => 'paid', 'paid_at' => now()]);
                return;
            }

            $voucherId = null;
            $keterangan = trim(preg_replace('/\[Voucher:\d+\]|\[Cart:[\d,]+\]/', '', (string) $draft->keterangan));
            if (preg_match('/\[Voucher:(\d+)\]/', (string) $draft->keterangan, $m)) {
                $voucherId = (int) $m[1];
            }

            $penjualan = Penjualan::create([
                'kode_penjualan' => $draft->kode_penjualan,
                'nomor_pesanan' => $draft->kode_penjualan,
                'nomor_transaksi' => $locked->order_id_midtrans,
                'tanggal' => now(),
                'total_harga' => $draft->total_harga,
                'harga_discount' => $draft->harga_discount,
                'shipping_cost' => $draft->shipping_cost,
                'service_fee' => 0,
                'subtotal_harga' => $draft->subtotal_harga,
                'keterangan' => $keterangan,
                'status' => 'proses',
                'order_web' => true,
                'created_by' => $draft->created_by,
            ]);

            foreach ($draft->items as $draftItem) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $draftItem->barang_id,
                    'qty' => $draftItem->qty,
                    'harga' => $draftItem->harga,
                    'subtotal' => $draftItem->subtotal,
                ]);

                $stok = StokBarang::where('barang_id', $draftItem->barang_id)->lockForUpdate()->first();
                $stokSebelum = $stok->jumlah_stok ?? 0;

                if ($stokSebelum < $draftItem->qty) {
                    throw new \Exception("Stok tidak cukup untuk barang ID " . $draftItem->barang_id);
                }

                $stokSesudah = $stokSebelum - $draftItem->qty;

                StokBarang::updateOrCreate(
                    ['barang_id' => $draftItem->barang_id],
                    ['jumlah_stok' => $stokSesudah]
                );

                StokMovement::create([
                    'barang_id'      => $draftItem->barang_id,
                    'jenis'          => 'keluar',
                    'qty'            => $draftItem->qty,
                    'stok_sebelum'   => $stokSebelum,
                    'stok_sesudah'   => $stokSesudah,
                    'referensi_tipe' => 'penjualan',
                    'referensi_id'   => $penjualan->id,
                    'keterangan'     => 'Penjualan ' . $penjualan->kode_penjualan,
                    'created_by'     => $draft->created_by,
                ]);
            }

            PenjualanAddress::where('penjualan_draft_id', $draft->id)
                ->update(['penjualan_id' => $penjualan->id]);

            PenjualanShipment::where('penjualan_draft_id', $draft->id)
                ->update(['penjualan_id' => $penjualan->id]);

            $locked->update([
                'penjualan_id' => $penjualan->id,
                'status' => 'paid',
                'payment_type' => $paymentType,
                'transaction_id' => $transactionId,
                'paid_at' => now(),
            ]);

            if ($voucherId) {
                UserVoucher::where('id', $voucherId)
                    ->where('user_id', $draft->created_by)
                    ->update([
                        'status' => 'used',
                        'used_at' => now(),
                        'order_id' => $penjualan->id,
                    ]);
            }

            if (preg_match('/\[Cart:([\d,]+)\]/', (string) $draft->keterangan, $cm)) {
                $cartIds = array_map('intval', explode(',', $cm[1]));
                Cart::where('user_id', $draft->created_by)
                    ->whereIn('id', $cartIds)
                    ->delete();
            }

            Notifikasi::create([
                'judul' => 'Pembayaran Diterima',
                'isi' => 'Pembayaran untuk nomor pesanan ' . $penjualan->kode_penjualan . ' dari website telah dibayar pada ' . now()->translatedFormat('l, d F Y H:i:s') . '.',
                'tipe' => 'pembayaran',
                'link' => route('checkout.success', $penjualan->id),
                'payload' => ['kode_penjualan' => $penjualan->kode_penjualan, 'penjualan_id' => $penjualan->id, 'pembayaran_id' => $locked->id],
                'created_by' => $draft->created_by,
            ]);

            $draft->delete();
            PenjualanDraftItem::where('penjualan_draft_id', $draft->id)->delete();
        });
    }
}
