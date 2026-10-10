@extends('layouts.dashboard')

@section('title', 'Voucher & Promo — CHRISBALE')

@push('styles')
    <style>
        /* ---- Flash Alert (sama persis dengan dashboard alamat) ---- */
        .addr-flash {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            margin-bottom: 22px;
            border-radius: 14px;
            border: 1px solid transparent;
            animation: addrFlashIn 0.35s ease both;
        }

        .addr-flash--success {
            background: linear-gradient(135deg, #ecfdf3 0%, #f6fef9 100%);
            border-color: #bbf7d0;
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.10);
        }

        .addr-flash--error {
            background: linear-gradient(135deg, #fef2f2 0%, #fffafa 100%);
            border-color: #fecaca;
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.10);
        }

        .addr-flash-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 24px;
            color: #fff;
        }

        .addr-flash--success .addr-flash-icon {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.15);
        }

        .addr-flash--error .addr-flash-icon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.15);
        }

        .addr-flash-content { flex: 1; min-width: 0; }

        .addr-flash-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.01em;
            margin-bottom: 2px;
        }

        .addr-flash--success .addr-flash-title { color: #166534; }
        .addr-flash--error .addr-flash-title { color: #991b1b; }

        .addr-flash-msg {
            font-size: 13px;
            line-height: 1.5;
            color: #4b5563;
        }

        .addr-flash-close {
            background: none;
            border: none;
            cursor: pointer;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 18px;
            flex-shrink: 0;
            transition: background 0.18s, color 0.18s;
        }

        .addr-flash-close:hover { background: rgba(0, 0, 0, 0.06); color: #374151; }

        @keyframes addrFlashIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('dashboard-content')
                    <div class="dash-panel" id="panel-voucher">
                        <h2 class="dash-section-title" style="margin-bottom:28px;">Voucher & Promo</h2>

                        @if (session('success'))
                        <div class="addr-flash addr-flash--success" role="alert" data-flash>
                            <div class="addr-flash-icon">
                                <i class='bx bx-check'></i>
                            </div>
                            <div class="addr-flash-content">
                                <div class="addr-flash-title">Berhasil!</div>
                                <div class="addr-flash-msg">{{ session('success') }}</div>
                            </div>
                            <button type="button" class="addr-flash-close" aria-label="Tutup">
                                <i class='bx bx-x'></i>
                            </button>
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="addr-flash addr-flash--error" role="alert" data-flash>
                            <div class="addr-flash-icon">
                                <i class='bx bx-error'></i>
                            </div>
                            <div class="addr-flash-content">
                                <div class="addr-flash-title">Terjadi kesalahan</div>
                                <div class="addr-flash-msg">{{ session('error') }}</div>
                            </div>
                            <button type="button" class="addr-flash-close" aria-label="Tutup">
                                <i class='bx bx-x'></i>
                            </button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('dashboard.voucher.claim') }}" class="voucher-input-card">
                            @csrf
                            <div class="voucher-input-inner">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="var(--accent)" stroke-width="1.8">
                                    <path d="M20 12v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
                                    <path d="M22 7H2v5h20V7z" />
                                    <path d="M12 22V7" />
                                </svg>
                                <input type="text" name="code" placeholder="Masukkan kode voucher..." id="voucherInput" required>
                                <button type="submit" class="btn-submit"
                                    style="padding:12px 24px;border-radius:var(--radius-sm);">Terapkan</button>
                            </div>
                        </form>

                        <h3 style="font-size:13px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--ink-muted);margin:28px 0 16px;font-family:'Inter',sans-serif;">
                            Voucher Tersedia</h3>

                        @if ($userVouchers->isEmpty())
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;min-height:200px;padding:48px 20px;color:var(--ink-muted);background:var(--bg-card);border-radius:var(--radius);border:1px solid var(--line);">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="margin-bottom:16px;opacity:0.4;">
                                <path d="M20 12v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
                                <path d="M22 7H2v5h20V7z" />
                                <path d="M12 22V7" />
                            </svg>
                            <p style="font-size:14px;">Belum ada voucher tersedia.</p>
                        </div>
                        @else
                        <div class="vouchers-grid">
                            @foreach ($userVouchers as $uv)
                            @php
                                $v = $uv->voucher;
                                $isExpired = $v->end_at && now() > $v->end_at;
                                $isUsed = $uv->status === 'used';
                                $isDisabled = $isExpired || $isUsed;
                                $disabledClass = $isDisabled ? 'dash-voucher-card--expired' : '';
                                $bgGrad = $v->type === 'percent'
                                    ? 'linear-gradient(135deg, var(--accent-dark), var(--accent-light))'
                                    : ($v->type === 'shipping'
                                        ? 'linear-gradient(135deg,#1A3A2A,#2E7D32)'
                                        : 'linear-gradient(135deg,#1A237E,#3949AB)');
                            @endphp
                            <div class="dash-voucher-card {{ $disabledClass }}">
                                <div class="dash-voucher-left" style="background:{{ $bgGrad }};position:relative;">
                                    <div class="dash-voucher-amount">{{ $v->name ?? $v->code }}</div>
                                    <div class="dash-voucher-code">{{ $v->code }}</div>
                                </div>
                                <div class="dash-voucher-divider"></div>
                                <div class="dash-voucher-right">
                                    <p>{{ $v->description ?? $v->name }}</p>
                                    <span style="{{ $isDisabled ? 'color:var(--red);' : '' }}">Berlaku hingga {{ $v->end_at ? \Carbon\Carbon::parse($v->end_at)->format('d M Y') : '-' }}</span>
                                    @if ($isDisabled)
                                    <div style="margin-top:8px;padding:10px 14px;background:{{ $isUsed ? 'rgba(138,133,128,0.08)' : 'rgba(192,57,43,0.06)' }};border:1px solid {{ $isUsed ? 'var(--line)' : 'rgba(192,57,43,0.2)' }};border-radius:var(--radius-sm);">
                                        <span style="font-size:12px;color:{{ $isUsed ? 'var(--ink-muted)' : 'var(--red)' }};">{{ $isUsed ? 'Voucher sudah digunakan.' : 'Voucher sudah kedaluwarsa dan tidak dapat digunakan.' }}</span>
                                    </div>
                                    @else
                                    <div style="display:flex;gap:8px;margin-top:4px;flex-wrap:wrap;">
                                        <a href="/products" class="dash-copy-btn" style="text-decoration:none;">Pakai Voucher</a>
                                        <a href="{{ route('dashboard.voucher.detail', $uv->id) }}" class="dash-copy-btn" style="background:none;color:var(--ink-muted);border-color:var(--line);text-decoration:none;">Syarat & Ketentuan</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div><!-- /panel-voucher -->
@endsection

@push('scripts')
    <script>
        // Tombol tutup flash alert (sama persis dengan dashboard alamat)
        document.querySelectorAll('[data-flash]').forEach(function (el) {
            el.querySelector('.addr-flash-close').addEventListener('click', function () {
                el.remove();
            });
        });
    </script>
@endpush
