@extends('layouts.dashboard')

@section('title', 'Pesanan Saya — CHRISBALE')

@section('dashboard-content')
                    <div class="dash-panel" id="panel-orders">
                        <div class="dash-section-head" style="margin-bottom:24px;">
                            <h2 class="dash-section-title">Riwayat Pesanan</h2>
                            <div class="order-filter-tabs">
                                <button class="ofilter active" onclick="filterOrders(this,'all')">Semua</button>
                                <button class="ofilter" onclick="filterOrders(this,'process')">Diproses</button>
                                <button class="ofilter" onclick="filterOrders(this,'shipping')">Dikirim</button>
                                <button class="ofilter" onclick="filterOrders(this,'done')">Selesai</button>
                            </div>
                        </div>

                        @if ($orders->isEmpty())
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;min-height:200px;padding:48px 20px;color:var(--ink-muted);background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--line);">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.4;">
                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <path d="M16 10a4 4 0 01-8 0" />
                            </svg>
                            <p style="font-size:14px;">Belum ada pesanan.</p>
                        </div>
                        @else
                        <div class="orders-full-list" id="ordersFullList">
                            @foreach ($orders as $order)
                            @php
                                $orderId = $order->nomor_pesanan ?? $order->kode_penjualan ?? '#CB-ORD-' . $order->id;
                                $orderDate = $order->tanggal ? \Carbon\Carbon::parse($order->tanggal)->format('d M Y') : $order->created_at->format('d M Y');
                                $total = 'Rp' . number_format($order->total_harga, 0, ',', '.');
                                $statusText = ucfirst($order->status);

                                $filterStatus = match ($order->status) {
                                    'selesai' => 'done',
                                    'dikirim' => 'shipping',
                                    default => 'process',
                                };

                                $statusClass = match ($order->status) {
                                    'selesai' => 'dot-status--done',
                                    'dikirim' => 'dot-status--shipping',
                                    default => 'dot-status--process',
                                };
                            @endphp
                            <div class="order-card" data-status="{{ $filterStatus }}">
                                <div class="order-card-header" onclick="toggleOrder(this)">
                                    <div class="ofc-header-left">
                                        <span class="ofc-id">{{ $orderId }}</span>
                                        <span class="dot-status {{ $statusClass }}">{{ $statusText }}</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:12px;">
                                        <span class="ofc-date">{{ $orderDate }}</span>
                                        <svg class="order-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="order-card-body">
                                    <div class="order-track-bar">
                                        @php
                                            $steps = ['Dikonfirmasi', 'Dikemas', 'Dikirim', 'Tiba'];
                                            $statusMap = ['confirmed' => 0, 'packed' => 0, 'dikirim' => 1, 'shipping' => 1, 'selesai' => 3];
                                            $stepIndex = $statusMap[$order->status] ?? -1;
                                        @endphp
                                        @foreach ($steps as $i => $step)
                                        <div class="otb-step {{ $i < $stepIndex ? 'done' : ($i == $stepIndex ? 'active' : '') }}">
                                            <div class="otb-dot"></div>
                                            <span>{{ $step }}</span>
                                        </div>
                                        @if (!$loop->last)
                                        <div class="otb-line {{ $i < $stepIndex ? 'done' : '' }}"></div>
                                        @endif
                                        @endforeach
                                    </div>
                                    <div class="ofc-products">
                                        @foreach ($order->detail as $item)
                                        <div class="ofc-prod-row" @if (!$loop->first) style="border-top:1px solid var(--line-soft);padding-top:12px;" @endif>
                                            <div style="width:60px;height:60px;border-radius:var(--radius-sm);overflow:hidden;flex-shrink:0;background:var(--bg);display:flex;align-items:center;justify-content:center;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ink-muted)" stroke-width="1.2" style="opacity:0.4;">
                                                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                                    <line x1="3" y1="6" x2="21" y2="6" />
                                                    <path d="M16 10a4 4 0 01-8 0" />
                                                </svg>
                                            </div>
                                            <div class="ofc-prod-detail">
                                                <strong>{{ $item->barang->nama_barang ?? 'Produk' }}</strong>
                                                <span>{{ $item->barang->sku ? 'SKU: ' . $item->barang->sku : '' }} · Qty: {{ $item->qty }}</span>
                                                <span style="color:var(--accent);font-weight:600;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="ofc-footer">
                                        <div class="ofc-footer-info">
                                            @if ($order->pembayaran)
                                            <div><span class="ofc-label">Pembayaran</span><span>{{ $order->pembayaran->payment_provider ?? '' }} {{ $order->pembayaran->payment_method ? '— ' . $order->pembayaran->payment_method : '' }} · {{ ucfirst($order->pembayaran->payment_status ?? 'Lunas') }}</span></div>
                                            @endif
                                            <div><span class="ofc-label">Alamat</span><span>{{ $order->address->address ?? '-' }}{{ $order->address ? ', ' . $order->address->city : '' }}</span></div>
                                            <div class="ofc-total-row"><span class="ofc-label">Total Bayar</span><strong>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</strong></div>
                                        </div>
                                        <div class="ofc-footer-actions">
                                            <a href="{{ route('dashboard.pesanan.detail', $order->id) }}" class="ofc-btn ofc-btn--outline">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div><!-- /panel-orders -->
@endsection

@push('scripts')
<script>
    function toggleOrder(el) {
        var card = el.closest('.order-card');
        card.classList.toggle('open');
    }
</script>
@endpush
