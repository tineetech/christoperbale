@extends('layouts.dashboard')

@section('title', 'Keranjang — CHRISBALE')

@push('styles')
    <style>
        .cart-list {
            display: flex;
            flex-direction: column;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 16px;
            transition: border-color .2s;
        }

        .cart-item:hover {
            border-color: var(--accent);
        }

        .cart-item input[type=checkbox] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
            cursor: pointer;
            flex-shrink: 0;
        }

        .cart-item input[type=checkbox]:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        .cart-item-img {
            width: 64px;
            height: 64px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f0f0f0;
        }

        .cart-item-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-img .img-placeholder {
            width: 100%;
            height: 100%;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.12);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .cart-item-info .brand {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 2px;
        }

        .cart-item-info .name {
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-item-info .variant {
            font-size: 12px;
            color: var(--ink-muted);
            margin-top: 2px;
        }

        .cart-item-info .price {
            font-size: 14px;
            font-weight: 600;
            color: var(--accent);
            margin-top: 4px;
        }

        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .cart-item-qty button {
            width: 28px;
            height: 28px;
            border: 1px solid var(--line);
            border-radius: 4px;
            background: transparent;
            font-size: 15px;
            cursor: pointer;
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color .2s;
        }

        .cart-item-qty button:hover {
            border-color: var(--accent);
        }

        .cart-item-qty button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            border-color: var(--line);
        }

        .cart-stock-info {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 3px;
        }

        .cart-stock-warn {
            font-size: 11px;
            font-weight: 600;
            color: var(--red);
            margin-top: 3px;
        }

        .cart-item.is-insufficient {
            border-color: rgba(192, 57, 43, 0.45);
            background: rgba(192, 57, 43, 0.04);
        }

        .cart-item.is-insufficient .name {
            color: var(--red);
        }

        .cart-item-qty span {
            min-width: 24px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
        }

        .cart-checkout-bar {
            position: sticky;
            bottom: 0;
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 16px 20px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .cart-checkout-bar .info {
            font-size: 14px;
            color: var(--ink);
        }

        .cart-checkout-bar .info strong {
            font-size: 16px;
            color: var(--accent);
        }

        .cart-checkout-bar .btn-checkout {
            padding: 12px 32px;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--accent);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: background .2s, opacity .2s;
            text-transform: uppercase;
            font-family: 'Inter', sans-serif;
        }

        .cart-checkout-bar .btn-checkout:hover {
            background: #a0780a;
        }

        .cart-checkout-bar .btn-checkout:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .cart-empty {
            text-align: center;
            padding: 80px 20px;
        }

        .cart-empty svg {
            margin: 0 auto 16px;
            display: block;
        }

        .cart-empty p {
            color: var(--ink-muted);
            font-size: 15px;
            margin: 0;
        }

        .cart-group-sep {
            height: 1px;
            background: var(--line);
            margin: 12px 10px;
        }

        .cart-group-head {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--ink-muted);
            padding: 8px 0 4px 10px;
        }

        .cart-item.grouped {
            border-top: none;
            border-radius: 0;
        }

        .cart-item.grouped:first-child {
            border-top: 1px solid var(--line);
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
        }

        .cart-item.grouped:last-child {
            border-bottom-left-radius: var(--radius);
            border-bottom-right-radius: var(--radius);
        }

        .cart-item.grouped + .cart-item.grouped {
            margin-top: -1px;
        }

        .cart-item-link {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
            min-width: 0;
            text-decoration: none;
            color: inherit;
        }

        .cart-select-all {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 0 10px 4px;
        }

        .cart-select-all input[type=checkbox] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .cart-select-all label {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
            cursor: pointer;
            user-select: none;
        }
    </style>
@endpush

@section('dashboard-content')
    <div class="dash-panel active" id="panel-cart">
        <div class="dash-panel-head" style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <h2>Keranjang</h2>
                <p>Kelola produk yang akan Anda beli.</p>
            </div>
            <a href="/products" style="font-size:12px;color:var(--accent);text-decoration:none;white-space:nowrap;margin-top:4px;">+ Tambah Barang</a>
        </div>

        @if ($items->isEmpty())
            <div class="cart-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--ink-muted)"
                    stroke-width="1.2">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
                </svg>
                <p>Keranjang Anda masih kosong.</p>
            </div>
        @else
            <div class="cart-list" id="cartList">
                <div class="cart-select-all">
                    <input type="checkbox" id="checkAll" onchange="toggleSelectAll(this)">
                    <label for="checkAll">Pilih Semua</label>
                </div>
                @php $prevProdukId = null; @endphp
                @foreach ($items as $item)
                    @php
                        $currProdukId = $item->barang?->produk_id;
                        $isGrouped = $prevProdukId !== null && $currProdukId === $prevProdukId;
                    @endphp
                    @if ($prevProdukId !== null && !$isGrouped)
                        <div class="cart-group-sep"></div>
                    @endif
                    <div class="cart-item{{ $isGrouped ? ' grouped' : '' }}{{ ($item->stok ?? 0) < $item->qty ? ' is-insufficient' : '' }}" data-id="{{ $item->id }}" data-price="{{ $item->finalPrice }}"
                        data-qty="{{ $item->qty }}" data-stok="{{ $item->stok ?? 0 }}">
                        <input type="checkbox" class="cart-check" onchange="updateCheckoutBar()" {{ ($item->stok ?? 0) < $item->qty ? 'disabled' : '' }}>
                        <a href="/product/{{ $item->barang->produk->slug }}?size={{ urlencode($item->varianSize) }}&color={{ urlencode($item->varianColor) }}" class="cart-item-link">
                        <div class="cart-item-img">
                            @if ($item->barang && $item->barang->produk && $item->barang->produk->fotoUtama)
                                <img src="{{ env('BE_URL') . '/storage/' . $item->barang->produk->fotoUtama->foto }}"
                                    alt="">
                            @else
                                <div class="img-placeholder">{{ $item->barang->produk->brand->nama_brand ?? 'N/A' }}</div>
                            @endif
                        </div>
                        <div class="cart-item-info">
                            <div class="brand">{{ $item->barang->produk->brand->nama_brand ?? '' }}</div>
                            <div class="name">{{ $item->barang->produk->nama_produk }}
                        </div>
                            <div class="variant">{{ $item->barang->nama_barang ?? '' }}</div>
                            @if (($item->stok ?? 0) < $item->qty)
                                <div class="cart-stock-warn">Stok tersedia hanya {{ $item->stok }} (dipesan {{ $item->qty }})</div>
                            @else
                                <div class="cart-stock-info">Stok tersedia: {{ $item->stok }}</div>
                            @endif
                            <div class="price">
                                @if ($item->hasDiscount)
                                <span style="color:var(--accent);">Rp{{ number_format($item->finalPrice, 0, ',', '.') }}</span>
                                <span style="text-decoration:line-through;color:var(--ink-muted);font-size:12px;font-weight:400;margin-right:6px;">Rp{{ number_format($item->barang->produk->harga_normal ?? 0, 0, ',', '.') }}</span>
                                    <span style="display:inline-block;font-size:10px;font-weight:600;color:#fff;background:var(--accent);padding:1px 6px;border-radius:999px;margin-left:4px;vertical-align:middle;">-{{ $item->discountPercent }}%</span>
                                @else
                                    Rp{{ number_format($item->barang->harga_1 ?? 0, 0, ',', '.') }}
                                @endif
                            </div>
                        </div>
                        </a>
                        <div class="cart-item-qty">
                            <button class="qty-minus" onclick="changeQty(this, -1)">−</button>
                            <span>{{ $item->qty }}</span>
                            <button class="qty-plus" onclick="changeQty(this, 1)" {{ ($item->stok ?? 0) <= $item->qty ? 'disabled' : '' }}>+</button>
                        </div>
                    </div>
                    @php $prevProdukId = $currProdukId; @endphp
                @endforeach
            </div>

            <div class="cart-checkout-bar" id="checkoutBar">
                <div class="info">
                    <span id="selectedCount">0</span> produk dipilih • Total: <strong id="totalPrice">Rp0</strong>
                </div>
                <button class="btn-checkout" id="btnCheckout" disabled onclick="goCheckout()">Checkout</button>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
var cartList = document.getElementById('cartList');
if (cartList) {
    function updateCheckoutBar(){
        var checks = document.querySelectorAll('.cart-check:checked');
        var total = 0;
        var ids = [];
        var hasInsufficient = false;
        checks.forEach(function(c){
            var item = c.closest('.cart-item');
            var price = parseFloat(item.dataset.price) || 0;
            var qty = parseInt(item.dataset.qty) || 1;
            total += price * qty;
            ids.push(item.dataset.id);
            if ((parseInt(item.dataset.stok) || 0) < qty) hasInsufficient = true;
        });
        document.getElementById('selectedCount').textContent = checks.length;
        document.getElementById('totalPrice').textContent = 'Rp' + total.toLocaleString('id-ID');
        var btn = document.getElementById('btnCheckout');
        btn.disabled = checks.length === 0 || hasInsufficient;
        btn.dataset.ids = ids.join(',');
    }

    function toggleSelectAll(source) {
        document.querySelectorAll('.cart-check').forEach(function(c) {
            if (!c.disabled) c.checked = source.checked;
        });
        updateCheckoutBar();
    }

    function refreshInsufficient(container) {
        var qty = parseInt(container.dataset.qty) || 0;
        var stok = parseInt(container.dataset.stok) || 0;
        var warn = container.querySelector('.cart-stock-warn');
        var check = container.querySelector('.cart-check');
        if (qty > stok) {
            container.classList.add('is-insufficient');
            if (warn) warn.style.display = '';
            if (check) { check.checked = false; check.disabled = true; }
        } else {
            container.classList.remove('is-insufficient');
            if (warn) warn.style.display = 'none';
            if (check) check.disabled = false;
        }
    }

    function changeQty(btn, delta) {
        var container = btn.closest('.cart-item');
        var span = container.querySelector('.cart-item-qty span');
        var qty = parseInt(span.textContent) + delta;
        var stok = parseInt(container.dataset.stok) || 0;

        if (qty < 1) {
            Swal.fire({
                title: 'Hapus barang?',
                text: 'Barang akan dihapus dari keranjang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#B8860B',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(res) {
                if (res.isConfirmed) {
                    fetch('/dashboard/keranjang/' + container.dataset.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } })
                        .then(function(r) { return r.json(); })
                        .then(function() {
                            container.remove();
                            updateCheckoutBar();
                        });
                }
            });
            return;
        }

        if (qty > stok) {
            Swal.fire({ icon: 'warning', title: 'Stok Tidak Cukup', text: stok <= 0 ? 'Stok produk ini sedang habis.' : 'Stok tersedia: ' + stok + '.' });
            return;
        }

        if (qty > 99) qty = 99;
        span.textContent = qty;
        container.dataset.qty = qty;

        var plusBtn = container.querySelector('.qty-plus');
        if (plusBtn) plusBtn.disabled = qty >= stok || stok <= 0;
        refreshInsufficient(container);
        updateCheckoutBar();

        fetch('/dashboard/keranjang/' + container.dataset.id, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({ qty: qty })
        }).then(function(r) {
            if (!r.ok) return r.json().then(function(d) { throw new Error(d.message || 'Gagal'); });
            return r.json();
        }).catch(function(e) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
        });
    }

    document.querySelectorAll('.cart-item').forEach(function(container) {
        refreshInsufficient(container);
    });

    function goCheckout() {
        var btn = document.getElementById('btnCheckout');
        if (btn.disabled) return;

        var checks = document.querySelectorAll('.cart-check:checked');
        var insufficient = null;
        checks.forEach(function(c) {
            var item = c.closest('.cart-item');
            var qty = parseInt(item.dataset.qty) || 1;
            var stok = parseInt(item.dataset.stok) || 0;
            if (qty > stok) insufficient = item;
        });

        if (insufficient) {
            var check = insufficient.querySelector('.cart-check');
            if (check) { check.checked = false; check.disabled = true; }
            updateCheckoutBar();
            Swal.fire({
                icon: 'warning',
                title: 'Stok Tidak Cukup',
                text: 'Ada produk yang stoknya tidak mencukupi sehingga tidak dapat diproses. Kurangi jumlahnya atau pilih produk lain.'
            });
            return;
        }

        window.location.href = '/checkout?items=' + btn.dataset.ids;
    }

    updateCheckoutBar();
}
</script>
@endpush
