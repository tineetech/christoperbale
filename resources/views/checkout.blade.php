@extends('layouts.app')

@section('title', 'Checkout — CHRISBALE')

@push('styles')
<style>
.checkout-wrap *{font-family:'Inter',sans-serif;box-sizing:border-box;}
.checkout-wrap{width:100%;max-width:1100px;margin:20px auto;padding:0 20px 80px;overflow-x:hidden;}
.checkout-head{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin:0 0 20px;padding-top:16px;}
.checkout-head h1{font-size:26px;font-weight:700;margin-bottom:4px;letter-spacing:-0.01em;}
.checkout-head .subtitle{color:var(--ink-muted);font-size:14px;}

/* Progress steps */
.checkout-steps{display:flex;align-items:center;gap:0;margin-bottom:32px;list-style:none;padding:0;}
.checkout-steps li{display:flex;align-items:center;gap:8px;flex:1;position:relative;}
.checkout-steps li:not(:last-child)::after{content:'';flex:1;height:1px;background:var(--line);margin:0 12px;}
.checkout-steps .step-dot{width:26px;height:26px;border-radius:50%;background:var(--accent);color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.checkout-steps .step-label{font-size:12px;font-weight:600;color:var(--ink);white-space:nowrap;}
.checkout-steps li.is-pending .step-dot{background:var(--bg-card);border:1.5px solid var(--line);color:var(--ink-muted);}
.checkout-steps li.is-pending .step-label{color:var(--ink-muted);}

.checkout-back{display:inline-flex;align-items:center;gap:4px;font-size:13px;color:var(--ink-muted);text-decoration:none;margin-bottom:16px;}
.checkout-back:hover{color:var(--accent);}

/* Grid layout: main + sticky summary */
.checkout-grid{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;width:100%;min-width:0;}
.checkout-main{min-width:0;}
@media (max-width:860px){.checkout-grid{grid-template-columns:1fr;}}

.checkout-section{background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:24px;margin-bottom:16px;transition:border-color .2s;}
.checkout-section h2{font-size:15px;font-weight:600;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;gap:8px;}
.checkout-section h2 .h2-left{display:flex;align-items:center;gap:8px;}
.checkout-section h2 svg{width:18px;height:18px;stroke:var(--accent);fill:none;stroke-width:2;flex-shrink:0;}
.checkout-section h2 .h2-action{font-size:12px;font-weight:600;color:var(--accent);cursor:pointer;text-decoration:none;}
.checkout-section h2 .h2-action:hover{text-decoration:underline;}

/* Address — selectable card */
.address-card{display:flex;align-items:flex-start;gap:12px;padding:14px;border:1.5px solid var(--line);border-radius:10px;margin-bottom:10px;cursor:pointer;transition:border-color .15s, background .15s;}
.address-card:last-child{margin-bottom:0;}
.address-card:hover{border-color:color-mix(in srgb, var(--accent) 45%, var(--line));}
.address-card:has(input:checked){border-color:var(--accent);background:color-mix(in srgb, var(--accent) 6%, var(--bg-card));}
.address-card input[type=radio]{accent-color:var(--accent);margin-top:3px;flex-shrink:0;}
.address-card .addr-label{font-weight:600;font-size:13px;}
.address-card .addr-text{font-size:12px;color:var(--ink-muted);margin-top:2px;line-height:1.5;}
.address-card .addr-tag{display:inline-block;font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;background:var(--accent);color:#fff;padding:2px 8px;border-radius:999px;margin-top:6px;}
.addr-empty{font-size:13px;color:var(--ink-muted);text-align:center;padding:20px 0;}
.addr-empty a{color:var(--accent);font-weight:600;}

/* Products */
.checkout-product{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--line);}
.checkout-product:last-child{border-bottom:none;padding-bottom:0;}
.checkout-product img{width:56px;height:56px;border-radius:8px;object-fit:cover;flex-shrink:0;background:#f0f0f0;}
.checkout-product .cp-info{flex:1;min-width:0;}
.checkout-product .cp-info .cp-name{font-size:13px;font-weight:600;color:var(--ink);}
.checkout-product .cp-info .cp-variant{font-size:11px;color:var(--ink-muted);margin-top:2px;}
.checkout-product .cp-right{text-align:right;flex-shrink:0;}
.checkout-product .cp-price{font-size:14px;font-weight:700;color:var(--accent);white-space:nowrap;display:block;}
.checkout-product .cp-qty{font-size:11px;color:var(--ink-muted);white-space:nowrap;}
.checkout-items-count{font-size:12px;font-weight:600;color:var(--ink-muted);background:var(--line);padding:2px 10px;border-radius:999px;}

/* Shipping / Payment — selectable option rows */
.option-card{display:flex;align-items:center;gap:12px;padding:14px;border:1.5px solid var(--line);border-radius:10px;margin-bottom:10px;cursor:pointer;transition:border-color .15s, background .15s;}
.option-card:last-child{margin-bottom:0;}
.option-card:hover{border-color:color-mix(in srgb, var(--accent) 45%, var(--line));}
.option-card:has(input:checked){border-color:var(--accent);background:color-mix(in srgb, var(--accent) 6%, var(--bg-card));}
.option-card input[type=radio]{accent-color:var(--accent);flex-shrink:0;}
.option-card .opt-icon{width:34px;height:34px;border-radius:8px;background:color-mix(in srgb, var(--accent) 12%, transparent);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.option-card .opt-icon svg{width:17px;height:17px;stroke:var(--accent);fill:none;stroke-width:2;}
.option-card .opt-body{flex:1;min-width:0;}
.option-card .opt-name{font-size:13px;font-weight:600;}
.option-card .opt-desc{font-size:11px;color:var(--ink-muted);margin-top:1px;}
.option-card .opt-price{font-size:13px;font-weight:700;color:var(--ink);margin-left:auto;white-space:nowrap;}
.option-card .opt-badge{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--accent);background:color-mix(in srgb, var(--accent) 14%, transparent);padding:2px 6px;border-radius:5px;margin-left:6px;}

/* Notes */
.checkout-notes textarea{width:100%;border:1.5px solid var(--line);border-radius:10px;padding:12px 14px;font-size:13px;font-family:'Inter',sans-serif;color:var(--ink);resize:vertical;min-height:70px;transition:border-color .15s;}
.checkout-notes textarea:focus{outline:none;border-color:var(--accent);}
.checkout-notes .char-count{font-size:11px;color:var(--ink-muted);text-align:right;margin-top:4px;}

/* Sticky summary sidebar */
.order-summary{position:sticky;top:20px;background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:24px;}
.order-summary h2{font-size:15px;font-weight:600;margin-bottom:16px;}
.promo-row{display:flex;gap:8px;margin-bottom:20px;}
.promo-row input{flex:1;border:1.5px solid var(--line);border-radius:8px;padding:10px 12px;font-size:12px;font-family:'Inter',sans-serif;transition:border-color .15s;}
.promo-row input:focus{outline:none;border-color:var(--accent);}
.promo-row button{border:1.5px solid var(--accent);background:transparent;color:var(--accent);font-size:12px;font-weight:700;padding:0 16px;border-radius:8px;cursor:pointer;white-space:nowrap;transition:background .15s,color .15s;}
.promo-row button:hover{background:var(--accent);color:#fff;}
.promo-msg{font-size:11px;margin:-14px 0 16px;display:none;}
.promo-msg.show{display:block;}
.promo-msg.ok{color:#2a8a4d;}
.promo-msg.err{color:#c0392b;}

.summary-line{display:flex;justify-content:space-between;font-size:13px;color:var(--ink-muted);padding:7px 0;}
.summary-line.discount span:last-child{color:#2a8a4d;font-weight:600;}
.summary-line.total{border-top:1px solid var(--line);margin-top:8px;padding-top:14px;font-size:15px;font-weight:700;color:var(--ink);}
.summary-line.total span:last-child{color:var(--accent);font-size:19px;}

.btn-buy{width:100%;margin-top:18px;padding:15px;border:none;border-radius:var(--radius-sm);background:var(--accent);color:#fff;font-size:14px;font-weight:700;letter-spacing:0.04em;cursor:pointer;transition:background .2s, transform .1s;text-transform:uppercase;font-family:'Inter',sans-serif;}
.btn-buy:hover{background:color-mix(in srgb, var(--accent) 82%, black);}
.btn-buy:active{transform:scale(0.98);}

.trust-badges{display:flex;flex-direction:column;gap:10px;margin-top:20px;padding-top:18px;border-top:1px solid var(--line);}
.trust-badge{display:flex;align-items:center;gap:10px;font-size:11.5px;color:var(--ink-muted);}
.trust-badge svg{width:16px;height:16px;stroke:var(--accent);fill:none;stroke-width:2;flex-shrink:0;}

@media (max-width:860px){
  .order-summary{position:static;margin-top:8px;}
}

/* Mobile */
@media (max-width:600px){
  .checkout-wrap{padding:0 14px 60px;margin-top: -20px;}
  .checkout-head{padding-top:12px;margin-bottom:16px;}
  .checkout-head h1{font-size:21px;}
  .checkout-head .subtitle{font-size:13px;}

  .checkout-steps{margin-bottom:22px;gap:0;}
  .checkout-steps li:not(:last-child)::after{margin:0 6px;}
  .checkout-steps .step-dot{width:22px;height:22px;font-size:11px;}
  .checkout-steps .step-label{display:none;}

  .checkout-grid{gap:14px;}
  .checkout-section{padding:16px;margin-bottom:12px;border-radius:12px;}
  .checkout-section h2{font-size:14px;margin-bottom:12px;flex-wrap:wrap;}

  .address-card,.option-card{padding:12px;gap:10px;}
  .option-card .opt-icon{width:30px;height:30px;}
  .option-card .opt-price{white-space:nowrap;font-size:12px;}

  .checkout-product{gap:10px;}
  .checkout-product img,.img-placeholder{width:48px;height:48px;}
  .checkout-product .cp-name{font-size:12.5px;}

  .order-summary{padding:18px;}
  .promo-row{flex-wrap:wrap;}
  .promo-row input{min-width:0;flex:1 1 100%;}
  .promo-row button{flex:1;padding:10px;}
}
</style>
@endpush

@section('content')
<div class="wrap">
    <div class="checkout-wrap">
        <div class="checkout-head">
            <div>
                <h1>Checkout</h1>
                <p class="subtitle">Konfirmasi pesanan Anda sebelum membeli.</p>
            </div>
        </div>

        <ul class="checkout-steps">
            <li><span class="step-dot">1</span><span class="step-label">Keranjang</span></li>
            <li><span class="step-dot">2</span><span class="step-label">Checkout</span></li>
            <li class="is-pending"><span class="step-dot">3</span><span class="step-label">Pembayaran</span></li>
            <li class="is-pending"><span class="step-dot">4</span><span class="step-label">Selesai</span></li>
        </ul>

        <div class="checkout-grid">
            <div class="checkout-main">

                {{-- Tombol Kembali --}}
                <a href="{{ route('dashboard.keranjang') }}" class="checkout-back">&larr; Kembali ke Keranjang</a>

                {{-- Alamat Pengiriman --}}
                <div class="checkout-section">
                    <h2>
                        <span class="h2-left"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg> Alamat Pengiriman</span>
                        <a href="{{ route('dashboard.alamat') }}" class="h2-action">+ Alamat baru</a>
                    </h2>
                    @forelse($addresses as $addr)
                        <label class="address-card">
                            <input type="radio" name="address" value="{{ $addr->id }}" {{ $addr->id === $defaultAddress?->id ? 'checked' : '' }}>
                            <div>
                                <div class="addr-label">{{ $addr->label ?? $addr->recipient }} — {{ $addr->phone }}</div>
                                <div class="addr-text">{{ $addr->address }}, {{ $addr->city }}, {{ $addr->district }}, {{ $addr->province }} {{ $addr->postal_code }}</div>
                                @if($addr->is_default)<span class="addr-tag">Utama</span>@endif
                            </div>
                        </label>
                    @empty
                        <p class="addr-empty">Belum ada alamat. <a href="{{ route('dashboard.alamat') }}">Tambah alamat</a></p>
                    @endforelse
                </div>

                {{-- Produk Dipilih --}}
                <div class="checkout-section">
                    <h2>
                        <span class="h2-left"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg> Produk</span>
                        <span class="checkout-items-count">{{ $items->sum('qty') }} item</span>
                    </h2>
                    @foreach($items as $item)
                        <div class="checkout-product">
                            @if($item->barang && $item->barang->produk && $item->barang->produk->fotoUtama)
                                <img src="{{ env('BE_URL') . '/storage/' . $item->barang->produk->fotoUtama->foto }}" alt="">
                            @else
                                <div class="img-placeholder" style="width:56px;height:56px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#111;color:rgba(255,255,255,0.12);font-size:10px;font-weight:700;text-transform:uppercase;flex-shrink:0;">{{ $item->barang->produk->brand->nama_brand ?? 'N/A' }}</div>
                            @endif
                            <div class="cp-info">
                                <div class="cp-name">{{ $item->barang->produk->nama_produk }}</div>
                                <div class="cp-variant">{{ $item->barang->nama_barang ?? '' }}</div>
                            </div>
                            <div class="cp-right">
                                <span class="cp-price">
                                    @if ($item->hasDiscount)
                                        <span style="text-decoration:line-through;color:var(--ink-muted);font-size:12px;font-weight:400;margin-right:4px;">Rp{{ number_format($item->normalPrice, 0, ',', '.') }}</span>
                                        Rp{{ number_format($item->finalPrice, 0, ',', '.') }}
                                        <span style="display:inline-block;font-size:10px;font-weight:600;color:#fff;background:var(--accent);padding:1px 6px;border-radius:999px;margin-left:4px;vertical-align:middle;">-{{ $item->discountPercent }}%</span>
                                    @else
                                        Rp{{ number_format($item->finalPrice, 0, ',', '.') }}
                                    @endif
                                </span>
                                <span class="cp-qty">{{ $item->qty }}x</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Opsi Pengiriman --}}
                <div class="checkout-section">
                    <h2><span class="h2-left"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg> Opsi Pengiriman</span></h2>
                    <label class="option-card">
                        <input type="radio" name="shipping" value="reguler" data-price="10000" checked onchange="updateSummary()">
                        <span class="opt-icon"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/></svg></span>
                        <span class="opt-body">
                            <span class="opt-name">Reguler (3–5 hari)</span>
                            <span class="opt-desc">Estimasi tiba dalam 3–5 hari kerja</span>
                        </span>
                        <span class="opt-price">Rp10.000</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="shipping" value="express" data-price="25000" onchange="updateSummary()">
                        <span class="opt-icon"><svg viewBox="0 0 24 24"><path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg></span>
                        <span class="opt-body">
                            <span class="opt-name">Express (1–2 hari)<span class="opt-badge">Cepat</span></span>
                            <span class="opt-desc">Estimasi tiba dalam 1–2 hari kerja</span>
                        </span>
                        <span class="opt-price">Rp25.000</span>
                    </label>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="checkout-section">
                    <h2><span class="h2-left"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Metode Pembayaran</span></h2>
                    <label class="option-card">
                        <input type="radio" name="payment" value="transfer" checked>
                        <span class="opt-icon"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></span>
                        <span class="opt-body">
                            <span class="opt-name">Transfer Bank (BCA)</span>
                            <span class="opt-desc">Pembayaran via transfer ke rekening BCA a.n. CHRISBALE</span>
                        </span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="payment" value="cod">
                        <span class="opt-icon"><svg viewBox="0 0 24 24"><path d="M20 12V8H4v4M4 12v6a2 2 0 002 2h12a2 2 0 002-2v-6M4 12h16"/></svg></span>
                        <span class="opt-body">
                            <span class="opt-name">Bayar di Tempat (COD)</span>
                            <span class="opt-desc">Bayar tunai saat pesanan tiba</span>
                        </span>
                    </label>
                </div>

                {{-- Catatan --}}
                <div class="checkout-section checkout-notes">
                    <h2><span class="h2-left"><svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/></svg> Catatan untuk Penjual</span></h2>
                    <textarea name="catatan" maxlength="200" placeholder="Contoh: tolong dibungkus rapi, ukuran sepatu pas ya (opsional)" oninput="document.getElementById('charCount').textContent = this.value.length"></textarea>
                    <div class="char-count"><span id="charCount">0</span>/200</div>
                </div>
            </div>

            {{-- Ringkasan Pesanan --}}
            <aside class="order-summary">
                <h2>Ringkasan Pesanan</h2>

                <div class="promo-row">
                    <input type="text" id="promoInput" placeholder="Kode promo">
                    <button type="button" onclick="applyPromo()">Pakai</button>
                </div>
                <div class="promo-msg" id="promoMsg"></div>

                <div class="summary-line">
                    <span>Subtotal ({{ $items->sum('qty') }} produk)</span>
                    <span id="lineSubtotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-line">
                    <span>Biaya Pengiriman</span>
                    <span id="lineShipping">Rp10.000</span>
                </div>
                <div class="summary-line discount" id="lineDiscountRow" style="display:none;">
                    <span>Diskon</span>
                    <span id="lineDiscount">-Rp0</span>
                </div>
                <div class="summary-line total">
                    <span>Total</span>
                    <span id="checkoutTotal">Rp{{ number_format($subtotal + 10000, 0, ',', '.') }}</span>
                </div>

                <button class="btn-buy" onclick="alert('Fitur checkout akan segera tersedia.')">Beli Sekarang</button>

                <div class="trust-badges">
                    <div class="trust-badge"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Pembayaran aman &amp; terenkripsi</div>
                    <div class="trust-badge"><svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg> Garansi 100% produk original</div>
                    <div class="trust-badge"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Estimasi pengiriman transparan</div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const SUBTOTAL = {{ (float) $subtotal }};
let discount = 0;

function formatRupiah(n) {
    return 'Rp' + Math.round(n).toLocaleString('id-ID');
}

function updateSummary() {
    const shippingInput = document.querySelector('input[name="shipping"]:checked');
    const shippingCost = shippingInput ? parseFloat(shippingInput.dataset.price) : 0;

    document.getElementById('lineShipping').textContent = formatRupiah(shippingCost);
    document.getElementById('lineSubtotal').textContent = formatRupiah(SUBTOTAL);

    const total = SUBTOTAL + shippingCost - discount;
    document.getElementById('checkoutTotal').textContent = formatRupiah(Math.max(total, 0));
}

function applyPromo() {
    const code = document.getElementById('promoInput').value.trim().toUpperCase();
    const msg = document.getElementById('promoMsg');
    const discountRow = document.getElementById('lineDiscountRow');

    if (!code) {
        msg.textContent = 'Masukkan kode promo terlebih dahulu.';
        msg.className = 'promo-msg show err';
        return;
    }

    if (code === 'CHRISBALE10') {
        discount = SUBTOTAL * 0.1;
        document.getElementById('lineDiscount').textContent = '-' + formatRupiah(discount);
        discountRow.style.display = 'flex';
        msg.textContent = 'Kode promo berhasil digunakan, diskon 10%.';
        msg.className = 'promo-msg show ok';
    } else {
        discount = 0;
        discountRow.style.display = 'none';
        msg.textContent = 'Kode promo tidak valid atau sudah kedaluwarsa.';
        msg.className = 'promo-msg show err';
    }
    updateSummary();
}

updateSummary();
</script>
@endpush