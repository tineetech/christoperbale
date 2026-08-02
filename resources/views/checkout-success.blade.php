@extends('layouts.app')

@section('title', 'Pesanan Berhasil — CHRISBALE')

@push('styles')
    <style>
        .checkout-wrap * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .checkout-wrap {
            width: 100%;
            max-width: 860px;
            margin: 20px auto;
            padding: 0 20px 80px;
            overflow-x: hidden;
        }

        .checkout-head {
            margin: 0 0 20px;
            padding-top: 16px;
        }

        .checkout-head h1 {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 4px;
        }

        .checkout-head .subtitle {
            font-size: 13px;
            color: var(--ink-muted);
            margin: 0;
        }

        .checkout-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 32px;
            list-style: none;
            padding: 0;
        }

        .checkout-steps li {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            position: relative;
        }

        .checkout-steps li:not(:last-child)::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
            margin: 0 12px;
        }

        .checkout-steps .step-dot {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .checkout-steps .step-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
        }

        .success-hero {
            background: linear-gradient(135deg, rgba(16, 185, 129, .12), rgba(16, 185, 129, .04));
            border: 1px solid rgba(16, 185, 129, .25);
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        .success-hero .check-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #10b981;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .success-hero .check-icon svg {
            width: 30px;
            height: 30px;
            stroke: #fff;
        }

        .success-hero h2 {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .success-hero p {
            font-size: 13px;
            color: var(--ink-muted);
            margin: 0;
        }

        .success-hero .order-code {
            display: inline-block;
            margin-top: 12px;
            padding: 8px 18px;
            background: rgba(16, 185, 129, .12);
            color: #0d9f6e;
            font-weight: 700;
            font-size: 14px;
            border-radius: 999px;
        }

        .success-hero .success-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-primary-green,
        .btn-outline-green {
            padding: 13px 26px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
        }

        .btn-primary-green {
            background: #10b981;
            color: #fff;
        }

        .btn-primary-green:hover {
            background: #0ea371;
        }

        .btn-outline-green {
            border-color: var(--line);
            color: var(--ink);
            background: transparent;
        }

        .btn-outline-green:hover {
            border-color: #10b981;
            color: #10b981;
        }

        .pay-card {
            background: var(--bg-card, #fff);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 16px;
        }

        .pay-card h2 {
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pay-card h2 svg {
            width: 17px;
            height: 17px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .pay-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 8px 0;
            border-bottom: 1px dashed var(--line);
            font-size: 13px;
        }

        .pay-row:last-child {
            border-bottom: none;
        }

        .pay-row .pay-label {
            color: var(--ink-muted);
        }

        .pay-row .pay-value {
            font-weight: 600;
            text-align: right;
        }

        .pay-product {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px dashed var(--line);
        }

        .pay-product:last-child {
            border-bottom: none;
        }

        .pay-product .pp-img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #111;
            object-fit: cover;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.12);
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .pay-product .pp-name {
            font-size: 13px;
            font-weight: 600;
            flex: 1;
            min-width: 0;
        }

        .pay-product .pp-right {
            text-align: right;
            flex-shrink: 0;
        }

        .pay-product .pp-price {
            font-size: 13px;
            font-weight: 700;
        }

        .pay-product .pp-qty {
            font-size: 12px;
            color: var(--ink-muted);
        }
    </style>
@endpush

@section('content')
    <div class="wrap">
        <div class="checkout-wrap">
            <div class="checkout-head">
                <h1>Pesanan Selesai</h1>
                <p class="subtitle">Terima kasih, pesanan Anda berhasil dibuat.</p>
            </div>

            <ul class="checkout-steps">
                <li><span class="step-dot">1</span><span class="step-label">Keranjang</span></li>
                <li><span class="step-dot">2</span><span class="step-label">Checkout</span></li>
                <li><span class="step-dot">3</span><span class="step-label">Pembayaran</span></li>
                <li><span class="step-dot">4</span><span class="step-label">Selesai</span></li>
            </ul>

            <div class="success-hero">
                <div class="check-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                </div>
                <h2>Pembayaran Berhasil!</h2>
                <p>Pesanan Anda sudah kami terima dan sedang diproses.</p>
                <span class="order-code">No. Pesanan: {{ $penjualan->kode_penjualan }}</span>
                <div class="success-actions">
                    <a href="{{ route('dashboard.pesanan.detail', $penjualan->id) }}" class="btn-primary-green">
                        Lihat Detail Pesanan
                    </a>
                    <a href="{{ url('/') }}" class="btn-outline-green">Kembali Belanja</a>                </div>
            </div>

            <div class="pay-card">
                <h2><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" /><line x1="3" y1="6" x2="21" y2="6" /></svg>
                    Produk Dipesan</h2>
                @foreach ($penjualan->detail as $item)
                    <div class="pay-product">
                        <div class="pp-img">
                            @if ($item->barang && $item->barang->produk && $item->barang->produk->fotoUtama)
                                <img src="{{ env('BE_URL') . '/storage/' . $item->barang->produk->fotoUtama->foto }}"
                                    alt="" style="width:100%;height:100%;border-radius:8px;object-fit:cover;">
                            @else
                                {{ $item->barang->produk->brand->nama_brand ?? 'N/A' }}
                            @endif
                        </div>
                        <div class="pp-name">{{ $item->barang->produk->nama_produk ?? 'Produk' }}</div>
                        <div class="pp-right">
                            <div class="pp-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
                            <div class="pp-qty">{{ $item->qty }}x</div>
                        </div>
                    </div>
                @endforeach

                <div class="pay-row" style="margin-top:8px;">
                    <span class="pay-label">Subtotal ({{ $penjualan->detail->sum('qty') }} produk)</span>
                    <span class="pay-value">Rp{{ number_format($penjualan->subtotal_harga, 0, ',', '.') }}</span>
                </div>
                <div class="pay-row">
                    <span class="pay-label">Biaya Pengiriman
                        ({{ $penjualan->shipment->courier ?? '' }} {{ $penjualan->shipment->service ?? '' }})</span>
                    <span class="pay-value">Rp{{ number_format($penjualan->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if ($penjualan->harga_discount > 0)
                    <div class="pay-row">
                        <span class="pay-label">Diskon</span>
                        <span class="pay-value">-Rp{{ number_format($penjualan->harga_discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="pay-row" style="border-bottom:none;">
                    <span class="pay-label">Metode Pembayaran</span>
                    <span class="pay-value">Midtrans {{ $pembayaran->payment_type ?? '' }}</span>
                </div>
                <div class="pay-row" style="border-bottom:none;">
                    <span class="pay-label">Status</span>
                    <span class="pay-value">Lunas</span>
                </div>
            </div>

            @if ($penjualan->address)
                <div class="pay-card">
                    <h2><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" /><circle cx="12" cy="10" r="3" /></svg>
                        Alamat Pengiriman</h2>
                    <div class="pay-row">
                        <span class="pay-label">Penerima</span>
                        <span class="pay-value">{{ $penjualan->address->recipient_name }} — {{ $penjualan->address->phone }}</span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">Alamat</span>
                        <span class="pay-value">{{ $penjualan->address->address }}, {{ $penjualan->address->district }}, {{ $penjualan->address->city }}, {{ $penjualan->address->province }} {{ $penjualan->address->postal_code }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
