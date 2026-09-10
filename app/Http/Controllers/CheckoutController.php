<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DiscountProduct;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\PenjualanAddress;
use App\Models\PenjualanDetail;
use App\Models\PenjualanDraft;
use App\Models\PenjualanDraftItem;
use App\Models\PenjualanShipment;
use App\Models\PengaturanWeb;
use App\Models\ShippingRateCache;
use App\Models\ShippingRateCacheItem;
use App\Models\ShippingRateCacheRate;
use App\Models\UserAddress;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private $allowedServices = [
        'jne'       => ['reg'],
        'sicepat'   => ['reg'],
        'jnt'       => ['ez'],
        'anteraja'  => ['reg'],
        'idexpress' => ['reg'],
        'lion'      => ['reg_pack'],
    ];

    private function findInsufficientStock($items)
    {
        foreach ($items as $item) {
            $stok = \App\Models\StokBarang::where('barang_id', $item->barang_id)->value('jumlah_stok') ?? 0;
            if ($item->qty > $stok) {
                return ['barang' => $item->barang, 'qty' => $item->qty, 'stok' => $stok];
            }
        }

        return null;
    }

    public function index(Request $request)
    {
        $cartIds = $request->query('items');
        $buyNowBarangName = $request->query('nama_barang');

        if ($cartIds) {
            $ids = explode(',', $cartIds);
            $items = Cart::with('barang.produk.brand')
                ->where('user_id', Auth::id())
                ->whereIn('id', $ids)
                ->get();

            if ($items->isEmpty()) {
                return redirect()->route('dashboard.keranjang');
            }

            $insufficient = $this->findInsufficientStock($items);
            if ($insufficient) {
                return redirect()->route('dashboard.keranjang');
            }

            $checkoutSource = [
                'type' => 'cart',
                'ids' => array_map('intval', $ids),
            ];
        } elseif ($buyNowBarangName) {
            $barang = \App\Models\Barang::with('produk.brand')
                ->where('nama_barang', $buyNowBarangName)
                ->first();

            if (!$barang) {
                return redirect()->route('dashboard.keranjang');
            }

            $qty = max(1, min((int) ($request->query('qty') ?? 1), 99));
            $item = (object) [
                'barang' => $barang,
                'barang_id' => $barang->id,
                'qty' => $qty,
            ];
            $items = collect([$item]);

            $insufficient = $this->findInsufficientStock($items);
            if ($insufficient) {
                return redirect()->to('/product/' . $barang->produk->slug);
            }

            $checkoutSource = [
                'type' => 'buynow',
                'nama_barang' => $barang->nama_barang,
                'qty' => $qty,
            ];
        } else {
            return redirect()->route('dashboard.keranjang');
        }

        $user = Auth::user();
        $allAddresses = UserAddress::where('user_id', $user->id)->get()->sortByDesc('is_default');

        $selectedAddressId = $request->query('address_id');
        if ($selectedAddressId) {
            $defaultAddress = $allAddresses->firstWhere('id', $selectedAddressId) ?? $allAddresses->first();
        } else {
            $defaultAddress = $allAddresses->firstWhere('is_default', true) ?? $allAddresses->first();
        }

        $subtotal = 0;
        $items->each(function ($item) use (&$subtotal) {
            $normal = (float) ($item->barang->produk->harga_normal ?? 0);
            $activeDiscounts = DiscountProduct::with('discount')
                ->where('product_id', $item->barang->produk_id)
                ->where('status', 'active')
                ->get()
                ->filter(fn($dp) => $dp->discount);

            $totalPercent = 0;
            $totalFixed = 0;
            foreach ($activeDiscounts as $dp) {
                $d = $dp->discount;
                if ($d->type === 'percentage') $totalPercent += (float) $d->value;
                elseif ($d->type === 'fixed') $totalFixed += (float) $d->value;
            }

            $discounted = $normal;
            $discounted = $discounted * (1 - $totalPercent / 100);
            $discounted = max(0, $discounted - $totalFixed);

            $item->finalPrice = $discounted;
            $item->normalPrice = $normal;
            $item->hasDiscount = $activeDiscounts->isNotEmpty();
            $item->discountPercent = $totalPercent + ($normal > 0 ? round(($totalFixed / $normal) * 100) : 0);
            $subtotal += $discounted * $item->qty;
        });

        $originAreaId = PengaturanWeb::where('key', 'Origin Area Id')->value('value');
        $shippingOptions = [];

        if ($originAreaId && $defaultAddress && $defaultAddress->area_id) {
            $shippingOptions = $this->fetchShippingRates($originAreaId, $defaultAddress->area_id, $items);
        }

        $vouchers = \App\Models\UserVoucher::with('voucher')
            ->where('user_id', $user->id)
            ->where('status', 'unused')
            ->whereHas('voucher', function ($q) {
                $q->where('status', 'active')
                    ->where(function ($q2) {
                        $q2->whereNull('start_at')->orWhere('start_at', '<=', now());
                    })
                    ->where(function ($q2) {
                        $q2->whereNull('end_at')->orWhere('end_at', '>=', now());
                    });
            })
            ->get();

        $activeVoucher = $vouchers->sortByDesc(fn($uv) => $uv->voucher?->value ?? 0)->first();

        return view('checkout', [
            'items' => $items,
            'user' => $user,
            'allAddresses' => $allAddresses,
            'defaultAddress' => $defaultAddress,
            'subtotal' => $subtotal,
            'shippingOptions' => $shippingOptions,
            'vouchers' => $vouchers,
            'activeVoucher' => $activeVoucher,
            'checkoutSource' => $checkoutSource,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'items' => 'nullable',
                'source' => 'nullable',
                'address_id' => 'required|integer',
                'shipping_code' => 'required|string',
                'shipping_price' => 'required|numeric|min:0',
                'estimation_days' => 'nullable|integer|min:1|max:30',
                'payment_method' => 'required|string',
                'catatan' => 'nullable|string|max:200',
                'voucher_id' => 'nullable|integer',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => collect($e->errors())->flatten()->first() ?? 'Data checkout tidak lengkap.',
            ], 422);
        }

        $pendingPayments = \App\Models\Pembayaran::whereIn('status', ['pending', 'paid_confirmation'])
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            })
            ->whereHas('penjualanDraft', function ($q) {
                $q->where('created_by', Auth::id());
            })
            ->with(['penjualanDraft' => fn ($q) => $q->with('items.barang.produk')])
            ->orderBy('id', 'desc')
            ->get();

        if ($pendingPayments->isNotEmpty()) {
            return response()->json([
                'message' => 'Anda masih memiliki pembayaran yang belum diselesaikan. Selesaikan terlebih dahulu sebelum membuat pesanan baru.',
                'pending_payments' => $pendingPayments->map(function ($p) {
                    $draft = $p->penjualanDraft;
                    return [
                        'id' => $p->id,
                        'order_code' => $draft->kode_penjualan ?? '-',
                        'items' => $draft && $draft->items ? $draft->items->map(function ($it) {
                            return [
                                'nama' => $it->barang->produk->nama_produk ?? $it->barang->nama_barang ?? 'Produk',
                                'qty' => $it->qty,
                            ];
                        })->values() : [],
                        'amount' => (float) $p->amount,
                        'expired_at' => optional($p->expired_at)->format('d M Y H:i'),
                        'payment_url' => $p->payment_method === 'bank_transfer'
                            ? route('checkout.transfer', $p->id)
                            : route('checkout.payment', $p->id),
                    ];
                })->values(),
            ], 422);
        }

        $source = $request->input('source');
        if (!is_array($source)) {
            $source = ['type' => 'cart', 'ids' => array_map('intval', explode(',', (string) $request->items))];
        }

        $cartIds = [];
        if (($source['type'] ?? 'cart') === 'buynow') {
            $barang = \App\Models\Barang::with('produk.brand')
                ->where('nama_barang', $source['nama_barang'] ?? null)
                ->first();
            if (!$barang) {
                return response()->json(['message' => 'Produk tidak ditemukan.'], 422);
            }
            $qty = max(1, min((int) ($source['qty'] ?? 1), 99));
            $item = (object) [
                'barang' => $barang,
                'barang_id' => $barang->id,
                'qty' => $qty,
            ];
            $items = collect([$item]);
        } else {
            $ids = array_map('intval', $source['ids'] ?? []);
            $items = Cart::with('barang.produk.brand')
                ->where('user_id', Auth::id())
                ->whereIn('id', $ids)
                ->get();
            $cartIds = $items->pluck('id')->map(fn($v) => (int) $v)->values()->toArray();
        }

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Keranjang tidak ditemukan.'], 422);
        }

        $insufficient = $this->findInsufficientStock($items);
        if ($insufficient) {
            $nama = $insufficient['barang']->produk->nama_produk ?? $insufficient['barang']->nama_barang ?? 'Produk';
            $message = $insufficient['stok'] <= 0
                ? 'Stok "' . $nama . '" sedang habis. Kurangi jumlah atau pilih produk lain.'
                : 'Stok "' . $nama . '" hanya tersisa ' . $insufficient['stok'] . ' (dipesan ' . $insufficient['qty'] . '). Kurangi jumlah atau pilih produk lain.';
            return response()->json(['message' => $message], 422);
        }

        $address = UserAddress::where('id', $request->address_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $subtotal = 0;
        $items->each(function ($item) use (&$subtotal) {
            $normal = (float) ($item->barang->produk->harga_normal ?? 0);
            $activeDiscounts = DiscountProduct::with('discount')
                ->where('product_id', $item->barang->produk_id)
                ->where('status', 'active')
                ->get()
                ->filter(fn($dp) => $dp->discount);

            $totalPercent = 0;
            $totalFixed = 0;
            foreach ($activeDiscounts as $dp) {
                $d = $dp->discount;
                if ($d->type === 'percentage') $totalPercent += (float) $d->value;
                elseif ($d->type === 'fixed') $totalFixed += (float) $d->value;
            }

            $discounted = $normal;
            $discounted = $discounted * (1 - $totalPercent / 100);
            $discounted = max(0, $discounted - $totalFixed);

            $item->finalPrice = $discounted;
            $subtotal += $discounted * $item->qty;
        });

        $shippingCost = (float) $request->shipping_price;
        [$shippingCourier, $shippingService] = array_pad(explode('-', $request->shipping_code, 2), 2, '');
        $estimationDays = $request->filled('estimation_days') ? (int) $request->estimation_days : null;

        $voucherDiscount = 0;
        $activeVoucher = null;
        if ($request->voucher_id) {
            $activeVoucher = UserVoucher::with('voucher')
                ->where('user_id', Auth::id())
                ->where('id', $request->voucher_id)
                ->where('status', 'unused')
                ->whereHas('voucher', function ($q) {
                    $q->where('status', 'active')
                        ->where(function ($q2) {
                            $q2->whereNull('start_at')->orWhere('start_at', '<=', now());
                        })
                        ->where(function ($q2) {
                            $q2->whereNull('end_at')->orWhere('end_at', '>=', now());
                        });
                })
                ->first();

            if ($activeVoucher) {
                $v = $activeVoucher->voucher;
                if ($v->type === 'percent') {
                    $voucherDiscount = $subtotal * (float) $v->value / 100;
                } elseif ($v->type === 'fixed') {
                    $voucherDiscount = (float) $v->value;
                } else {
                    $voucherDiscount = $shippingCost;
                }
                if ((float) $v->maximum_discount > 0) {
                    $voucherDiscount = min($voucherDiscount, (float) $v->maximum_discount);
                }
                $voucherDiscount = min($voucherDiscount, $subtotal + $shippingCost);
            }
        }

        $total = max($subtotal + $shippingCost - $voucherDiscount, 0);

        $index = 1;
        $kode = 'PJL-' . now()->format('YmdHis') . '-' . $index;
        if (\App\Models\Penjualan::where('kode_penjualan', $kode)->exists()) {
            $kode .= '-' . $index . '-' . now()->format('His');
        }

        $keterangan = $request->catatan;
        if ($activeVoucher) {
            $keterangan = '[Voucher:' . $activeVoucher->id . '] ' . $keterangan;
        }
        if (!empty($cartIds)) {
            $keterangan = '[Cart:' . implode(',', $cartIds) . '] ' . $keterangan;
        }

        $draft = PenjualanDraft::create([
            'kode_penjualan' => $kode,
            'tanggal' => now(),
            'total_harga' => $total,
            'harga_discount' => $voucherDiscount,
            'shipping_cost' => $shippingCost,
            'subtotal_harga' => $subtotal,
            'keterangan' => $keterangan,
            'order_web' => true,
            'status' => 'waiting_payment',
            'created_by' => Auth::id(),
        ]);

        foreach ($items as $item) {
            PenjualanDraftItem::create([
                'penjualan_draft_id' => $draft->id,
                'barang_id' => $item->barang_id,
                'qty' => $item->qty,
                'harga' => $item->finalPrice,
                'subtotal' => $item->finalPrice * $item->qty,
            ]);
        }

        PenjualanAddress::create([
            'penjualan_draft_id' => $draft->id,
            'recipient_name' => $address->receiver_name,
            'phone' => $address->phone,
            'province' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'label' => $address->label,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
        ]);

        PenjualanShipment::create([
            'penjualan_draft_id' => $draft->id,
            'courier' => $shippingCourier,
            'service' => $shippingService,
            'tracking_number' => null,
            'shipping_cost' => $shippingCost,
            'estimation_days' => $estimationDays,
        ]);

        $isBankTransfer = strtolower(trim((string) $request->payment_method)) === 'bca';

        $pembayaran = Pembayaran::create([
            'penjualan_draft_id' => $draft->id,
            'payment_method' => $isBankTransfer ? 'bank_transfer' : $request->payment_method,
            'payment_type' => $isBankTransfer ? 'bank_transfer' : null,
            'amount' => $total,
            'status' => 'pending',
            'expired_at' => now()->addHours(24),
        ]);

        if ($isBankTransfer) {
            Notifikasi::create([
                'judul' => 'Pesanan Baru Dari Website',
                'isi' => 'Pesanan ' . $kode . ' dari website telah dibuat pada ' . now()->translatedFormat('l, d F Y H:i:s') . ' dengan pembayaran transfer bank (BCA) dan akan dikonfirmasi oleh admin. Total: Rp' . number_format($total, 0, ',', '.'),
                'tipe' => 'pesanan',
                'link' => route('checkout.transfer', $pembayaran->id),
                'payload' => [
                    'kode_penjualan' => $kode,
                    'total' => $total,
                    'penjualan_draft_id' => $draft->id,
                    'pembayaran_id' => $pembayaran->id,
                    'metode' => 'bank_transfer',
                ],
                'created_by' => Auth::id(),
            ]);

            return response()->json(['redirect' => route('checkout.transfer', $pembayaran->id)]);
        }

        Notifikasi::create([
            'judul' => 'Pesanan Baru Dari Website',
            'isi' => 'Pesanan ' . $kode . ' dari website telah dibuat pada ' . now()->translatedFormat('l, d F Y H:i:s') . '. Total: Rp' . number_format($total, 0, ',', '.'),
            'tipe' => 'pesanan',
            'link' => route('checkout.payment', $pembayaran->id),
            'payload' => ['kode_penjualan' => $kode, 'total' => $total, 'penjualan_draft_id' => $draft->id],
            'created_by' => Auth::id(),
        ]);

        Notifikasi::create([
            'judul' => 'Pembayaran Dibuat',
            'isi' => 'Pembayaran untuk pesanan ' . $kode . ' telah dibuat pada ' . now()->translatedFormat('l, d F Y H:i:s') . '. Metode pembayaran: ' . $request->payment_method . '. Total: Rp' . number_format($total, 0, ',', '.') . '. Pembeli: ' . ($address->receiver_name ?: Auth::user()->full_name ?? Auth::user()->nama) . ' (' . ($address->phone ?: Auth::user()->phone) . ').',
            'tipe' => 'pembayaran',
            'link' => route('checkout.payment', $pembayaran->id),
            'payload' => [
                'kode_penjualan' => $kode,
                'total' => $total,
                'pembayaran_id' => $pembayaran->id,
                'penjualan_draft_id' => $draft->id,
                'pembeli' => [
                    'nama' => $address->receiver_name ?: Auth::user()->full_name ?? Auth::user()->nama,
                    'no_hp' => $address->phone ?: Auth::user()->phone,
                ],
            ],
            'created_by' => Auth::id(),
        ]);

        $snap = $this->createMidtransSnap($draft, $pembayaran);
        if ($snap && !empty($snap['token'])) {
            $pembayaran->update([
                'order_id_midtrans' => $snap['order_id'],
                'snap_token' => $snap['token'],
            ]);
            return response()->json(['redirect' => route('checkout.payment', $pembayaran->id)]);
        }

        return response()->json(['redirect' => route('checkout.payment', $pembayaran->id)]);
    }

    public function transfer($id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->view('errors.not-found', [], 404);
        }

        $draft = PenjualanDraft::with('items.barang.produk')
            ->where('id', $pembayaran->penjualan_draft_id)
            ->first();

        $belongsToUser = false;
        if ($draft) {
            $belongsToUser = (int) $draft->created_by === Auth::id();
        } elseif ($pembayaran->penjualan_id) {
            $belongsToUser = (int) Penjualan::where('id', $pembayaran->penjualan_id)->value('created_by') === Auth::id();
        }

        if (!$belongsToUser) {
            return response()->view('errors.not-found', [], 404);
        }

        if ($pembayaran->status === 'paid') {
            return redirect()->route('checkout.success', $pembayaran->penjualan_id);
        }

        $confirmed = $pembayaran->status !== 'pending';

        $shipment = $draft ? PenjualanShipment::where('penjualan_draft_id', $draft->id)->first() : null;
        $noRekening = PengaturanWeb::where('key', 'no_rekening')->value('value') ?? '';

        return view('checkout-transfer', [
            'pembayaran' => $pembayaran,
            'draft' => $draft,
            'shipment' => $shipment,
            'noRekening' => $noRekening,
            'confirmed' => $confirmed,
        ]);
    }

    public function transferConfirm(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->view('errors.not-found', [], 404);
        }

        $belongsToUser = false;
        if ($pembayaran->penjualan_draft_id) {
            $belongsToUser = (int) PenjualanDraft::where('id', $pembayaran->penjualan_draft_id)->value('created_by') === Auth::id();
        } elseif ($pembayaran->penjualan_id) {
            $belongsToUser = (int) Penjualan::where('id', $pembayaran->penjualan_id)->value('created_by') === Auth::id();
        }

        if (!$belongsToUser) {
            return response()->view('errors.not-found', [], 404);
        }

        if ($pembayaran->status !== 'pending') {
            return redirect()->route('checkout.transfer', $pembayaran->id);
        }

        try {
            $request->validate([
                'proof_img' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ], [
                'proof_img.required' => 'Bukti transfer wajib diunggah.',
                'proof_img.image' => 'File harus berupa gambar.',
                'proof_img.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
                'proof_img.max' => 'Ukuran gambar maksimal 5MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        }

        $path = $request->file('proof_img')->store('proofs', 'public');

        $pembayaran->update([
            'status' => 'paid_confirmation',
            'proof_img' => $path,
        ]);

        return redirect()->route('checkout.transfer', $pembayaran->id);
    }

    public function payment($id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->view('errors.not-found', [], 404);
        }

        $draft = PenjualanDraft::with('items.barang.produk', 'creator')
            ->where('id', $pembayaran->penjualan_draft_id)
            ->first();

        $belongsToUser = false;
        if ($draft) {
            $belongsToUser = (int) $draft->created_by === Auth::id();
        } elseif ($pembayaran->penjualan_id) {
            $belongsToUser = (int) Penjualan::where('id', $pembayaran->penjualan_id)->value('created_by') === Auth::id();
        }

        if (!$belongsToUser) {
            return response()->view('errors.not-found', [], 404);
        }

        if ($pembayaran->status !== 'paid') {
            app(MidtransController::class)->syncStatus($pembayaran);
            $pembayaran->refresh();
        }

        if ($pembayaran->status === 'paid') {
            if ($pembayaran->penjualan_id) {
                return redirect()->route('checkout.success', $pembayaran->penjualan_id);
            }
            return redirect()->route('dashboard.pembayaran');
        }

        if (!$draft) {
            return response()->view('errors.not-found', [], 404);
        }

        $address = PenjualanAddress::where('penjualan_draft_id', $draft->id)->first();
        $shipment = PenjualanShipment::where('penjualan_draft_id', $draft->id)->first();

        $clientKey = PengaturanWeb::where('key', 'Midtrans Client Key')->value('value') ?? '';
        $isProduction = strtolower(PengaturanWeb::where('key', 'Midtrans Environment')->value('value') ?? 'sandbox') === 'production';
        $snapToken = null;
        // Log::info("tes");
        
        if ($pembayaran->order_id_midtrans) {
            $snapToken = $this->getSnapToken($pembayaran->order_id_midtrans, $draft, $pembayaran);
        } else {
            $orderId = $draft->kode_penjualan . '-' . strtoupper(Str::random(4));
            $snapToken = $this->getSnapToken($orderId, $draft, $pembayaran);
            if ($snapToken) {
                $pembayaran->update(['order_id_midtrans' => $orderId]);
            }
        }

        return view('checkout-payment', [
            'draft' => $draft,
            'pembayaran' => $pembayaran,
            'address' => $address,
            'shipment' => $shipment,
            'clientKey' => $clientKey,
            'snapToken' => $snapToken,
            'isProduction' => $isProduction,
            'midtransOrderId' => $pembayaran->order_id_midtrans,
        ]);
    }

    public function paymentStatus($id)
    {

        $pembayaran = Pembayaran::where('order_id_midtrans', $id)->first();

        if (!$pembayaran) {
            return response()->json(['status' => 'error', 'message' => 'Pembayaran tidak ditemukan.']);
        }

        $draft = PenjualanDraft::where('id', $pembayaran->penjualan_draft_id)->first();
        $belongsToUser = false;
        if ($draft) {
            $belongsToUser = (int) $draft->created_by === Auth::id();
        } elseif ($pembayaran->penjualan_id) {
            $belongsToUser = (int) Penjualan::where('id', $pembayaran->penjualan_id)->value('created_by') === Auth::id();
        }

        if (!$belongsToUser) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($pembayaran->status === 'paid_overide') {

            app(MidtransController::class)->finalize($pembayaran, $pembayaran->payment_type, $pembayaran->transaction_id);
            $pembayaran->refresh();
        }

        if ($pembayaran->status === 'paid' && $pembayaran->penjualan_id) {
            return response()->json([
                'status' => 'paid',
                'penjualan_id' => $pembayaran->penjualan_id,
                'redirect' => route('checkout.success', $pembayaran->penjualan_id),
            ]);
        }

        if (in_array($pembayaran->status, ['deny', 'cancel', 'expire', 'failure'])) {
            return response()->json(['status' => $pembayaran->status, 'penjualan_id' => $pembayaran->penjualan_id]);
        }

        return response()->json([
            'status' => $pembayaran->status ?? 'pending',
            'penjualan_id' => $pembayaran->penjualan_id,
        ]);
    }

    public function paymentSync($id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->json(['status' => 'error', 'message' => 'Pembayaran tidak ditemukan.'], 404);
        }

        $draft = PenjualanDraft::where('id', $pembayaran->penjualan_draft_id)->first();
        $belongsToUser = false;
        if ($draft) {
            $belongsToUser = (int) $draft->created_by === Auth::id();
        } elseif ($pembayaran->penjualan_id) {
            $belongsToUser = (int) Penjualan::where('id', $pembayaran->penjualan_id)->value('created_by') === Auth::id();
        }

        if (!$belongsToUser) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($pembayaran->status !== 'paid') {
            app(MidtransController::class)->syncStatus($pembayaran);
            $pembayaran->refresh();
        }

        if ($pembayaran->status === 'paid' && $pembayaran->penjualan_id) {
            return response()->json([
                'status' => 'paid',
                'penjualan_id' => $pembayaran->penjualan_id,
                'redirect' => route('checkout.success', $pembayaran->penjualan_id),
            ]);
        }

        return response()->json([
            'status' => $pembayaran->status ?? 'pending',
            'penjualan_id' => $pembayaran->penjualan_id,
        ]);
    }

    public function success($id)
    {
        $penjualan = Penjualan::with(['detail.barang', 'address', 'shipment'])
            ->where('id', $id)
            ->where('created_by', Auth::id())
            ->first();

        if (!$penjualan) {
            return response()->view('errors.not-found', [], 404);
        }

        $pembayaran = Pembayaran::where('penjualan_id', $penjualan->id)->first();

        return view('checkout-success', [
            'penjualan' => $penjualan,
            'pembayaran' => $pembayaran,
        ]);
    }

    private function createMidtransSnap($draft, $pembayaran)
    {
        $serverKey = PengaturanWeb::where('key', 'Midtrans Server Key')->value('value') ?? '';

        if (!$serverKey) {
            return null;
        }

        $orderId = $draft->kode_penjualan . '-' . strtoupper(Str::random(4));
        $itemDetails = [];
        foreach ($draft->items as $draftItem) {
            $itemDetails[] = [
                'id' => $draftItem->barang_id,
                'price' => (int) round((float) $draftItem->harga),
                'quantity' => (int) $draftItem->qty,
                'name' => substr($draftItem->barang->nama_barang ?? 'Produk', 0, 50),
            ];
        }

        if ((float) $draft->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) round((float) $draft->shipping_cost),
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        if ((float) $draft->harga_discount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) round((float) $draft->harga_discount),
                'quantity' => 1,
                'name' => 'Diskon Voucher',
            ];
        }

        $body = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round((float) $draft->total_harga),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $draft->creator->full_name ?? $draft->creator->nama ?? 'Customer',
                'email' => $draft->creator->email ?? '',
                'phone' => $draft->creator->phone,
                "shipping_address" => [
                    'address' => $draft->address->label . ' - ' . $draft->address->address,
                    'city' => $draft->address->city,
                    'postal_code' => $draft->address->postal_code,
                    'country_code' => "IDN",
                ]
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];

        $isProduction = strtolower(PengaturanWeb::where('key', 'Midtrans Environment')->value('value') ?? 'sandbox') === 'production';
        $baseUrl = $isProduction ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':'),
            ],
            CURLOPT_TIMEOUT => 20,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = $httpCode === 200 || $httpCode === 201 ? json_decode($response, true) : [];

        return [
            'token' => $data['token'] ?? null,
            'order_id' => $orderId,
        ];
    }

    private function getSnapToken($orderId, $draft, $pembayaran)
    {
        $serverKey = PengaturanWeb::where('key', 'Midtrans Server Key')->value('value') ?? '';

        if (!$serverKey) {
            return null;
        }

        if ($pembayaran->snap_token) {
            return $pembayaran->snap_token;
        }

        $itemDetails = [];
        foreach ($draft->items as $draftItem) {
            $itemDetails[] = [
                'id' => $draftItem->barang_id,
                'price' => (int) round((float) $draftItem->harga),
                'quantity' => (int) $draftItem->qty,
                'name' => substr($draftItem->barang->nama_barang ?? 'Produk', 0, 50),
            ];
        }

        if ((float) $draft->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) round((float) $draft->shipping_cost),
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        if ((float) $draft->harga_discount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) round((float) $draft->harga_discount),
                'quantity' => 1,
                'name' => 'Diskon Voucher',
            ];
        }

        $isProduction = strtolower(PengaturanWeb::where('key', 'Midtrans Environment')->value('value') ?? 'sandbox') === 'production';
        $baseUrl = $isProduction ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $body = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round((float) $draft->total_harga),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $draft->creator->full_name ?? $draft->creator->nama ?? 'Customer',
                'email' => $draft->creator->email,
                'phone' => $draft->creator->phone,
                "shipping_address" => [
                    'address' => $draft->address->label . ' - ' . $draft->address->address,
                    'city' => $draft->address->city,
                    'postal_code' => $draft->address->postal_code,
                    'country_code' => "IDN",
                ]
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':'),
            ],
            CURLOPT_TIMEOUT => 20,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = $httpCode === 200 || $httpCode === 201 ? json_decode($response, true) : [];

        if (!empty($data['token'])) {
            $pembayaran->update(['snap_token' => $data['token']]);
        }

        return $data['token'] ?? null;
    }

    private function fetchShippingRates($originAreaId, $destinationAreaId, $cartItems)
    {
        // Build map per barang_id => quantity (global, tidak per user) untuk pencocokan cache yang akurat
        $cartMap = $cartItems->mapWithKeys(fn($it) => [
            (int) ($it->barang_id ?? $it['barang_id'] ?? 0) => (int) ($it->qty ?? $it->quantity ?? 0)
        ])->sortKeys()->toArray();
        $cartCount = count($cartMap);

        // Cari cache global per rute yang barang & quantity-nya identik dengan checkout saat ini
        $existingCache = ShippingRateCache::where('origin_area_id', $originAreaId)
            ->where('destination_area_id', $destinationAreaId)
            ->with(['items', 'rates'])
            ->get()
            ->first(function ($cache) use ($cartMap, $cartCount) {
                if ($cache->items->count() !== $cartCount) {
                    return false;
                }
                $cachedMap = $cache->items->mapWithKeys(fn($i) => [
                    (int) $i->barang_id => (int) $i->quantity
                ])->sortKeys()->toArray();
                return $cachedMap === $cartMap;
            });

        if ($existingCache) {
            $rate = $existingCache->rates->first();
            if ($rate && !empty($rate->response_json)) {
                $decoded = json_decode($rate->response_json, true);
                if (is_array($decoded) && !empty($decoded)) {
                    return $decoded;
                }
            }
        }

        $apiKey = PengaturanWeb::where('key', 'Api Key Biteship')->value('value');
        if (!$apiKey) {
            return [];
        }

        $itemsPayload = [];
        foreach ($cartItems as $item) {
            $nama = $item->barang->produk->nama_produk ?? '';
            $varian = $item->barang->nama_barang ?? '';
            $desc = $varian ? $nama . ' - ' . $varian : $nama;
            $itemsPayload[] = [
                'name' => $nama,
                'description' => $desc,
                'value' => (int) ($item->finalPrice ?? 0),
                'weight' => $item->barang->produk->berat_gram,
                'length' => $item->barang->produk->panjang_cm,
                'width' => $item->barang->produk->lebar_cm,
                'height' => $item->barang->produk->tinggi_cm,
                'quantity' => (int) $item->qty,
            ];
        }

        $body = [
            'origin_area_id' => $originAreaId,
            'destination_area_id' => $destinationAreaId,
            'couriers' => 'jne,sicepat,jnt,anteraja,idexpress,lion',
            'items' => $itemsPayload,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.biteship.com/v1/rates/couriers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return [];
        }

        $data = json_decode($response, true);
        if (empty($data['success']) || empty($data['pricing'])) {
            return [];
        }

        $filtered = [];
        foreach ($data['pricing'] as $p) {
            $code = $p['courier_code'] ?? '';
            $svcCode = $p['courier_service_code'] ?? '';
            if (isset($this->allowedServices[$code]) && in_array($svcCode, $this->allowedServices[$code])) {
                $filtered[] = [
                    'company' => $p['company'] ?? '',
                    'courier_name' => $p['courier_name'] ?? '',
                    'courier_code' => $code,
                    'courier_service_name' => $p['courier_service_name'] ?? '',
                    'courier_service_code' => $svcCode,
                    'description' => $p['description'] ?? '',
                    'duration' => $p['duration'] ?? '',
                    'shipment_duration_range' => $p['shipment_duration_range'] ?? '',
                    'shipment_duration_unit' => $p['shipment_duration_unit'] ?? '',
                    'service_type' => $p['service_type'] ?? '',
                    'shipping_type' => $p['shipping_type'] ?? '',
                    'price' => (int) ($p['price'] ?? 0),
                    // 'available_for_cash_on_delivery' => !empty($p['available_for_cash_on_delivery']),
                    'available_for_cash_on_delivery' => "",
                    'available_for_insurance' => !empty($p['available_for_insurance']),
                ];
            }
        }

        usort($filtered, fn($a, $b) => $a['price'] <=> $b['price']);

        $cache = ShippingRateCache::create([
            'user_id' => Auth::id(),
            'origin_area_id' => $originAreaId,
            'destination_area_id' => $destinationAreaId,
        ]);

        foreach ($cartItems as $item) {
            ShippingRateCacheItem::create([
                'shipping_rate_cache_id' => $cache->id,
                'barang_id' => $item->barang_id,
                'quantity' => (int) $item->qty,
                'weight' => 900,
                'length' => 25,
                'width' => 7,
                'height' => 2.5,
                'value' => (int) ($item->finalPrice ?? 0),
            ]);
        }

        ShippingRateCacheRate::create([
            'shipping_rate_cache_id' => $cache->id,
            'response_json' => json_encode($filtered),
        ]);

        return $filtered;
    }
}
