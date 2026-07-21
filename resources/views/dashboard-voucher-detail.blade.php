@extends('layouts.dashboard')

@section('title', 'Detail Voucher — CHRISBALE')

@section('dashboard-content')
@php
    $v = $userVoucher->voucher;
    $isExpired = $v->end_at && now() > $v->end_at;
    $isUsed = $userVoucher->status === 'used';
    $isDisabled = $isExpired || $isUsed;
    $bgGrad = $v->type === 'percent'
        ? 'linear-gradient(135deg, var(--accent-dark), var(--accent-light))'
        : ($v->type === 'shipping'
            ? 'linear-gradient(135deg,#1A3A2A,#2E7D32)'
            : 'linear-gradient(135deg,#1A237E,#3949AB)');
@endphp

<div class="dash-panel">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
        <a href="{{ route('dashboard.voucher') }}" style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--ink-muted);text-decoration:none;border:1px solid var(--line);border-radius:var(--radius-sm);padding:8px 16px;transition:all 0.18s;" onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'" onmouseout="this.style.borderColor='';this.style.color=''">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2 style="font-size:18px;font-weight:600;margin:0;">Detail Voucher</h2>
    </div>

    <!-- Voucher Card Preview -->
    <div class="dash-voucher-card" style="margin-bottom:24px;{{ $isDisabled ? 'opacity:0.55;' : '' }}">
        <div class="dash-voucher-left" style="background:{{ $bgGrad }};position:relative;">
            @if ($isDisabled)
            <div style="position:absolute;bottom:6px;left:6px;right:6px;background:rgba(0,0,0,0.55);border-radius:4px;font-size:8px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#fff;padding:3px 6px;text-align:center;">{{ $isUsed ? 'Terpakai' : 'Kedaluwarsa' }}</div>
            @endif
            <div class="dash-voucher-amount" style="font-size:22px;">{{ $v->name ?? $v->code }}</div>
            <div class="dash-voucher-code">{{ $v->code }}</div>
        </div>
        <div class="dash-voucher-divider"></div>
        <div class="dash-voucher-right">
            <p style="font-size:15px;">{{ $v->description ?? $v->name }}</p>
            <span>Berlaku hingga {{ $v->end_at ? \Carbon\Carbon::parse($v->end_at)->format('d M Y') : '-' }}</span>
        </div>
    </div>

    @if ($isDisabled)
    <div style="margin-bottom:24px;padding:14px 18px;background:{{ $isUsed ? 'rgba(138,133,128,0.08)' : 'rgba(192,57,43,0.06)' }};border:1px solid {{ $isUsed ? 'var(--line)' : 'rgba(192,57,43,0.2)' }};border-radius:var(--radius-sm);">
        <span style="font-size:13px;color:{{ $isUsed ? 'var(--ink-muted)' : 'var(--red)' }};">{{ $isUsed ? 'Voucher sudah digunakan.' : 'Voucher sudah kedaluwarsa dan tidak dapat digunakan.' }}</span>
    </div>
    @endif

    <!-- Detail Informasi -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:24px;">
            <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;padding-bottom:12px;border-bottom:1px solid var(--line-soft);">Informasi Voucher</h3>
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ $v->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kode</span>
                <span class="info-value" style="font-family:monospace;font-weight:700;letter-spacing:0.06em;">{{ $v->code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tipe</span>
                <span class="info-value">{{ ucfirst($v->type) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nilai</span>
                <span class="info-value">{{ $v->name }}</span>
            </div>
            @if ($v->minimum_purchase)
            <div class="info-row">
                <span class="info-label">Min. Belanja</span>
                <span class="info-value">Rp{{ number_format($v->minimum_purchase, 0, ',', '.') }}</span>
            </div>
            @endif
            @if ($v->maximum_discount)
            <div class="info-row">
                <span class="info-label">Maks. Diskon</span>
                <span class="info-value">Rp{{ number_format($v->maximum_discount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:24px;">
            <h3 style="font-size:14px;font-weight:600;margin:0 0 16px;padding-bottom:12px;border-bottom:1px solid var(--line-soft);">Status & Masa Berlaku</h3>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value" style="color:{{ $isUsed ? 'var(--ink-muted)' : ($isExpired ? 'var(--red)' : 'var(--green)') }};">
                    {{ $isUsed ? 'Digunakan' : ($isExpired ? 'Kedaluwarsa' : 'Aktif') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Mulai</span>
                <span class="info-value">{{ $v->start_at ? \Carbon\Carbon::parse($v->start_at)->format('d M Y') : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Berakhir</span>
                <span class="info-value">{{ $v->end_at ? \Carbon\Carbon::parse($v->end_at)->format('d M Y') : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Klaim</span>
                <span class="info-value">{{ $userVoucher->claimed_at ? \Carbon\Carbon::parse($userVoucher->claimed_at)->format('d M Y H:i') : '-' }}</span>
            </div>
            @if ($userVoucher->used_at)
            <div class="info-row">
                <span class="info-label">Digunakan</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($userVoucher->used_at)->format('d M Y H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Syarat & Ketentuan -->
    <div style="background:var(--bg-card);border:1px solid var(--line);border-radius:var(--radius);padding:24px;margin-top:20px;">
        <h3 style="font-size:14px;font-weight:600;margin:0 0 12px;">Deskripsi & Syarat Ketentuan</h3>
        <div style="font-size:13px;color:var(--ink-soft);line-height:1.8;">
            {!! $v->description_full ?? 'Tidak ada syarat & ketentuan khusus.' !!}
        </div>
    </div>

    <!-- Actions -->
    <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap;">
        @if (!$isDisabled)
        <a href="/products" class="ofc-btn ofc-btn--primary" style="text-decoration:none;">Pakai Voucher</a>
        @endif
        <a href="{{ route('dashboard.voucher') }}" class="ofc-btn ofc-btn--outline" style="text-decoration:none;">Kembali ke Voucher</a>
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
