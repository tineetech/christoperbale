@extends('layouts.dashboard')

@section('title', 'Dashboard — CHRISBALE')

@section('dashboard-content')
                    <div class="dash-panel" id="panel-overview">

                        <!-- Stats Row -->
                        <div class="dash-stats-row">
                            <div class="dash-stat-card">
                                <div class="dash-stat-icon dash-stat-icon--gold">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                        <line x1="3" y1="6" x2="21" y2="6" />
                                        <path d="M16 10a4 4 0 01-8 0" />
                                    </svg>
                                </div>
                                <div class="dash-stat-info">
                                    <span class="dash-stat-num">{{ $totalOrders }}</span>
                                    <span class="dash-stat-label">Total Pesanan</span>
                                </div>
                            </div>
                            <div class="dash-stat-card">
                                <div class="dash-stat-icon dash-stat-icon--green">
                                    <svg viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div class="dash-stat-info">
                                    <span class="dash-stat-num">{{ $completed }}</span>
                                    <span class="dash-stat-label">Selesai</span>
                                </div>
                            </div>
                            <div class="dash-stat-card">
                                <div class="dash-stat-icon dash-stat-icon--blue">
                                    <svg viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </div>
                                <div class="dash-stat-info">
                                    <span class="dash-stat-num">{{ $processing }}</span>
                                    <span class="dash-stat-label">Diproses</span>
                                </div>
                            </div>
                            <div class="dash-stat-card">
                                <div class="dash-stat-icon dash-stat-icon--red">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                                    </svg>
                                </div>
                                <div class="dash-stat-info">
                                    <span class="dash-stat-num">{{ $recentOrders->count() }}</span>
                                    <span class="dash-stat-label">Pesanan Terbaru</span>
                                </div>
                            </div>
                        </div>

                        @php
                            $activeOrder = $recentOrders->firstWhere('status', '!=', 'selesai');
                        @endphp

                        @if ($activeOrder)
                        <div class="dash-alert">
                            <div class="dash-alert-dot"></div>
                            <div class="dash-alert-content">
                                <strong>Pesanan #{{ $activeOrder->kode_penjualan ?? $activeOrder->kode_penjualan }}</strong> sedang dalam proses
                                @if ($activeOrder->status === 'dikirim')
                                — estimasi tiba sesuai jadwal pengiriman
                                @endif
                            </div>
                            <a href="{{ route('dashboard.pesanan') }}" class="dash-alert-link">
                                Lacak
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        </div>
                        @endif

                        <!-- Recent Orders -->
                        <div class="dash-section-head">
                            <h2 class="dash-section-title">Pesanan Terbaru</h2>
                            <a class="dash-section-link" href="{{ route('dashboard.pesanan') }}">Lihat Semua</a>
                        </div>

                        @if ($recentOrders->isEmpty())
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;min-height:200px;padding:48px 20px;color:var(--ink-muted);background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--line);">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.4;">
                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <path d="M16 10a4 4 0 01-8 0" />
                            </svg>
                            <p style="font-size:14px;">Belum ada pesanan.</p>
                        </div>
                        @else
                        <div class="dash-orders-table">
                            <div class="dot-table-header">
                                <span>Produk</span>
                                <span>ID Pesanan</span>
                                <span>Tanggal</span>
                                <span>Total</span>
                                <span>Status</span>
                                <span></span>
                            </div>
                            @foreach ($recentOrders as $order)
                            @php
                                $firstDetail = $order->detail->first();
                                $productName = $firstDetail->barang->nama_barang ?? 'Produk';
                                $itemCount = $order->detail->count();
                                $orderId = $order->kode_penjualan ?? '#CB-ORD-' . $order->id;
                                $orderDate = $order->tanggal ? \Carbon\Carbon::parse($order->tanggal)->format('d M Y') : $order->created_at->format('d M Y');
                                $total = 'Rp' . number_format($order->total_harga, 0, ',', '.');
                                $statusText = ucfirst($order->status);

                                $statusClass = match ($order->status) {
                                    'selesai' => 'dot-status--done',
                                    'dikirim' => 'dot-status--shipping',
                                    'diproses', 'dikemas' => 'dot-status--process',
                                    default => 'dot-status--process',
                                };
                            @endphp
                            <div class="dot-row">
                                <div class="dot-product">
                                    <div class="dot-prod-img" style="display:flex;align-items:center;justify-content:center;background:var(--bg);">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ink-muted)" stroke-width="1.2" style="opacity:0.4;">
                                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                            <line x1="3" y1="6" x2="21" y2="6" />
                                            <path d="M16 10a4 4 0 01-8 0" />
                                        </svg>
                                    </div>
                                    <div class="dot-prod-info">
                                        <span class="dot-prod-name">{{ $productName }}</span>
                                        @if ($itemCount > 1)
                                        <span class="dot-prod-meta">+{{ $itemCount - 1 }} produk lainnya</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="dot-id">{{ $orderId }}</span>
                                <span class="dot-date">{{ $orderDate }}</span>
                                <span class="dot-total">{{ $total }}</span>
                                <span class="dot-status {{ $statusClass }}">{{ $statusText }}</span>
                                <button class="dot-action-btn">Detail</button>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Recommended + Voucher Row -->
                        <div class="dash-two-col">
                            <!-- Rekomendasi -->
                            <div>
                                <div class="dash-section-head">
                                    <h2 class="dash-section-title">Rekomendasi Untukmu</h2>
                                </div>
                                <div class="dash-reco-list">
                                    @forelse ($recommendedProducts as $product)
                                        <div class="dash-reco-item">
                                            <div class="dash-reco-img" @if (!$product->fotoUtama) style="display:flex;align-items:center;justify-content:center;background:var(--bg);" @endif>
                                                @if ($product->fotoUtama)
                                                    <img src="{{ env('BE_URL') . '/storage/' . $product->fotoUtama->foto }}"
                                                        alt="{{ $product->nama_produk }}" loading="lazy">
                                                @else
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ink-muted)" stroke-width="1.2" style="opacity:0.4;">
                                                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                                        <line x1="3" y1="6" x2="21" y2="6" />
                                                        <path d="M16 10a4 4 0 01-8 0" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="dash-reco-info">
                                                <span class="dash-reco-name">{{ $product->nama_produk }}</span>
                                                @php
                                                    $barang = $product->barang->first();
                                                    $harga = $barang->harga_1 ?? $product->harga_normal ?? 0;
                                                    $diskon = $product->harga_diskon;
                                                @endphp
                                                <span class="dash-reco-price">
                                                    Rp{{ number_format($harga, 0, ',', '.') }}
                                                    @if ($diskon && $diskon < $harga)
                                                        <del style="font-size:11px;color:var(--ink-muted);font-weight:400;">Rp{{ number_format($harga, 0, ',', '.') }}</del>
                                                    @endif
                                                </span>
                                                @if ($product->is_popular)
                                                    <span class="badge-tag badge-hot"
                                                        style="position:static;display:inline-block;margin-top:4px;font-size:9px;">Populer</span>
                                                @elseif ($product->is_newproduct)
                                                    <span class="badge-tag badge-new"
                                                        style="position:static;display:inline-block;margin-top:4px;font-size:9px;">Baru</span>
                                                @endif
                                            </div>
                                            <button class="dash-reco-btn"
                                                onclick="addToCartFromDashboard({{ $product->id }}, event)">+ Keranjang</button>
                                        </div>
                                    @empty
                                        <p style="padding:16px;text-align:center;color:var(--ink-muted);">Belum ada produk rekomendasi.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Voucher Aktif -->
                            <div>
                                <div class="dash-section-head">
                                    <h2 class="dash-section-title">Voucher Aktif</h2>
                                    <a class="dash-section-link" href="{{ route('dashboard.voucher') }}">Semua</a>
                                </div>
                                <div class="dash-vouchers">
                                    @forelse ($activeVouchers as $voucher)
                                        @php
                                            $isShipping = $voucher->type === 'shipping';
                                            $amountLabel = $isShipping
                                                ? 'GRATIS ONGKIR'
                                                : ($voucher->type === 'percent'
                                                    ? (int) $voucher->value . '% OFF'
                                                    : 'Rp' . number_format($voucher->value, 0, ',', '.') . ' OFF');
                                            $leftBg = $isShipping ? 'background:linear-gradient(135deg,#1A3A2A,#2E7D32);' : '';
                                        @endphp
                                        <div class="dash-voucher-card">
                                            <div class="dash-voucher-left" style="{{ $leftBg }}">
                                                <div class="dash-voucher-amount">{{ $amountLabel }}</div>
                                                <div class="dash-voucher-code">{{ $voucher->code }}</div>
                                            </div>
                                            <div class="dash-voucher-divider"></div>
                                            <div class="dash-voucher-right">
                                                <p>{{ $voucher->name }}</p>
                                                <span>Berlaku hingga {{ $voucher->end_at ? $voucher->end_at->format('d M Y') : '-' }}</span>
                                                <button class="dash-copy-btn" onclick="copyCode('{{ $voucher->code }}',this)">Salin
                                                    Kode</button>
                                            </div>
                                        </div>
                                    @empty
                                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;padding:28px 16px;text-align:center;">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--ink-muted)" stroke-width="1.3" style="opacity:.45;">
                                                <path d="M3 8h18v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                                <path d="M7 8V6a5 5 0 015-5v0a5 5 0 015 5v2" />
                                                <circle cx="9" cy="12" r="1.2" fill="var(--ink-muted)" stroke="none" />
                                                <circle cx="15" cy="12" r="1.2" fill="var(--ink-muted)" stroke="none" />
                                            </svg>
                                            <p style="margin:0;font-size:13px;color:var(--ink-muted);">Belum ada voucher aktif.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div><!-- /panel-overview -->
@endsection
