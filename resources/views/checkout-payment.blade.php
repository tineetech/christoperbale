@extends('layouts.app')

@section('title', 'Pembayaran — CHRISBALE')

@push('styles')
    @if ($isProduction)
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @endif
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

        .pay-product {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px dashed var(--line);
        }

        .pay-product:last-child {
            border-bottom: none;
        }

        .pay-product .pp-img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #111;
            object-fit: cover;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.12);
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .pay-product .pp-name {
            font-size: 13px;
            font-weight: 600;
            flex: 1;
            min-width: 0;
        }

        .pay-product .pp-variant {
            font-size: 12px;
            color: var(--ink-muted);
        }

        .pay-product .pp-right {
            text-align: right;
            flex-shrink: 0;
        }

        .pay-product .pp-price {
            font-size: 13px;
            font-weight: 700;
        }

        .pay-product .pp-qty {
            font-size: 12px;
            color: var(--ink-muted);
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

        .pay-actions {
            display: flex;
            gap: 12px;
            margin-top: 4px;
            flex-wrap: wrap;
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

        .pay-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            padding: 10px 16px;
            border-radius: 10px;
            margin-top: 16px;
            font-weight: 600;
        }

        .pay-status.is-pending {
            background: rgba(250, 173, 20, .12);
            color: #b7791f;
        }

        .pay-status.is-paid {
            background: rgba(16, 185, 129, .12);
            color: #0d9f6e;
        }

        .pay-status .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: pay-spin .8s linear infinite;
        }

        @keyframes pay-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .pay-note {
            font-size: 12px;
            color: var(--ink-muted);
            line-height: 1.6;
            margin-top: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="wrap">
        <div class="checkout-wrap">
            <div class="checkout-head">
                <h1>Pembayaran</h1>
                <p class="subtitle">Selesaikan pembayaran pesanan <strong>{{ $draft->kode_penjualan }}</strong>.</p>
            </div>

            <ul class="checkout-steps">
                <li><span class="step-dot">1</span><span class="step-label">Keranjang</span></li>
                <li><span class="step-dot">2</span><span class="step-label">Checkout</span></li>
                <li><span class="step-dot">3</span><span class="step-label">Pembayaran</span></li>
                <li class="is-pending"><span class="step-dot">4</span><span class="step-label">Selesai</span></li>
            </ul>

            @if ($draft->status === 'paid')
                <div class="pay-card">
                    <h2><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><path d="M9 12l2 2 4-4" /></svg>
                        Pembayaran Selesai</h2>
                    <p style="font-size:14px;margin:0 0 16px;color:var(--ink);">Pesanan Anda sudah terkonfirmasi. Silakan lanjut ke halaman sukses.</p>
                    <div class="pay-actions">
                        <a href="#" class="btn-pay" onclick="event.preventDefault();goSuccess();" style="text-align:center;text-decoration:none;">Lihat Pesanan</a>
                    </div>
                </div>
            @else
                <div class="pay-card">
                    <h2><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" /><line x1="1" y1="10" x2="23" y2="10" /></svg>
                        Ringkasan Pesanan</h2>

                    @foreach ($draft->items as $draftItem)
                        @php $item = $draftItem->barang; @endphp
                        <div class="pay-product">
                            <div class="pp-img">
                                @if ($item && $item->produk && $item->produk->fotoUtama)
                                    <img src="{{ env('BE_URL') . '/storage/' . $item->produk->fotoUtama->foto }}"
                                        alt="" style="width:100%;height:100%;border-radius:8px;object-fit:cover;">
                                @else
                                    {{ $item->produk->brand->nama_brand ?? 'N/A' }}
                                @endif
                            </div>
                            <div class="pp-name">
                                {{ $item->produk->nama_produk ?? 'Produk' }}
                                <div class="pp-variant">{{ $item->nama_barang ?? '' }}</div>
                            </div>
                            <div class="pp-right">
                                <div class="pp-price">Rp{{ number_format($draftItem->harga, 0, ',', '.') }}</div>
                                <div class="pp-qty">{{ $draftItem->qty }}x</div>
                            </div>
                        </div>
                    @endforeach

                    <div class="pay-row" style="margin-top:8px;">
                        <span class="pay-label">Subtotal ({{ $draft->items->sum('qty') }} produk)</span>
                        <span class="pay-value">Rp{{ number_format($draft->subtotal_harga, 0, ',', '.') }}</span>
                    </div>
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
                    <div class="pay-total">
                        <span class="pt-label">Total Bayar</span>
                        <span class="pt-value">Rp{{ number_format($draft->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pay-card">
                    <h2><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg>
                        Selesaikan Pembayaran</h2>
                    <div class="pay-row">
                        <span class="pay-label">Kode Pesanan</span>
                        <span class="pay-value">{{ $draft->kode_penjualan }}</span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">Metode Pembayaran</span>
                        <span class="pay-value">
                            {{ $pembayaran->payment_method === 'bca' ? 'Transfer Bank BCA' : 'Transfer & E-Wallet (Virtual Account, QRIS, GoPay, ShopeePay)' }}
                        </span>
                    </div>
                    <div class="pay-row">
                        <span class="pay-label">Status</span>
                        <span class="pay-value" id="payStatusValue">Menunggu pembayaran</span>
                    </div>
                    <div class="pay-actions" style="margin-top:16px;">
                        <button class="btn-pay" id="btnBayar" onclick="payNow()">Bayar Sekarang</button>
                        <a href="{{ route('dashboard.pesanan') }}" class="btn-ghost">Nanti</a>
                    </div>
                    <div class="pay-status is-pending" id="payStatus" style="display:none;">
                        <span class="spinner"></span>
                        <span id="payStatusText">Menunggu konfirmasi pembayaran...</span>
                    </div>
                    <p class="pay-note">Setelah pembayaran berhasil, status pesanan akan otomatis terverifikasi. Jangan tutup halaman ini sebelum pembayaran selesai.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const CLIENT_KEY = '{{ $clientKey }}';
        const SNAP_TOKEN = '{{ $snapToken ?? '' }}';
        const PEMBAYARAN_ID = {{ $pembayaran->id }};
        // const SNAP_URL = '{{ $isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com' }}/snap/snap.js';
        let payTimer = null;
        let snapOpened = false;

        function showSwalLoading() {
            if (typeof Swal === 'undefined') return;
            Swal.fire({
                title: 'Memproses Pembayaran',
                html: 'Mohon tunggu, kami sedang memverifikasi pembayaran Anda...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading(); },
                showConfirmButton: false,
            });
        }

        verifyWithMidtrans()
        async function verifyWithMidtrans() {
            const orderId = '{{ $midtransOrderId }}';

            try {
                const statusRes = await fetch('{{ url('/checkout/payment') }}' + '/' + encodeURIComponent(orderId) + '/status', {
                    headers: { 'Accept': 'application/json' }
                });
                const statusData = await statusRes.json();
                if (statusData.status === 'paid' && statusData.redirect) {
                    if (payTimer) clearInterval(payTimer);
                    showSwalLoading();
                    window.location.href = statusData.redirect;
                    return true;
                }
            } catch (e) {}

            try {
                const res = await fetch('{{ url('/checkout/payment') }}' + '/' + PEMBAYARAN_ID + '/sync', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.status === 'paid' && data.redirect) {
                    if (payTimer) clearInterval(payTimer);
                    showSwalLoading();
                    window.location.href = data.redirect;
                    return true;
                }
            } catch (e) {}
            return false;
        }

        function pollPaymentStatus() {
            if (payTimer) clearInterval(payTimer);
            let attempts = 0;
            let pollId = '{{ $midtransOrderId }}';

            if (!pollId) {
                stopPolling();
                return;
            }

            function statusUrl(id) {
                return '{{ url('/checkout/payment') }}' + '/' + encodeURIComponent(id) + '/status';
            }

            function stopPolling() {
                clearInterval(payTimer);
                if (typeof Swal !== 'undefined' && Swal.isVisible()) Swal.close();
                const btn = document.getElementById('btnBayar');
                if (btn) { btn.disabled = false; btn.textContent = 'Bayar Sekarang'; }
                const st = document.getElementById('payStatus');
                if (st) {
                    st.className = 'pay-status is-pending';
                    document.getElementById('payStatusText').textContent = 'Pembayaran belum terdeteksi. Klik "Bayar Sekarang" jika belum membayar.';
                }
            }

            payTimer = setInterval(async function() {
                attempts++;
                try {
                    const res = await fetch(statusUrl(pollId), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (data.status === 'paid' && data.redirect) {
                        clearInterval(payTimer);
                        showSwalLoading();
                        window.location.href = data.redirect;
                    } else if (attempts >= 60) {
                        stopPolling();
                    }
                } catch (e) {
                    if (attempts >= 60) stopPolling();
                }
            }, 3000);
        }

        function showWaiting() {
            const st = document.getElementById('payStatus');
            const btn = document.getElementById('btnBayar');
            if (st) {
                st.style.display = 'inline-flex';
                st.className = 'pay-status is-pending';
                document.getElementById('payStatusText').textContent = 'Menunggu konfirmasi pembayaran...';
            }
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Menunggu Pembayaran...';
            }
            showSwalLoading();
            pollPaymentStatus();
        }

        function goSuccess() {
            const st = document.getElementById('payStatus');
            if (st) st.className = 'pay-status is-paid';
            showWaiting();
        }

        @if ($snapToken)
            if (window.snap && snapPay) {
                snapPay();
            }
        @endif

        function payNow() {
            if (!CLIENT_KEY) {
                alert('Metode pembayaran belum dikonfigurasi. Hubungi admin untuk menyelesaikan pembayaran.');
                return;
            }
            if (!SNAP_TOKEN) {
                window.location.reload();
                return;
            }
            if (!window.snap) {
                alert('Terjadi kesalahan memuat pembayaran. Silakan muat ulang halaman.');
                return;
            }
            snapOpened = true;
            window.snap.pay(SNAP_TOKEN, {
                onSuccess: function(result) {
                    showWaiting();
                    verifyWithMidtrans();
                },
                onPending: function(result) {
                    showWaiting();
                },
                onError: function(result) {
                    if (typeof Swal !== 'undefined' && Swal.isVisible()) Swal.close();
                    const btn = document.getElementById('btnBayar');
                    if (btn) { btn.disabled = false; btn.textContent = 'Bayar Sekarang'; }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Pembayaran gagal', text: 'Silakan coba lagi.', confirmButtonColor: '#B8860B' });
                    } else {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    }
                },
                onClose: function() {
                    if (typeof Swal !== 'undefined' && Swal.isVisible()) Swal.close();
                    const btn = document.getElementById('btnBayar');
                    if (btn) { btn.disabled = false; btn.textContent = 'Bayar Sekarang'; }
                }
            });
        }

        function snapPay() {
            window.snap.pay(SNAP_TOKEN, {
                onSuccess: function(result) {
                    showWaiting();
                    verifyWithMidtrans();
                },
                onPending: function(result) {
                    showWaiting();
                },
                onError: function(result) {
                    if (typeof Swal !== 'undefined' && Swal.isVisible()) Swal.close();
                    const btn = document.getElementById('btnBayar');
                    if (btn) { btn.disabled = false; btn.textContent = 'Bayar Sekarang'; }
                },
                onClose: function() {
                    if (typeof Swal !== 'undefined' && Swal.isVisible()) Swal.close();
                    const btn = document.getElementById('btnBayar');
                    if (btn) { btn.disabled = false; btn.textContent = 'Bayar Sekarang'; }
                }
            });
        }
        snapPay()

        // @if ($clientKey)
        //     if (window.snap === undefined) {
        //         const s = document.createElement('script');
        //         s.src = SNAP_URL;
        //         s.dataset.clientKey = CLIENT_KEY;
        //         s.onload = function() {
        //             @if ($snapToken)
        //                 snapPay();
        //             @endif
        //         };
        //         document.body.appendChild(s);
        //     }
        // @endif
    </script>
@endpush
