@extends('layouts.dashboard')

@section('title', 'Detail Pesanan — CHRISBALE')

@section('dashboard-content')
@php
    $orderId = $order->nomor_pesanan ?? $order->kode_penjualan ?? '#CB-ORD-' . $order->id;
    $orderDate = $order->tanggal ? \Carbon\Carbon::parse($order->tanggal)->format('d M Y') : $order->created_at->format('d M Y');
    $statusText = ucfirst($order->status);

    $statusClass = match ($order->status) {
        'selesai' => 'dot-status--done',
        'dikirim' => 'dot-status--shipping',
        default => 'dot-status--process',
    };

    $steps = ['Dikonfirmasi', 'Dikemas', 'Dikirim', 'Tiba'];
    $statusMap = ['confirmed' => 0, 'packed' => 0, 'dikirim' => 1, 'shipping' => 1, 'selesai' => 3];
    $stepIndex = $statusMap[$order->status] ?? -1;
@endphp

<div class="dash-panel">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
        <a href="{{ route('dashboard.pesanan') }}" style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--ink-muted);text-decoration:none;border:1px solid var(--line);border-radius:var(--radius-sm);padding:8px 16px;transition:all 0.18s;" onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'" onmouseout="this.style.borderColor='';this.style.color=''">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2 style="font-size:18px;font-weight:600;margin:0;">Detail Pesanan</h2>
    </div>

    <!-- Header Card -->
    <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:24px;margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:14px;">
                <span style="font-size:18px;font-weight:700;font-family:monospace;letter-spacing:0.04em;">{{ $orderId }}</span>
                <span class="dot-status {{ $statusClass }}">{{ $statusText }}</span>
            </div>
            <span style="font-size:13px;color:var(--ink-muted);">{{ $orderDate }}</span>
        </div>
    </div>

    <!-- Tracking -->
    <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:24px 32px;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:0;">
            @foreach ($steps as $i => $step)
            <div class="otb-step {{ $i < $stepIndex ? 'done' : ($i == $stepIndex ? 'active' : '') }}" style="flex:1;">
                <div class="otb-dot" style="width:18px;height:18px;"></div>
                <span style="font-size:12px;margin-top:8px;">{{ $step }}</span>
            </div>
            @if (!$loop->last)
            <div class="otb-line {{ $i < $stepIndex ? 'done' : '' }}" style="margin-top:-20px;"></div>
            @endif
            @endforeach
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

        <!-- Produk -->
        <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
            <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;padding-bottom:12px;border-bottom:1px solid var(--line-soft);">Produk</h3>
            @foreach ($order->detail as $item)
            <div style="display:flex;gap:14px;padding:10px 0;{{ $loop->first ? '' : 'border-top:1px solid var(--line-soft);' }}">
                <div style="width:56px;height:56px;border-radius:var(--radius-sm);overflow:hidden;flex-shrink:0;background:var(--bg);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ink-muted)" stroke-width="1.2" style="opacity:0.4;">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                </div>
                <div style="flex:1;display:flex;flex-direction:column;gap:2px;">
                    <strong style="font-size:13px;">{{ $item->barang->nama_barang ?? 'Produk' }}</strong>
                    <span style="font-size:12px;color:var(--ink-muted);">Qty: {{ $item->qty }} @if ($item->barang->sku) · SKU: {{ $item->barang->sku }} @endif</span>
                    <span style="font-size:13px;font-weight:600;color:var(--accent);margin-top:4px;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Informasi Pengiriman -->
        <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
            <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;padding-bottom:12px;border-bottom:1px solid var(--line-soft);">Informasi Pengiriman</h3>
            @if ($order->shipment)
            <div class="info-row">
                <span class="info-label">Kurir</span>
                <span class="info-value">{{ $order->shipment->courier }} {{ $order->shipment->service ? '- ' . $order->shipment->service : '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Resi</span>
                <span class="info-value">{{ $order->shipment->tracking_number ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ongkir</span>
                <span class="info-value">Rp{{ number_format($order->shipment->shipping_cost ?? 0, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value">{{ $order->address->address ?? '-' }}{{ $order->address ? ', ' . $order->address->district . ', ' . $order->address->city . ', ' . $order->address->province . ' ' . $order->address->postal_code : '' }}</span>
            </div>
            @if ($order->address)
            <div class="info-row">
                <span class="info-label">Penerima</span>
                <span class="info-value">{{ $order->address->recipient_name ?? '-' }} · {{ $order->address->phone ?? '-' }}</span>
            </div>
            @endif
        </div>

        <!-- Pembayaran -->
        <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
            <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;padding-bottom:12px;border-bottom:1px solid var(--line-soft);">Pembayaran</h3>
            @if ($order->pembayaran)
            <div class="info-row">
                <span class="info-label">Metode</span>
                <span class="info-value">{{ $order->pembayaran->payment_provider ?? '-' }} {{ $order->pembayaran->payment_method ? '— ' . $order->pembayaran->payment_method : '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">{{ ucfirst($order->pembayaran->payment_status ?? '-') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Waktu Bayar</span>
                <span class="info-value">{{ $order->pembayaran->paid_at ? \Carbon\Carbon::parse($order->pembayaran->paid_at)->format('d M Y H:i') : '-' }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Subtotal</span>
                <span class="info-value">Rp{{ number_format($order->subtotal_harga ?? $order->total_harga, 0, ',', '.') }}</span>
            </div>
            @if ($order->shipping_cost)
            <div class="info-row">
                <span class="info-label">Ongkos Kirim</span>
                <span class="info-value">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @endif
            @if ($order->harga_discount)
            <div class="info-row">
                <span class="info-label">Diskon</span>
                <span class="info-value" style="color:var(--green);">-Rp{{ number_format($order->harga_discount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div style="border-top:1px solid var(--line-soft);padding-top:12px;margin-top:12px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:13px;font-weight:600;">Total</span>
                <strong style="font-size:16px;color:var(--accent);">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Catatan -->
        <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:20px;">
            <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;padding-bottom:12px;border-bottom:1px solid var(--line-soft);">Catatan</h3>
            <p style="font-size:13px;color:var(--ink-soft);margin:0;line-height:1.7;">
                {{ $order->keterangan ?: 'Tidak ada catatan.' }}
            </p>
        </div>

    </div>

    <!-- Tombol Aksi -->
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('dashboard.pesanan') }}" class="ofc-btn ofc-btn--outline">Kembali ke Pesanan</a>
    </div>
</div>

<style>
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 8px 0;
    font-size: 13px;
    gap: 12px;
}
.info-label {
    color: var(--ink-muted);
    flex-shrink: 0;
    font-size: 12px;
}
.info-value {
    text-align: right;
    color: var(--ink);
    font-weight: 500;
}
</style>
@endsection
