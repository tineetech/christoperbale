@extends('layouts.dashboard')

@section('title', 'Pembayaran Saya — CHRISBALE')

@push('styles')
    <style>
        .pay-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            background: var(--bg-card);
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
            background: var(--bg);
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

        .pay-stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .pay-stat {
            background: var(--bg-card);
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

        .ps-icon--gold { background: rgba(212, 148, 62, 0.15); }
        .ps-icon--gold svg { stroke: #D4943E; }
        .ps-icon--green { background: rgba(46, 125, 50, 0.15); }
        .ps-icon--green svg { stroke: #2E7D32; }
        .ps-icon--red { background: rgba(192, 57, 43, 0.15); }
        .ps-icon--red svg { stroke: #C0392B; }
        .ps-icon--blue { background: rgba(25, 118, 210, 0.15); }
        .ps-icon--blue svg { stroke: #1976D2; }

        .pay-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pay-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: box-shadow 0.2s, border-color 0.2s, transform 0.1s;
        }

        .pay-card:hover {
            box-shadow: var(--shadow-hover);
            border-color: var(--accent);
        }

        .pay-card:active {
            transform: scale(0.995);
        }

        .pay-card.is-pending {
            border-color: rgba(212, 148, 62, 0.55);
            background: linear-gradient(180deg, rgba(212, 148, 62, 0.06), rgba(212, 148, 62, 0.02) 55%, var(--bg-card));
        }

        .pay-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--line-soft);
            flex-wrap: wrap;
        }

        .pay-card-header .pch-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .pay-code {
            font-family: monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: 0.04em;
        }

        .pay-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            width: fit-content;
        }

        .pay-badge--pending {
            background: rgba(212, 148, 62, 0.15);
            color: #B8860B;
            border: 1px solid rgba(212, 148, 62, 0.35);
        }

        .pay-badge--paid {
            background: rgba(46, 125, 50, 0.12);
            color: var(--green);
        }

        .pay-badge--failed {
            background: rgba(192, 57, 43, 0.1);
            color: var(--red);
        }

        .pay-badge--other {
            background: rgba(25, 118, 210, 0.1);
            color: #1565C0;
        }

        .pay-date {
            font-size: 12px;
            color: var(--ink-muted);
        }

        .pay-card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 18px;
            flex-wrap: wrap;
        }

        .pay-products {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .pay-prod-row {
            font-size: 12.5px;
            color: var(--ink-soft);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pay-prod-row strong {
            color: var(--ink);
            font-weight: 500;
        }

        .pay-expiry {
            font-size: 11px;
            color: var(--red);
        }

        .pay-amount {
            text-align: right;
            flex-shrink: 0;
        }

        .pay-amount .pa-total {
            font-size: 15px;
            font-weight: 700;
            color: var(--accent);
        }

        .pay-amount .pa-action {
            font-size: 11px;
            font-weight: 600;
            color: var(--ink-muted);
            margin-top: 3px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .pay-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            min-height: 200px;
            padding: 48px 20px;
            color: var(--ink-muted);
            background: var(--bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--line);
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
                    <div class="ps-num">{{ $payments->count() }}</div>
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
                    <div class="ps-num">{{ $payments->where('status', 'paid')->count() }}</div>
                    <div class="ps-label">Lunas</div>
                </div>
            </div>
            <div class="pay-stat">
                <div class="ps-icon ps-icon--red">
                    <svg viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <div class="ps-num">{{ $payments->whereIn('status', ['deny', 'cancel', 'expire', 'failure'])->count() }}</div>
                    <div class="ps-label">Gagal / Dibatalkan</div>
                </div>
            </div>
        </div>

        <form class="pay-toolbar" method="GET" action="{{ route('dashboard.pembayaran') }}">
            <input class="pt-input pt-search" type="text" name="q" placeholder="Cari kode pesanan / nama produk..."
                value="{{ $filters['q'] ?? '' }}">

            <select class="pt-input" name="status">
                <option value="">Semua Status</option>
                <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending (Belum Dibayar)</option>
                <option value="paid" {{ ($filters['status'] ?? '') === 'paid' ? 'selected' : '' }}>Lunas</option>
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
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.4;">
                    <rect x="2" y="5" width="20" height="14" rx="2" />
                    <line x1="2" y1="10" x2="22" y2="10" />
                </svg>
                <p style="font-size:14px;">Belum ada data pembayaran.</p>
            </div>
        @else
            <div class="pay-list">
                @foreach ($payments as $p)
                    @php
                        $draft = $p->penjualanDraft;
                        $orderCode = $p->order_id_midtrans ?? '#CB-PAY-' . $p->id;
                        $payDate = $p->created_at ? $p->created_at->format('d M Y H:i') : '-';

                        if ($p->status === 'paid') {
                            $badgeText = 'Lunas';
                            $badgeClass = 'pay-badge--paid';
                        } elseif (in_array($p->status, ['deny', 'cancel', 'expire', 'failure'])) {
                            $badgeText = ucfirst($p->status);
                            $badgeClass = 'pay-badge--failed';
                        } elseif ($p->status === 'pending') {
                            $badgeText = 'Belum Dibayar';
                            $badgeClass = 'pay-badge--pending';
                        } else {
                            $badgeText = ucfirst($p->status);
                            $badgeClass = 'pay-badge--other';
                        }

                        $isPending = $p->status === 'pending';
                        $targetUrl = ($isPending || !$p->penjualan_id)
                            ? route('checkout.payment', $p->id)
                            : route('checkout.success', $p->penjualan_id);

                        $items = $draft && $draft->items ? $draft->items->take(3) : collect();
                    @endphp
                    <a class="pay-card {{ $isPending ? 'is-pending' : '' }}" href="{{ $targetUrl }}">
                        <div class="pay-card-header">
                            <div class="pch-left">
                                <span class="pay-code">{{ $orderCode }}</span>
                                <span class="pay-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>
                            <span class="pay-date">{{ $payDate }}</span>
                        </div>
                        <div class="pay-card-body">
                            <div class="pay-products">
                                @forelse ($items as $it)
                                    <div class="pay-prod-row">
                                        <strong>{{ $it->barang->produk->nama_produk ?? $it->barang->nama_barang ?? 'Produk' }}</strong>
                                        <span style="color:var(--ink-muted);">x{{ $it->qty }}</span>
                                    </div>
                                @empty
                                    <div class="pay-prod-row">Pesanan</div>
                                @endforelse
                                @if ($draft && $draft->items && $draft->items->count() > 3)
                                    <div class="pay-prod-row" style="color:var(--ink-muted);">+{{ $draft->items->count() - 3 }} produk lainnya</div>
                                @endif
                            </div>
                            <div class="pay-amount">
                                <div class="pa-total">Rp{{ number_format($p->amount, 0, ',', '.') }}</div>
                                @if ($isPending)
                                    <div class="pay-expiry">Berlaku hingga {{ $p->expired_at ? $p->expired_at->format('d M Y H:i') : '-' }}</div>
                                @else
                                    <div class="pa-action">{{ $isPending ? 'Bayar Sekarang' : 'Lihat Detail' }}</div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
