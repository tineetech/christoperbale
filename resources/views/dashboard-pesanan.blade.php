@extends('layouts.dashboard')

@section('title', 'Pesanan Saya — CHRISBALE')

@push('styles')
    <style>
        .order-card {
            cursor: pointer;
        }
        .order-card-body {
            max-height: none !important;
            overflow: visible !important;
        }

        .ord-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 14px;
            margin-bottom: 20px;
        }

        .ord-toolbar .ot-input {
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            padding: 9px 12px;
            font-size: 12.5px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: var(--bg);
            outline: none;
            transition: border-color 0.18s;
        }

        .ord-toolbar .ot-input:focus {
            border-color: var(--accent);
        }

        .ord-toolbar .ot-search {
            flex: 1 1 220px;
            min-width: 180px;
        }

        .ord-toolbar .ot-btn {
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            cursor: pointer;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
            transition: background 0.18s;
            font-family: 'Inter', sans-serif;
        }

        .ord-toolbar .ot-btn:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
        }

        .ord-toolbar .ot-reset {
            background: none;
            color: var(--ink-muted);
            border-color: var(--line);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .ord-toolbar .ot-reset:hover {
            border-color: var(--ink);
            color: var(--ink);
        }
    </style>
@endpush

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

                        <form class="ord-toolbar" method="GET" action="{{ route('dashboard.pesanan') }}">
                            <input class="ot-input ot-search" type="text" name="q" placeholder="Cari kode pesanan / nama produk..."
                                value="{{ $filters['q'] ?? '' }}">

                            <select class="ot-input" name="status">
                                <option value="">Semua Status</option>
                                <option value="proses" {{ ($filters['status'] ?? '') === 'proses' ? 'selected' : '' }}>Diproses</option>
                                <option value="packing" {{ ($filters['status'] ?? '') === 'packing' ? 'selected' : '' }}>Packing</option>
                                <option value="dikirim" {{ ($filters['status'] ?? '') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ ($filters['status'] ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>

                            <input class="ot-input" type="date" name="from" value="{{ $filters['from'] ?? '' }}">
                            <span style="font-size:12px;color:var(--ink-muted);">s/d</span>
                            <input class="ot-input" type="date" name="to" value="{{ $filters['to'] ?? '' }}">

                            <button type="submit" class="ot-btn">Terapkan</button>
                            @if (!empty(array_filter($filters)))
                                <a href="{{ route('dashboard.pesanan') }}" class="ot-btn ot-reset">Reset</a>
                            @endif
                        </form>

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
                            <div class="order-card" data-status="{{ $filterStatus }}" onclick="window.location.href='{{ route('dashboard.pesanan.detail', $order->id) }}'">
                                <div class="order-card-header">
                                    <div class="ofc-header-left">
                                        <span class="ofc-id">{{ $orderId }}</span>
                                        <span class="dot-status {{ $statusClass }}">{{ $statusText }}</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:12px;">
                                        <span class="ofc-date">{{ $orderDate }}</span>
                                    </div>
                                </div>
                                <div class="order-card-body" style="max-height:none;overflow:visible;">
                                    <div class="order-track-bar">
                                        @php
                                            $steps = ['Dikonfirmasi', 'Dikemas', 'Dikirim', 'Tiba'];
                                            $statusMap = ['proses' => 0, 'packing' => 1, 'dikirim' => 2, 'selesai' => 3];   
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
                                            <div><span class="ofc-label">Pembayaran</span><span>Midtrans {{ $order->pembayaran->payment_method ? '— ' . $order->pembayaran->payment_method : '' }} · {{ ucfirst($order->pembayaran->status ?? 'Lunas') }}</span></div>
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
    // Order cards are now fully clickable — navigate to detail directly.
</script>
@endpush
