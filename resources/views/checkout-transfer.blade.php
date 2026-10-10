@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran — CHRISBALE')

@push('styles')
    {{-- Boxicons (hapus baris ini jika layouts.app sudah memuat Boxicons) --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        .checkout-wrap * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .checkout-wrap {
            width: 100%;
            max-width: 860px;
            margin: 20px auto;
            padding: 0 20px 80px;
            overflow-x: hidden;
        }

        .checkout-head {
            margin: 0 0 20px;
            padding-top: 16px;
        }

        .checkout-head h1 {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 4px;
        }

        .checkout-head .subtitle {
            font-size: 13px;
            color: var(--ink-muted);
            margin: 0;
        }

        .checkout-steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 32px;
            list-style: none;
            padding: 0;
        }

        .checkout-steps li {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            position: relative;
        }

        .checkout-steps li:not(:last-child)::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
            margin: 0 12px;
        }

        .checkout-steps .step-dot {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .checkout-steps .step-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
        }

        .checkout-steps li.is-pending .step-dot {
            background: var(--bg-card);
            border: 1.5px solid var(--line);
            color: var(--ink-muted);
        }

        .checkout-steps li.is-pending .step-label {
            color: var(--ink-muted);
        }

        .pay-card {
            background: var(--bg-card, #fff);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 16px;
        }

        .pay-card h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 16px;
        }

        .pay-card h2 svg {
            width: 17px;
            height: 17px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .pay-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 8px 0;
            border-bottom: 1px dashed var(--line);
            font-size: 13px;
        }

        .pay-row:last-child {
            border-bottom: none;
        }

        .pay-row .pay-label {
            color: var(--ink-muted);
        }

        .pay-row .pay-value {
            font-weight: 600;
            text-align: right;
        }

        .pay-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            margin-top: 4px;
        }

        .pay-total .pt-label {
            font-weight: 700;
        }

        .pay-total .pt-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--accent);
        }

        .btn-pay {
            flex: 1;
            min-width: 200px;
            padding: 14px 24px;
            border: none;
            border-radius: 999px;
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s;
        }

        .btn-pay:hover {
            opacity: .85;
        }

        .btn-pay:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .btn-ghost {
            padding: 14px 24px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: transparent;
            color: var(--ink);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Tombol dengan ikon Boxicons */
        .btn-pay.has-icon,
        .btn-ghost.has-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-pay.has-icon i,
        .btn-ghost.has-icon i {
            font-size: 18px;
            line-height: 1;
        }

        .btn-ghost.has-icon {
            flex: 1;
            min-width: 200px;
        }

        .btn-ghost.has-icon:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .pay-actions {
            display: flex;
            gap: 12px;
            margin-top: 4px;
            flex-wrap: wrap;
        }

        .bank-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border: 1.5px dashed var(--accent);
            border-radius: 12px;
            background: rgba(0, 0, 0, .02);
            margin-bottom: 14px;
        }

        .bank-box-left {
            min-width: 0;
        }

        .bank-name {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 2px;
        }

        .bank-number {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: .04em;
            word-break: break-all;
        }

        .btn-copy {
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid var(--accent);
            background: transparent;
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: all .2s;
        }

        .btn-copy:hover {
            background: var(--accent);
            color: #fff;
        }

        .proof-drop {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            min-height: 130px;
            border: 1.5px dashed var(--line);
            border-radius: 12px;
            padding: 22px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s;
        }

        .proof-drop:hover,
        .proof-drop.is-dragover {
            border-color: var(--accent);
        }

        .proof-drop.is-dragover {
            background: rgba(0, 0, 0, .04);
        }

        .proof-drop svg {
            width: 30px;
            height: 30px;
            stroke: var(--ink-muted);
            fill: none;
            stroke-width: 1.6;
            flex-shrink: 0;
        }

        .proof-drop p {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            word-break: break-word;
        }

        .proof-drop span {
            display: block;
            font-size: 11.5px;
            color: var(--ink-muted);
        }

        #proofPreview {
            display: none;
            max-width: 100%;
            max-height: 260px;
            margin: 14px auto 0;
            border-radius: 10px;
            border: 1px solid var(--line);
            object-fit: contain;
        }

        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 8px;
        }

        .thanks-wrap {
            text-align: center;
            padding: 26px 10px;
        }

        .thanks-icon {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: rgba(16, 185, 129, .12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .thanks-icon svg {
            width: 30px;
            height: 30px;
            stroke: #0d9f6e;
            fill: none;
            stroke-width: 2.4;
        }

        .thanks-wrap h3 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 8px;
        }

        .thanks-wrap p {
            font-size: 13.5px;
            color: var(--ink-muted);
            margin: 0 auto;
            max-width: 420px;
            line-height: 1.7;
        }

        .wait-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(250, 173, 20, .12);
            color: #b7791f;
            font-size: 13px;
            font-weight: 700;
        }

        .wait-badge .spinner {
            width: 13px;
            height: 13px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: tr-spin .8s linear infinite;
        }

        @keyframes tr-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .proof-done {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .proof-done img {
            display: block;
            max-width: 100%;
            max-height: 300px;
            margin: 0 auto;
            border-radius: 10px;
            border: 1px solid var(--line);
            object-fit: contain;
        }

        .proof-done .pd-label {
            font-size: 12px;
            color: var(--ink-muted);
            margin-bottom: 8px;
        }

        /* ===== Info verifikasi & kontak admin ===== */
        .verify-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .verify-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px dashed var(--line);
            font-size: 13px;
            line-height: 1.7;
        }

        .verify-list li:last-child {
            border-bottom: none;
        }

        .verify-list .vl-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .verify-list .vl-icon svg {
            width: 15px;
            height: 15px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .verify-list .vl-title {
            font-weight: 700;
            color: var(--ink);
        }

        .verify-list .vl-desc {
            color: var(--ink-muted);
        }

        .contact-box {
            margin-top: 16px;
            padding: 16px;
            border-radius: 12px;
            background: rgba(37, 211, 102, .08);
            border: 1px solid rgba(37, 211, 102, .35);
        }

        .contact-box .cb-text {
            font-size: 13px;
            line-height: 1.7;
            color: var(--ink);
            margin: 0 0 12px;
        }

        .btn-wa {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 999px;
            background: #25d366;
            color: #fff;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            transition: opacity .2s;
        }

        .btn-wa:hover {
            opacity: .88;
        }

        .btn-wa svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
            flex-shrink: 0;
        }

        @media (max-width: 520px) {
            .checkout-steps .step-label {
                display: none;
            }

            .btn-wa {
                width: 100%;
            }

            .pay-actions {
                flex-direction: column;
            }

            .btn-pay,
            .btn-ghost {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="wrap">
        <div class="checkout-wrap">
            <div class="checkout-head">
                <h1>Konfirmasi Pembayaran</h1>
                <p class="subtitle">Transfer Bank BCA — pesanan <strong>{{ $draft->kode_penjualan ?? ('#CB-PAY-' . $pembayaran->id) }}</strong></p>
            </div>

            <ul class="checkout-steps">
                <li><span class="step-dot">1</span><span class="step-label">Keranjang</span></li>
                <li><span class="step-dot">2</span><span class="step-label">Checkout</span></li>
                <li><span class="step-dot">3</span><span class="step-label">Pembayaran</span></li>
                <li class="{{ $confirmed ? '' : 'is-pending' }}"><span class="step-dot">4</span><span class="step-label">Selesai</span></li>
            </ul>

            @if (!$confirmed)
                @if ($draft)
                    <div class="pay-card">
                        <h2><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" /><line x1="1" y1="10" x2="23" y2="10" /></svg>
                            Ringkasan Pesanan</h2>

                        @foreach ($draft->items as $draftItem)
                            @php $item = $draftItem->barang; @endphp
                            <div class="pay-row">
                                <span class="pay-label">{{ $item->produk->nama_produk ?? $item->nama_barang ?? 'Produk' }} ×{{ $draftItem->qty }}</span>
                                <span class="pay-value">Rp{{ number_format($draftItem->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <div class="pay-row">
                            <span class="pay-label">Biaya Pengiriman ({{ $shipment->courier ?? '' }} {{ $shipment->service ?? '' }})</span>
                            <span class="pay-value">Rp{{ number_format($draft->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if ($draft->harga_discount > 0)
                            <div class="pay-row">
                                <span class="pay-label">Diskon</span>
                                <span class="pay-value">-Rp{{ number_format($draft->harga_discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="pay-card">
                    <h2><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" /><line x1="1" y1="10" x2="23" y2="10" /></svg>
                        Informasi Transfer Bank</h2>

                    <div class="pay-row">
                        <span class="pay-label">Bank Tujuan</span>
                        <span class="pay-value">BCA</span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">Atas Nama</span>
                        <span class="pay-value">CHRISBALE</span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">Metode</span>
                        <span class="pay-value">Transfer Manual — Konfirmasi Admin</span>
                    </div>

                    <div class="pay-total" style="padding-top:8px;">
                        <span class="pt-label">Total Bayar</span>
                        <span class="pt-value">Rp{{ number_format($pembayaran->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="bank-box" style="margin-top:16px;">
                        <div class="bank-box-left">
                            <div class="bank-name">Nomor Rekening</div>
                            <div class="bank-number">{{ $noRekening ?: '-' }}</div>
                        </div>
                        @if ($noRekening)
                            <button type="button" class="btn-copy" id="btnCopy" onclick="copyRek()">Salin</button>
                        @endif
                    </div>

                    <p style="font-size:12.5px;color:var(--ink-muted);line-height:1.7;margin:0;">
                        Silakan transfer <strong>tepat sebesar Rp{{ number_format($pembayaran->amount, 0, ',', '.') }}</strong> ke rekening di atas,
                        lalu unggah bukti transfer dan klik <strong>Konfirmasi Pembayaran</strong>. Pesanan akan diverifikasi oleh admin.
                    </p>
                </div>

                <div class="pay-card">
                    <h2><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg>
                        Unggah Bukti Transfer</h2>

                    <form action="{{ route('checkout.transfer.confirm', $pembayaran->id) }}" method="POST"
                        enctype="multipart/form-data" id="proofForm" onsubmit="return handleSubmit(this)">
                        @csrf
                        <label class="proof-drop" id="dropZone" for="proofInput">
                            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" /><polyline points="17 8 12 3 7 8" /><line x1="12" y1="3" x2="12" y2="15" /></svg>
                            <p id="dropText">Klik untuk pilih foto bukti transfer</p>
                            <span>JPG, JPEG, PNG, atau WEBP · maksimal 5MB</span>
                        </label>
                        <input type="file" name="proof_img" id="proofInput" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required hidden
                            onchange="previewProof(this)">
                        <img id="proofPreview" alt="Pratinjau bukti transfer">

                        @error('proof_img')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                        <div class="pay-actions" style="margin-top:18px;">
                            <button type="submit" class="btn-pay" id="btnConfirm">Konfirmasi Pembayaran</button>
                            <a href="{{ route('dashboard.pesanan') }}" class="btn-ghost">Nanti Saja</a>
                        </div>
                    </form>
                </div>
            @else
                @php
                    $kodePesanan = $draft->kode_penjualan ?? ('#CB-PAY-' . $pembayaran->id);
                    $waNumber = '6285817328482';
                    $waText = "Halo Admin CHRISBALE, saya ingin menanyakan status pembayaran pesanan saya.\n\n"
                        . "Kode Pesanan: {$kodePesanan}\n"
                        . "Total Bayar: Rp" . number_format($pembayaran->amount, 0, ',', '.') . "\n"
                        . "Metode: Transfer Bank BCA (Manual)\n\n"
                        . "Mohon dibantu pengecekannya. Terima kasih.";
                    $waLink = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText);
                    $dashboardUrl = Route::has('dashboard') ? route('dashboard') : url('/dashboard');
                @endphp

                <div class="pay-card">
                    <div class="thanks-wrap">
                        <span class="thanks-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" /></svg>
                        </span>
                        <h3>Terima Kasih!</h3>
                        <p>Pembayaran sedang diproses, mohon menunggu konfirmasi selanjutnya dari admin kami.</p>

                        <div class="wait-badge">
                            <span class="spinner"></span> Menunggu Konfirmasi Admin
                        </div>

                        <div class="pay-row" style="margin-top:24px;text-align:left;">
                            <span class="pay-label">Kode Pesanan</span>
                            <span class="pay-value">{{ $kodePesanan }}</span>
                        </div>
                        <div class="pay-row" style="text-align:left;">
                            <span class="pay-label">Total Bayar</span>
                            <span class="pay-value">Rp{{ number_format($pembayaran->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="pay-row" style="text-align:left;">
                            <span class="pay-label">Metode</span>
                            <span class="pay-value">Transfer Bank BCA (Manual)</span>
                        </div>

                        @if ($pembayaran->proof_img)
                            <div class="proof-done">
                                <div class="pd-label">Bukti transfer kamu:</div>
                                <img src="{{ asset('storage/' . $pembayaran->proof_img) }}" alt="Bukti transfer">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Informasi proses verifikasi & kontak admin (khusus transfer manual) --}}
                <div class="pay-card">
                    <h2><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><line x1="12" y1="16" x2="12" y2="12" /><line x1="12" y1="8" x2="12.01" y2="8" /></svg>
                        Informasi Proses Verifikasi</h2>

                    <ul class="verify-list">
                        <li>
                            <span class="vl-icon">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                            </span>
                            <div>
                                <div class="vl-title">Waktu Verifikasi</div>
                                <div class="vl-desc">Pembayaran akan diproses paling lambat <strong>1x24 jam operasional</strong>.</div>
                            </div>
                        </li>
                        <li>
                            <span class="vl-icon">
                                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg>
                            </span>
                            <div>
                                <div class="vl-title">Jam Operasional Admin</div>
                                <div class="vl-desc">Senin – Sabtu, pukul <strong>08.00 – 15.00 WIB</strong>. Pembayaran di luar jam tersebut akan diproses pada hari operasional berikutnya.</div>
                            </div>
                        </li>
                    </ul>

                    <div class="contact-box">
                        <p class="cb-text">
                            Jika pembayaran kamu dirasa belum terupdate statusnya, silakan hubungi kontak admin berikut:
                            <br><strong>+62 858-1732-8482</strong>
                        </p>
                        <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn-wa">
                            <svg viewBox="0 0 24 24"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.14-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.22 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.7.63.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.69.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35zM12.04 2C6.5 2 2 6.5 2 12.04c0 1.77.46 3.5 1.34 5.02L2 22l5.06-1.33a10.02 10.02 0 004.98 1.32C17.58 22 22 17.5 22 11.96 22 6.5 17.58 2 12.04 2z" /></svg>
                            Hubungi Admin via WhatsApp
                        </a>
                    </div>
                </div>

                {{-- Kartu aksi: Lihat Pesanan Saya & Kembali ke Dashboard --}}
                <div class="pay-card">
                    <div class="pay-actions" style="margin-top:0;">
                        <a href="{{ route('dashboard.pesanan') }}" class="btn-pay has-icon">
                            <i class='bx bx-receipt'></i> Lihat Pesanan Saya
                        </a>
                        <a href="{{ $dashboardUrl }}" class="btn-ghost has-icon">
                            <i class='bx bx-home-alt'></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyRek() {
            var btn = document.getElementById('btnCopy');
            var number = btn.closest('.bank-box').querySelector('.bank-number').textContent.trim();

            function done() {
                var old = btn.textContent;
                btn.textContent = 'Tersalin!';
                setTimeout(function() {
                    btn.textContent = old;
                }, 1500);
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(number).then(done);
            } else {
                var ta = document.createElement('textarea');
                ta.value = number;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                done();
            }
        }

        function previewProof(input) {
            var preview = document.getElementById('proofPreview');
            var dropText = document.getElementById('dropText');

            if (!input.files || !input.files[0]) {
                preview.style.display = 'none';
                preview.src = '';
                dropText.textContent = 'Klik untuk pilih foto bukti transfer';
                return;
            }

            dropText.textContent = input.files[0].name;
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }

        function handleSubmit(form) {
            var input = document.getElementById('proofInput');
            if (!input.files || !input.files.length) {
                alert('Silakan pilih foto bukti transfer terlebih dahulu.');
                return false;
            }

            var btn = document.getElementById('btnConfirm');
            btn.disabled = true;
            btn.textContent = 'Mengunggah...';
            return true;
        }

        (function() {
            var dropZone = document.getElementById('dropZone');
            var proofInput = document.getElementById('proofInput');

            // Pada tampilan "selesai" form upload tidak dirender, jadi hentikan di sini
            if (!dropZone || !proofInput) return;

            ['dragenter', 'dragover'].forEach(function(evName) {
                dropZone.addEventListener(evName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('is-dragover');
                });
            });

            ['dragleave', 'dragend', 'mouseout'].forEach(function(evName) {
                dropZone.addEventListener(evName, function(e) {
                    dropZone.classList.remove('is-dragover');
                });
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('is-dragover');

                var files = e.dataTransfer && e.dataTransfer.files;
                if (!files || !files.length) return;

                var file = files[0];
                if (!/^image\/(jpeg|png|webp)$/.test(file.type)) {
                    alert('Format file harus JPG, JPEG, PNG, atau WEBP.');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran gambar maksimal 5MB.');
                    return;
                }

                try {
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    proofInput.files = dt.files;
                } catch (err) {
                    proofInput.files = files;
                }

                previewProof(proofInput);
            });

            document.addEventListener('dragover', function(e) {
                e.preventDefault();
            });
            document.addEventListener('drop', function(e) {
                if (!dropZone.contains(e.target)) {
                    e.preventDefault();
                }
            });
        })();
    </script>
@endpush