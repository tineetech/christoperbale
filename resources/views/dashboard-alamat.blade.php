@extends('layouts.dashboard')

@section('title', 'Alamat Pengiriman — CHRISBALE')

@push('styles')
    <style>
        /* ---- Modern Address Cards ---- */
        .addr-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .addr-modern-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            transition: box-shadow 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
            position: relative;
        }

        .addr-modern-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.10);
            border-color: #d1d5db;
            transform: translateY(-2px);
        }

        .addr-modern-card--default {
            border-color: var(--accent);
            box-shadow: 0 4px 16px rgba(184, 134, 11, 0.14);
        }

        .addr-modern-card--default:hover {
            box-shadow: 0 8px 28px rgba(184, 134, 11, 0.20);
        }

        /* Card top accent bar */
        .addr-card-bar {
            height: 4px;
            background: #f3f4f6;
        }

        .addr-modern-card--default .addr-card-bar {
            background: linear-gradient(90deg, var(--accent-dark), var(--accent-light));
        }

        /* Card body */
        .addr-card-body {
            padding: 20px 22px 18px;
        }

        /* Header: icon + label + badge */
        .addr-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 14px;
            gap: 10px;
        }

        .addr-card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .addr-icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(184, 134, 11, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .addr-icon-wrap svg {
            width: 18px;
            height: 18px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .addr-label-text {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.01em;
        }

        .addr-utama-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(184, 134, 11, 0.12);
            color: var(--accent);
            border: 1px solid rgba(184, 134, 11, 0.3);
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Divider */
        .addr-card-divider {
            height: 1px;
            background: #f3f4f6;
            margin: 0 22px;
        }

        /* Info rows */
        .addr-card-info {
            padding: 14px 22px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .addr-info-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .addr-info-bx {
            font-size: 16px;
            color: #9ca3af;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .addr-info-text {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.5;
        }

        .addr-info-text strong {
            color: #111827;
            font-weight: 600;
        }

        /* Footer actions */
        .addr-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 22px 16px;
            gap: 8px;
            flex-wrap: wrap;
        }

        .addr-action-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .addr-btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--accent);
            border: 1.5px solid var(--accent);
            border-radius: 8px;
            padding: 6px 14px;
            background: none;
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }

        .addr-btn-edit:hover {
            background: var(--accent);
            color: #fff;
        }

        .addr-btn-edit i {
            font-size: 14px;
        }

        .addr-btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #dc2626;
            border: 1.5px solid #fca5a5;
            border-radius: 8px;
            padding: 6px 14px;
            background: none;
            cursor: pointer;
            transition: all 0.18s;
        }

        .addr-btn-delete:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }

        .addr-btn-delete i {
            font-size: 14px;
        }

        .addr-btn-default {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 500;
            color: #6b7280;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px 12px;
            background: none;
            cursor: pointer;
            transition: all 0.18s;
        }

        .addr-btn-default:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .addr-btn-default i {
            font-size: 13px;
        }

        /* ---- Flash Alert ---- */
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
        /* Empty state */
        .addr-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            padding: 60px 24px;
            background: #ffffff;
            border-radius: 16px;
            border: 1px dashed #d1d5db;
        }

        .addr-empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(184, 134, 11, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .addr-empty-icon svg {
            width: 30px;
            height: 30px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 1.5;
            opacity: 0.7;
        }

        .addr-empty-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .addr-empty-desc {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 24px;
        }
    </style>
@endpush

@section('dashboard-content')
    <div class="dash-panel" id="panel-address">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:28px;">
            <div>
                <h2 class="dash-section-title" style="margin-bottom:4px;">Alamat Pengiriman</h2>
                <p style="font-size:13px;color:var(--ink-muted);margin:0;">Kelola alamat pengiriman untuk transaksimu.</p>
            </div>
            <a href="{{ route('dashboard.alamat.create') }}" class="ofc-btn ofc-btn--primary" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
                <i class='bx bx-plus' style="font-size:17px;"></i>
                Tambah Alamat
            </a>
        </div>

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

        @if (session('error'))
            <div class="dash-alert" style="border-color:var(--red);background:rgba(192,57,43,0.06);margin-bottom:20px;">
                <div class="dash-alert-dot" style="background:var(--red);animation:none;"></div>
                <div class="dash-alert-content">
                    <strong style="color:var(--red);">{{ session('error') }}</strong>
                </div>
            </div>
        @endif

        @if ($addresses->isEmpty())
            <div class="addr-empty-state">
                <div class="addr-empty-icon">
                    <i class='bx bx-map-pin' style="font-size:32px;color:var(--accent);"></i>
                </div>
                <div class="addr-empty-title">Belum ada alamat tersimpan</div>
                <div class="addr-empty-desc">Tambahkan alamat pengiriman untuk memudahkan proses checkout.</div>
                <a href="{{ route('dashboard.alamat.create') }}" class="ofc-btn ofc-btn--primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <i class='bx bx-plus' style="font-size:16px;"></i>
                    Tambah Alamat Baru
                </a>
            </div>
        @else
            <div class="addr-cards-grid">
                @foreach ($addresses as $address)
                    <div class="addr-modern-card {{ $address->is_default ? 'addr-modern-card--default' : '' }}">
                        <div class="addr-card-bar"></div>
                        <div class="addr-card-body">
                            <div class="addr-card-header">
                                <div class="addr-card-header-left">
                                    <div class="addr-icon-wrap">
                                        <i class='bx bx-map-pin' style="font-size:20px;color:var(--accent);"></i>
                                    </div>
                                    <span class="addr-label-text">{{ $address->label }}</span>
                                </div>
                                @if ($address->is_default)
                                    <span class="addr-utama-badge">
                                        <i class='bx bxs-check-circle' style="font-size:12px;"></i>
                                        Utama
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="addr-card-divider"></div>

                        <div class="addr-card-info">
                            <div class="addr-info-row">
                                <i class='bx bx-user addr-info-bx'></i>
                                <div class="addr-info-text"><strong>{{ $address->receiver_name }}</strong></div>
                            </div>
                            <div class="addr-info-row">
                                <i class='bx bx-phone addr-info-bx'></i>
                                <div class="addr-info-text">{{ $address->phone }}</div>
                            </div>
                            <div class="addr-info-row">
                                <i class='bx bx-map addr-info-bx'></i>
                                <div class="addr-info-text">
                                    {{ $address->address }}{{ $address->district ? ', ' . $address->district : '' }},
                                    {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                </div>
                            </div>
                            @if ($address->catatan)
                                <div class="addr-info-row">
                                    <i class='bx bx-notepad addr-info-bx'></i>
                                    <div class="addr-info-text" style="font-style:italic;color:#9ca3af;">{{ $address->catatan }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="addr-card-divider"></div>

                        <div class="addr-card-footer">
                            <div class="addr-action-group">
                                <a href="{{ route('dashboard.alamat.edit', $address->id) }}" class="addr-btn-edit">
                                    <i class='bx bx-edit-alt'></i>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('dashboard.alamat.delete', $address->id) }}" class="delete-address-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="addr-btn-delete btn-delete-address">
                                        <i class='bx bx-trash'></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            @if (!$address->is_default)
                                <form method="POST" action="{{ route('dashboard.alamat.default', $address->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="addr-btn-default">
                                        <i class='bx bx-check'></i>
                                        Jadikan Utama
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Delete address confirmation with SweetAlert2
    document.querySelectorAll('.btn-delete-address').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var form = this.closest('.delete-address-form');
            Swal.fire({
                title: 'Hapus alamat ini?',
                text: 'Alamat yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                borderRadius: '12px',
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    // Tombol tutup flash alert
document.querySelectorAll('[data-flash]').forEach(function (el) {
    el.querySelector('.addr-flash-close').addEventListener('click', function () {
        el.remove();
    });
});
</script>
@endpush