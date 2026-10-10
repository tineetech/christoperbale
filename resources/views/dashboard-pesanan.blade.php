@extends('layouts.dashboard')

@section('title', 'Pesanan Saya — CHRISBALE')

@push('styles')
    <style>
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

        .ord-toolbar .ot-input:focus { border-color: var(--accent); }

        .ord-toolbar .ot-search { flex: 1 1 220px; min-width: 180px; }

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

        .ord-toolbar .ot-reset:hover { border-color: var(--ink); color: var(--ink); }

        /* ===== THUMB PLACEHOLDER — ikon produk bertema ===== */
        .oc-thumb-icon {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(184,134,11,0.08) 0%, rgba(184,134,11,0.18) 100%);
            border-radius: 7px;
        }

        .oc-thumb-icon svg {
            width: 26px;
            height: 26px;
            color: var(--accent);
            opacity: 0.75;
        }

        /* ===== ESTIMASI PENGIRIMAN ===== */
        .oc-eta {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: var(--ink-muted);
            margin-top: 5px;
        }

        .oc-eta svg {
            flex-shrink: 0;
            color: var(--ink-muted);
        }

        .oc-eta-highlight {
            font-weight: 600;
            color: var(--ink);
        }

        .oc-eta--arrived {
            color: #2E7D32;
        }

        .oc-eta--arrived svg {
            color: #2E7D32;
        }

        .oc-eta--arrived .oc-eta-highlight {
            color: #2E7D32;
        }

        .oc-eta--soon {
            color: #E65100;
        }

        .oc-eta--soon svg {
            color: #E65100;
        }

        .oc-eta--soon .oc-eta-highlight {
            color: #E65100;
        }
    </style>
@endpush

@section('dashboard-content')
    <div class="dash-panel" id="panel-orders">
        <div class="dash-section-head" style="margin-bottom:24px;">
            <h2 class="dash-section-title">Riwayat Pesanan</h2>
            <div class="order-filter-tabs">
                <button type="button" class="ofilter active" onclick="filterPesananTabs(this,'all')">Semua</button>
                <button type="button" class="ofilter" onclick="filterPesananTabs(this,'process')">Di Proses</button>
                <button type="button" class="ofilter" onclick="filterPesananTabs(this,'packing')">Di Kemas (Packing)</button>
                <button type="button" class="ofilter" onclick="filterPesananTabs(this,'shipping')">Di Kirim</button>
                <button type="button" class="ofilter" onclick="filterPesananTabs(this,'done')">Selesai</button>
            </div>
        </div>

        <form class="ord-toolbar" method="GET" action="{{ route('dashboard.pesanan') }}">
            <input class="ot-input ot-search" type="text" name="q"
                   placeholder="Cari kode pesanan / nama produk..."
                   value="{{ $filters['q'] ?? '' }}">

            <input class="ot-input" type="date" name="from" value="{{ $filters['from'] ?? '' }}">
            <span style="font-size:12px;color:var(--ink-muted);">s/d</span>
            <input class="ot-input" type="date" name="to" value="{{ $filters['to'] ?? '' }}">

            <button type="submit" class="ot-btn">Terapkan</button>
            @if (!empty(array_filter($filters)))
                <a href="{{ route('dashboard.pesanan') }}" class="ot-btn ot-reset">Reset</a>
            @endif
        </form>

        @if ($orders->isEmpty())
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;min-height:220px;padding:48px 20px;color:var(--ink-muted);background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--line);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.35;">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 01-8 0" />
                </svg>
                <p style="font-size:14px;font-weight:500;margin:0 0 4px;">Belum ada pesanan</p>
                <p style="font-size:12px;margin:0;">Pesanan yang kamu buat akan muncul di sini.</p>
            </div>
        @else
            <div class="orders-full-list" id="ordersFullList">
                @foreach ($orders as $order)
                @php
                    $orderId    = $order->kode_penjualan ?? '#CB-' . $order->id;
                    $orderDate  = $order->tanggal
                        ? \Carbon\Carbon::parse($order->tanggal)->format('d M Y')
                        : $order->created_at->format('d M Y');

                    $filterStatus = match ($order->status) {
                        'selesai' => 'done',
                        'dikirim' => 'shipping',
                        'packing' => 'packing',
                        'batal', 'cancelled' => 'cancelled',
                        default   => 'process',
                    };

                    $badgeClass = match ($order->status) {
                        'selesai' => 'oc-badge--done',
                        'dikirim' => 'oc-badge--shipping',
                        'packing' => 'oc-badge--packing',
                        'batal', 'cancelled' => 'oc-badge--cancelled',
                        default   => 'oc-badge--process',
                    };

                    $statusLabel = match ($order->status) {
                        'selesai' => 'Selesai',
                        'dikirim' => 'Di Kirim',
                        'packing' => 'Di Kemas',
                        'batal', 'cancelled' => 'Dibatalkan',
                        default   => 'Di Proses',
                    };

                    $totalItems = $order->detail->sum('qty');
                    $firstItem  = $order->detail->first();
                    $firstName  = $firstItem?->barang?->nama_barang ?? 'Produk';
                    $moreCount  = $order->detail->count() - 1;

                    // Foto produk pertama
                    $fotoUtama = $firstItem?->barang?->produk?->fotoUtama;
                    $fotoSrc   = $fotoUtama?->foto ? asset('storage/' . $fotoUtama->foto) : null;

                    // Estimasi tiba
                    $eta = null;
                    $etaClass = '';
                    $etaText  = '';

                    if ($order->status === 'selesai') {
                        $etaClass = 'oc-eta--arrived';
                        $etaText  = 'Pesanan telah diterima';
                    } elseif ($order->shipment && $order->shipment->estimation_days) {
                        $baseDate  = $order->tanggal
                            ? \Carbon\Carbon::parse($order->tanggal)
                            : $order->created_at;
                        $etaDate   = $baseDate->copy()->addDays((int) $order->shipment->estimation_days);
                        $today     = \Carbon\Carbon::today();
                        $diffDays  = (int) floor($today->diffInDays($etaDate, false)); // negatif = sudah lewat

                        if ($diffDays < 0) {
                            // estimasi sudah lewat tapi belum selesai
                            $etaClass = 'oc-eta--soon';
                            $etaText  = 'Est. tiba ' . $etaDate->format('d M Y');
                        } elseif ($diffDays === 0) {
                            $etaClass = 'oc-eta--soon';
                            $etaText  = 'Estimasi tiba hari ini';
                        } elseif ($diffDays <= 2) {
                            $etaClass = 'oc-eta--soon';
                            $etaText  = 'Est. tiba ' . $etaDate->format('d M Y') . ' (' . $diffDays . ' hari lagi)';
                        } else {
                            $etaText  = 'Est. tiba ' . $etaDate->format('d M Y') . ' (' . $diffDays . ' hari lagi)';
                        }
                    } elseif ($order->shipment && $order->shipment->courier) {
                        $etaText = 'Dikirim via ' . strtoupper($order->shipment->courier)
                            . ($order->shipment->service ? ' ' . $order->shipment->service : '');
                    }
                @endphp

                <div class="order-card"
                     data-status="{{ $filterStatus }}"
                     onclick="window.location.href='{{ route('dashboard.pesanan.detail', $order->id) }}'">

                    {{-- Head: Nama toko + ID + tanggal + status --}}
                    <div class="oc-head">
                        <div class="oc-head-left">
                            {{-- Ikon Toko (Boxicons bx-store-alt) --}}
                            <i class='bx bx-store-alt oc-store-icon'></i>
                            <span class="oc-store-name">CHRISBALE</span>
                            <span class="oc-order-id">{{ $orderId }}</span>
                        </div>
                        <div class="oc-head-right">
                            <span class="oc-date">{{ $orderDate }}</span>
                            <span class="oc-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>

                    {{-- Body: thumbnail produk + ringkasan + estimasi --}}
                    <div class="oc-body">
                        <div class="oc-thumbs">
                            {{-- Thumbnail produk pertama --}}
                            <div class="oc-thumb">
                                @if ($fotoSrc)
                                    <img src="{{ $fotoSrc }}" alt="{{ $firstName }}" loading="lazy"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    {{-- Fallback icon jika gambar gagal load --}}
                                    <div class="oc-thumb-icon" style="display:none;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                                            <line x1="3" y1="6" x2="21" y2="6"/>
                                            <path d="M16 10a4 4 0 01-8 0"/>
                                        </svg>
                                    </div>
                                @else
                                    {{-- Placeholder icon bertema shopping bag --}}
                                    <div class="oc-thumb-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                                            <line x1="3" y1="6" x2="21" y2="6"/>
                                            <path d="M16 10a4 4 0 01-8 0"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            {{-- Indikator item lebih --}}
                            @if ($moreCount > 0)
                                <div class="oc-thumb-more">+{{ $moreCount }}</div>
                            @endif
                        </div>

                        <div class="oc-info">
                            <div class="oc-prod-name">
                                {{ $firstName }}{{ $moreCount > 0 ? ', dan ' . $moreCount . ' produk lainnya' : '' }}
                            </div>
                            <div class="oc-prod-meta">{{ $totalItems }} barang</div>

                            {{-- Estimasi tiba --}}
                            @if ($etaText)
                                <div class="oc-eta {{ $etaClass }}">
                                    @if ($order->status === 'selesai')
                                        {{-- icon centang --}}
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    @elseif ($etaClass === 'oc-eta--soon')
                                        {{-- icon alarm --}}
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                    @else
                                        {{-- icon truk --}}
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="1" y="3" width="15" height="13"/>
                                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                                            <circle cx="5.5" cy="18.5" r="2.5"/>
                                            <circle cx="18.5" cy="18.5" r="2.5"/>
                                        </svg>
                                    @endif
                                    <span>{{ $etaText }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer: total bayar + tombol detail --}}
                    <div class="oc-footer">
                        <div>
                            <div class="oc-total-label">Total Pesanan</div>
                            <div class="oc-total-amount">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</div>
                        </div>
                        <a href="{{ route('dashboard.pesanan.detail', $order->id) }}"
                           class="oc-cta"
                           onclick="event.stopPropagation()">
                            Lihat Detail
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div id="ordersTabEmpty" style="display:none;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;min-height:220px;padding:48px 20px;color:var(--ink-muted);background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--line);">
                <p style="font-size:14px;font-weight:500;margin:0 0 4px;">Tidak ada pesanan pada status ini</p>
                <p style="font-size:12px;margin:0;">Coba pilih status lain atau ubah pencarian/tanggal.</p>
            </div>
        @endif
    </div><!-- /panel-orders -->
@endsection

@push('scripts')
<script>
    function filterPesananTabs(btn, status) {
        document.querySelectorAll('.ofilter').forEach(function (b) {
            b.classList.remove('active');
        });
        btn.classList.add('active');

        var visibleCount = 0;
        document.querySelectorAll('#ordersFullList .order-card').forEach(function (card) {
            var show = (status === 'all' || card.dataset.status === status);
            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        var emptyBox = document.getElementById('ordersTabEmpty');
        if (emptyBox) {
            emptyBox.style.display = visibleCount === 0 ? 'flex' : 'none';
        }
    }
</script>
@endpush
