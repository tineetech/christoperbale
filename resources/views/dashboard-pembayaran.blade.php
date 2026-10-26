@extends('layouts.dashboard')

@section('title', 'Pembayaran Saya — CHRISBALE')

@push('styles')
    <style>
        /* Toolbar with clean white background */
        .pay-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 14px;
        }

        .pay-toolbar .pt-input,
        .pay-toolbar select {
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            padding: 9px 12px;
            font-size: 12.5px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: #ffffff;
            outline: none;
            transition: border-color 0.18s;
        }

        .pay-toolbar .pt-input:focus,
        .pay-toolbar select:focus {
            border-color: var(--accent);
        }

        .pay-toolbar .pt-search {
            flex: 1 1 220px;
            min-width: 180px;
        }

        .pay-toolbar .pt-btn {
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

        .pay-toolbar .pt-btn:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
        }

        .pay-toolbar .pt-reset {
            background: none;
            color: var(--ink-muted);
            border-color: var(--line);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .pay-toolbar .pt-reset:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        /* Stats Cards with clean white background */
        .pay-stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .pay-stat {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .pay-stat .ps-num {
            font-size: 26px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }

        .pay-stat .ps-label {
            font-size: 11px;
            color: var(--ink-muted);
            margin-top: 4px;
            letter-spacing: 0.04em;
        }

        .pay-stat .ps-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pay-stat .ps-icon svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke-width: 1.8;
        }

        .ps-icon--gold  { background: rgba(212, 148, 62, 0.15); }
        .ps-icon--gold svg  { stroke: #D4943E; }
        .ps-icon--green { background: rgba(46, 125, 50, 0.15); }
        .ps-icon--green svg { stroke: #2E7D32; }
        .ps-icon--red   { background: rgba(192, 57, 43, 0.15); }
        .ps-icon--red svg   { stroke: #C0392B; }
        .ps-icon--blue  { background: rgba(25, 118, 210, 0.15); }
        .ps-icon--blue svg  { stroke: #1976D2; }

        /* Empty State */
        .pay-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            min-height: 220px;
            padding: 48px 20px;
            color: var(--ink-muted);
            background: #ffffff;
            border-radius: var(--radius);
            border: 1px solid var(--line);
        }

        /* ===== LIST CARD PEMBAYARAN SIMPLE (WHITE BACKGROUND) ===== */
        .pay-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pay-card {
            background: #ffffff !important;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
            transition: box-shadow 0.22s, transform 0.18s, border-color 0.18s;
            cursor: pointer;
        }

        .pay-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
            border-color: var(--accent);
        }

        /* Left indicator border based on status */
        .pay-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .pay-card[data-status="pending"]::before      { background: #D97706; }
        .pay-card[data-status="confirmation"]::before { background: #2563EB; }
        .pay-card[data-status="paid"]::before         { background: #16A34A; }
        .pay-card[data-status="failed"]::before       { background: #DC2626; }
        .pay-card[data-status="canceled"]::before     { background: #DC2626; }

        /* Card Header: Store + ID + Date + Badge */
        .pay-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px 12px 24px;
            border-bottom: 1px solid var(--line-soft);
            gap: 12px;
            flex-wrap: wrap;
            background: #ffffff;
        }

        .pay-head-left {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pay-code {
            font-size: 12px;
            color: var(--ink-muted);
            font-family: monospace;
            letter-spacing: 0.03em;
        }

        .pay-head-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pay-date {
            font-size: 11.5px;
            color: var(--ink-muted);
        }

        /* Solid Badge with Center Dot */
        .pay-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.10);
            width: fit-content;
        }

        .pay-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
            background: #ffffff;
        }

        .pay-badge--pending {
            background: #D97706;
            color: #ffffff;
        }

        .pay-badge--confirmation {
            background: #2563EB;
            color: #ffffff;
        }

        .pay-badge--paid {
            background: #16A34A;
            color: #ffffff;
        }

        .pay-badge--failed {
            background: #DC2626;
            color: #ffffff;
        }

        /* Simplified Card Main Content */
        .pay-card-main {
            padding: 16px 20px 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            background: #ffffff;
        }

        .pay-main-left {
            display: flex;
            flex-direction: column;
            gap: 3px;
            min-width: 180px;
        }

        .pay-amount-label {
            font-size: 11px;
            color: var(--ink-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .pay-amount-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--accent);
            line-height: 1.2;
        }

        .pay-method-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11.5px;
            color: var(--ink-soft);
            margin-top: 3px;
        }

        .pay-method-tag i {
            font-size: 14px;
            color: var(--ink-muted);
        }

        .pay-main-right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Expiry Box */
        .pay-expiry-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(230, 81, 0, 0.06);
            border: 1px solid rgba(230, 81, 0, 0.2);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
        }

        .pay-expiry-box i {
            font-size: 18px;
            color: #E65100;
        }

        .pay-expiry-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 600;
            color: #E65100;
        }

        .pay-expiry-val {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--ink);
        }

        /* Paid Box */
        .pay-paid-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(22, 163, 74, 0.06);
            border: 1px solid rgba(22, 163, 74, 0.2);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
        }

        .pay-paid-box i {
            font-size: 18px;
            color: #16A34A;
        }

        .pay-paid-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 600;
            color: #16A34A;
        }

        .pay-paid-val {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--ink);
        }

        /* CTA Button */
        .pay-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: 8px 18px;
            transition: all 0.18s;
            white-space: nowrap;
            text-decoration: none;
        }

        .pay-cta--primary {
            background: var(--accent);
            color: #ffffff;
            height: 40px;
            border: 1.5px solid var(--accent);
        }

        .pay-cta--primary:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
        }

        .pay-cta--outline {
            background: none;
            color: var(--accent);
            border: 1.5px solid var(--accent);
        }

        .pay-cta--outline:hover {
            background: var(--accent);
            color: #ffffff;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .pagination button {
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            color: var(--ink-soft);
            background: #ffffff;
            cursor: pointer;
            transition: all .2s;
            font-family: 'Inter', sans-serif;
        }

        .pagination button:hover:not(:disabled):not(.active) {
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination button.active {
            border-color: var(--accent);
            background: var(--accent);
            color: #fff;
        }

        .pagination button:disabled {
            opacity: 0.35;
            cursor: default;
        }

        .pagination .page-info {
            font-size: 13px;
            color: var(--ink-muted);
            padding: 0 8px;
            white-space: nowrap;
        }

        @media(max-width:600px) {
            .pagination button {
                min-width: 36px;
                height: 36px;
                font-size: 12px;
                padding: 0 8px;
            }
        }
    </style>
@endpush

@section('dashboard-content')
    <div class="dash-panel">
        <div class="dash-section-head" style="margin-bottom:20px;">
            <div>
                <h2 class="dash-section-title">Pembayaran Saya</h2>
                <p style="font-size:12.5px;color:var(--ink-muted);margin:4px 0 0;">Kelola dan selesaikan pembayaran pesanan Anda.</p>
            </div>
            @if ($pendingCount > 0)
                <span class="pay-badge pay-badge--pending" style="font-size:11px;padding:6px 14px;">
                    {{ $pendingCount }} pembayaran belum selesai
                </span>
            @endif
        </div>

        <div class="pay-stats-row">
            <div class="pay-stat">
                <div class="ps-icon ps-icon--blue">
                    <svg viewBox="0 0 24 24" stroke="currentColor"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
                <div>
                    <div class="ps-num">{{ $totalCount }}</div>
                    <div class="ps-label">Total Pembayaran</div>
                </div>
            </div>
            <div class="pay-stat">
                <div class="ps-icon ps-icon--gold">
                    <svg viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <div>
                    <div class="ps-num">{{ $pendingCount }}</div>
                    <div class="ps-label">Belum Dibayar</div>
                </div>
            </div>
            <div class="pay-stat">
                <div class="ps-icon ps-icon--green">
                    <svg viewBox="0 0 24 24" stroke="currentColor"><path d="M20 6L9 17l-5-5"/></svg>
                </div>
                <div>
                    <div class="ps-num">{{ $paidCount }}</div>
                    <div class="ps-label">Lunas</div>
                </div>
            </div>
            <div class="pay-stat">
                <div class="ps-icon ps-icon--red">
                    <svg viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <div class="ps-num">{{ $failedCount }}</div>
                    <div class="ps-label">Gagal / Dibatalkan</div>
                </div>
            </div>
        </div>

        <form class="pay-toolbar" method="GET" action="{{ route('dashboard.pembayaran') }}">
            <input class="pt-input pt-search" type="text" name="q" placeholder="Cari kode pesanan / pembayaran..."
                value="{{ $filters['q'] ?? '' }}">

            <select class="pt-input" name="status">
                <option value="">Semua Status</option>
                <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending (Belum Dibayar)</option>
                <option value="paid" {{ ($filters['status'] ?? '') === 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="canceled" {{ ($filters['status'] ?? '') === 'canceled' ? 'selected' : '' }}>Dibatalkan (Canceled)</option>
                <option value="gagal" {{ ($filters['status'] ?? '') === 'gagal' ? 'selected' : '' }}>Gagal / Dibatalkan</option>
            </select>

            <input class="pt-input" type="date" name="from" value="{{ $filters['from'] ?? '' }}">
            <span style="font-size:12px;color:var(--ink-muted);">s/d</span>
            <input class="pt-input" type="date" name="to" value="{{ $filters['to'] ?? '' }}">

            <button type="submit" class="pt-btn">Terapkan</button>
            @if (isset($filters['q']) || isset($filters['status']) || isset($filters['from']) || isset($filters['to']))
                <a href="{{ route('dashboard.pembayaran') }}" class="pt-btn pt-reset">Reset</a>
            @endif
        </form>

        @if ($payments->isEmpty())
            <div class="pay-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.35;">
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                    <line x1="2" y1="10" x2="22" y2="10" />
                </svg>
                <p style="font-size:14px;font-weight:500;margin:0 0 4px;">Belum ada data pembayaran</p>
                <p style="font-size:12px;margin:0;">Tagihan pembayaran pesanan Anda akan muncul di sini.</p>
            </div>
        @else
            <div class="pay-list">
                @foreach ($payments as $p)
                    @php
                        $draft = $p->penjualanDraft;
                        $penjualan = $p->penjualan;

                        $orderCode = $p->order_id_midtrans
                            ?? ($penjualan?->kode_penjualan
                            ?? ($draft?->kode_penjualan
                            ?? '#CB-PAY-' . $p->id));

                        $payDate = $p->created_at ? $p->created_at->format('d M Y H:i') : '-';

                        if ($p->status === 'paid') {
                            $badgeText = 'Lunas';
                            $badgeClass = 'pay-badge--paid';
                            $dataStatus = 'paid';
                        } elseif (in_array($p->status, ['canceled', 'deny', 'cancel', 'expire', 'failure'])) {
                            $badgeText = match($p->status) {
                                'canceled' => 'Dibatalkan',
                                'expire' => 'Kedaluwarsa',
                                'cancel' => 'Dibatalkan',
                                'deny'   => 'Ditolak',
                                default  => 'Gagal',
                            };
                            $badgeClass = 'pay-badge--failed';
                            $dataStatus = $p->status === 'canceled' ? 'canceled' : 'failed';
                        } elseif ($p->status === 'pending') {
                            $badgeText = 'Belum Dibayar';
                            $badgeClass = 'pay-badge--pending';
                            $dataStatus = 'pending';
                        } elseif ($p->status === 'paid_confirmation') {
                            $badgeText = 'Konfirmasi Admin';
                            $badgeClass = 'pay-badge--confirmation';
                            $dataStatus = 'confirmation';
                        } else {
                            $badgeText = ucfirst($p->status);
                            $badgeClass = 'pay-badge--pending';
                            $dataStatus = 'pending';
                        }

                        $isPending = $p->status === 'pending' || $p->status === 'paid_confirmation';

                        if (in_array($p->status, ['canceled', 'deny', 'cancel', 'expire', 'failure'])) {
                            $targetUrl = route('dashboard.pembayaran');
                        } elseif ($p->status === 'paid_confirmation' || ($p->payment_method === 'bank_transfer' && $isPending)) {
                            $targetUrl = route('checkout.transfer', $p->id);
                        } elseif ($p->status === 'pending' || !$p->penjualan_id) {
                            $targetUrl = route('checkout.payment', $p->id);
                        } else {
                            $targetUrl = route('checkout.success', $p->penjualan_id);
                        }

                        // Payment method detail text
                        $methodText = $p->payment_method
                            ? ucfirst(str_replace('_', ' ', $p->payment_method))
                            : ($p->payment_type ? ucfirst(str_replace('_', ' ', $p->payment_type)) : 'Midtrans');
                    @endphp

                    <a class="pay-card" data-status="{{ $dataStatus }}" href="{{ $targetUrl }}">
                        {{-- Head: Store + Order/Pay Code + Date + Status Badge --}}
                        <div class="pay-card-head">
                            <div class="pay-head-left">
                                <i class='bx bx-store-alt oc-store-icon'></i>
                                <span class="oc-store-name">CHRISBALE</span>
                                <span class="pay-code">{{ $orderCode }}</span>
                            </div>
                            <div class="pay-head-right">
                                <span class="pay-date">{{ $payDate }}</span>
                                <span class="pay-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>
                        </div>

                        {{-- Main Body: Ringkasan Singkat (Harga, Metode, Status Expired/Lunas, & Tombol Aksi) --}}
                        <div class="pay-card-main">
                            <div class="pay-main-left">
                                <div class="pay-amount-label">Total Tagihan</div>
                                <div class="pay-amount-value">Rp{{ number_format($p->amount, 0, ',', '.') }}</div>
                                <div class="pay-method-tag">
                                    <i class='bx bx-credit-card'></i>
                                    <span>{{ $methodText }}</span>
                                </div>
                            </div>

                            <div class="pay-main-right">
                                {{-- Info Tambahan: Expired (hanya saat pending, bukan konfirmasi admin) --}}
                                @if ($p->status === 'pending' && $p->expired_at)
                                    <div class="pay-expiry-box">
                                        <i class='bx bx-time-five'></i>
                                        <div>
                                            <div class="pay-expiry-label">BATAS PEMBAYARAN</div>
                                            <div class="pay-expiry-val">{{ $p->expired_at->format('d M Y H:i') }}</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- CTA Button --}}
                                <div>
                                    @if ($p->status === 'pending')
                                        <span class="pay-cta pay-cta--primary">
                                            Bayar Sekarang
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                        </span>
                                    @else
                                        <span class="pay-cta pay-cta--outline">
                                            Lihat Detail
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($payments->hasPages())
                <div class="pagination">
                    @if ($payments->onFirstPage())
                        <button disabled>‹</button>
                    @else
                        <a href="{{ $payments->previousPageUrl() }}"><button>‹</button></a>
                    @endif

                    @php
                        $current = $payments->currentPage();
                        $last = $payments->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                        if ($end - $start < 4) {
                            $start = max(1, $end - 4);
                            $end = min($last, $start + 4);
                        }
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $payments->url(1) }}"><button>1</button></a>
                        @if ($start > 2)
                            <span class="page-info">…</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $current)
                            <button class="active">{{ $i }}</button>
                        @else
                            <a href="{{ $payments->url($i) }}"><button>{{ $i }}</button></a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="page-info">…</span>
                        @endif
                        <a href="{{ $payments->url($last) }}"><button>{{ $last }}</button></a>
                    @endif

                    @if ($payments->hasMorePages())
                        <a href="{{ $payments->nextPageUrl() }}"><button>›</button></a>
                    @else
                        <button disabled>›</button>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection
