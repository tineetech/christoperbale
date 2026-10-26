<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\PenjualanAddress;
use App\Models\PenjualanDraft;
use App\Models\PenjualanDraftItem;
use App\Models\PenjualanShipment;
use App\Models\StokBarang;
use App\Models\StokMovement;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function deductStockForDraft(PenjualanDraft $draft, ?int $userId = null): void
    {
        $items = PenjualanDraftItem::where('penjualan_draft_id', $draft->id)->get();

        foreach ($items as $item) {
            $stok = StokBarang::where('barang_id', $item->barang_id)->lockForUpdate()->first();

            if (!$stok) {
                $stok = StokBarang::create([
                    'barang_id' => $item->barang_id,
                    'jumlah_stok' => 0,
                ]);
            }

            $stokSebelum = (int) $stok->jumlah_stok;

            if ($stokSebelum < (int) $item->qty) {
                throw new \RuntimeException('Stok tidak cukup untuk barang ID ' . $item->barang_id);
            }

            $stokSesudah = $stokSebelum - (int) $item->qty;

            $stok->update(['jumlah_stok' => $stokSesudah]);

            StokMovement::create([
                'barang_id'      => $item->barang_id,
                'jenis'          => 'keluar',
                'qty'            => $item->qty,
                'stok_sebelum'   => $stokSebelum,
                'stok_sesudah'   => $stokSesudah,
                'referensi_tipe' => 'penjualan_draft',
                'referensi_id'   => $draft->id,
                'keterangan'     => 'Reservasi stok pesanan ' . $draft->kode_penjualan,
                'created_by'     => $userId ?? $draft->created_by,
            ]);
        }
    }

    public function hasReservedStock(PenjualanDraft $draft): bool
    {
        return StokMovement::where('referensi_tipe', 'penjualan_draft')
            ->where('referensi_id', $draft->id)
            ->where('jenis', 'keluar')
            ->exists();
    }

    public function cancel(Pembayaran $pembayaran, string $status = 'canceled'): bool
    {
        return DB::transaction(function () use ($pembayaran, $status) {
            $locked = Pembayaran::where('id', $pembayaran->id)->lockForUpdate()->first();

            if (!$locked || in_array($locked->status, ['paid', 'canceled'], true)) {
                return false;
            }

            $draft = $locked->penjualan_draft_id
                ? PenjualanDraft::with('items')->where('id', $locked->penjualan_draft_id)->first()
                : null;

            if ($draft) {
                $alreadyRestored = StokMovement::where('referensi_tipe', 'penjualan_draft')
                    ->where('referensi_id', $draft->id)
                    ->where('jenis', 'masuk')
                    ->exists();

                if (!$alreadyRestored && $this->hasReservedStock($draft)) {
                    foreach ($draft->items as $item) {
                        $stok = StokBarang::where('barang_id', $item->barang_id)->lockForUpdate()->first();

                        if (!$stok) {
                            $stok = StokBarang::create([
                                'barang_id' => $item->barang_id,
                                'jumlah_stok' => 0,
                            ]);
                        }

                        $stokSebelum = (int) $stok->jumlah_stok;
                        $stokSesudah = $stokSebelum + (int) $item->qty;

                        $stok->update(['jumlah_stok' => $stokSesudah]);

                        StokMovement::create([
                            'barang_id'      => $item->barang_id,
                            'jenis'          => 'masuk',
                            'qty'            => $item->qty,
                            'stok_sebelum'   => $stokSebelum,
                            'stok_sesudah'   => $stokSesudah,
                            'referensi_tipe' => 'penjualan_draft',
                            'referensi_id'   => $draft->id,
                            'keterangan'     => 'Pembatalan stok pesanan ' . $draft->kode_penjualan,
                            'created_by'     => $draft->created_by,
                        ]);
                    }
                }

                PenjualanDraftItem::where('penjualan_draft_id', $draft->id)->delete();
                PenjualanAddress::where('penjualan_draft_id', $draft->id)->delete();
                PenjualanShipment::where('penjualan_draft_id', $draft->id)->delete();
                $draft->delete();
            }

            $locked->update([
                'status' => $status,
                'penjualan_draft_id' => null,
            ]);

            return true;
        });
    }

    public function cancelExpiredPayments(): int
    {
        $payments = Pembayaran::where('status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->orderBy('id')
            ->get();

        $cancelled = 0;

        foreach ($payments as $payment) {
            if ($this->cancel($payment)) {
                $cancelled++;
            }
        }

        return $cancelled;
    }
}
