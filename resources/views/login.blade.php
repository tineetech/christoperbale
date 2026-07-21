@extends('layouts.app')

@section('title', 'Login — CHRISBALE')


@push('styles')
    <style>
        /* =============================================
       LOGIN PAGE
       ============================================= */

        .login-page {
            min-height: 100vh;
            background: var(--bg);
        }

        /* Grid Layout */
        .login-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* =============================================
       LEFT SIDE
       ============================================= */

        .login-left {
            position: relative;
            overflow: hidden;
            background: #0F0D0B;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: end;
        }

        .login-left-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .login-left-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: grayscale(30%);
            opacity: 0.45;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .login-left:hover .login-left-bg img {
            transform: scale(1);
        }

        .login-left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                    rgba(15, 13, 11, 0.55) 0%,
                    rgba(15, 13, 11, 0.3) 40%,
                    rgba(15, 13, 11, 0.85) 100%);
            z-index: 1;
        }

        /* Logo kiri atas */
        .login-left-logo {
            position: relative;
            z-index: 2;
            padding: 36px 44px;
        }

        .login-left-logo .logo {
            color: #fff;
            font-size: 22px;
            letter-spacing: 0.1em;
        }

        .login-left-logo .logo span {
            color: var(--accent-light);
        }

        /* Konten kiri bawah */
        .login-left-content {
            position: relative;
            z-index: 2;
            padding: 44px;
        }

        .login-left-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 10.5px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 18px;
            backdrop-filter: blur(4px);
        }

        .login-left-content h2 {
            font-size: clamp(22px, 2.8vw, 34px);
            color: #fff;
            line-height: 1.2;
            margin-bottom: 12px;
            letter-spacing: 0.01em;
            text-shadow: 0 2px 16px rgba(0, 0, 0, 0.5);
        }

        .login-left-content h2 em {
            color: var(--accent-light);
            font-style: normal;
        }

        .login-left-content>p {
            color: rgba(255, 255, 255, 0.55);
            font-size: 13.5px;
            line-height: 1.75;
            margin-bottom: 28px;
            max-width: 360px;
        }

        /* Stats */
        .login-left-stats {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
            padding: 18px 22px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            backdrop-filter: blur(8px);
        }

        .login-stat {
            text-align: center;
            flex: 1;
        }

        .login-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
        }

        .login-stat-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.45);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .login-stat-divider {
            width: 1px;
            height: 36px;
            background: rgba(255, 255, 255, 0.12);
            flex-shrink: 0;
        }

        /* Testimonial */
        .login-left-testi {
            padding: 20px 22px;
            background: rgba(184, 134, 11, 0.12);
            border: 1px solid rgba(184, 134, 11, 0.25);
            border-radius: var(--radius);
            backdrop-filter: blur(6px);
        }

        .login-testi-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 10px;
        }

        .login-testi-stars svg {
            width: 12px;
            height: 12px;
            fill: var(--accent-light);
            color: var(--accent-light);
        }

        .login-left-testi p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.65;
            font-style: italic;
            font-family: 'Playfair Display', serif;
            margin-bottom: 8px;
        }

        .login-testi-author {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 0.04em;
        }

        /* =============================================
       RIGHT SIDE
       ============================================= */

        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            background: var(--bg);
            min-height: 100vh;
            overflow-y: auto;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 440px;
        }

        /* Mobile Logo - hidden by default */
        .login-mobile-logo {
            display: none;
            text-align: center;
            margin-bottom: 28px;
        }

        .login-mobile-logo .logo {
            font-size: 24px;
            letter-spacing: 0.1em;
            color: var(--ink);
        }

        .login-mobile-logo .logo span {
            color: var(--accent);
        }

        /* Form Header */
        .login-form-header {
            margin-bottom: 28px;
        }

        .login-form-header .eyebrow {
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--ink-muted);
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
        }

        .login-form-header h1 {
            font-size: clamp(22px, 2.5vw, 30px);
            color: var(--ink);
            margin-bottom: 10px;
            letter-spacing: 0.01em;
        }

        .login-form-header p {
            font-size: 13.5px;
            color: var(--ink-soft);
        }

        .login-link {
            color: var(--accent);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
            border-bottom: 1px solid rgba(184, 134, 11, 0.3);
            padding-bottom: 1px;
        }

        .login-link:hover {
            color: var(--accent-dark);
            border-bottom-color: var(--accent-dark);
        }

        /* Alerts */
        .login-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .login-alert svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .login-alert-error {
            background: rgba(192, 57, 43, 0.08);
            border: 1px solid rgba(192, 57, 43, 0.2);
            color: #C0392B;
        }

        .login-alert-error svg {
            stroke: #C0392B;
        }

        .login-alert-info {
            background: rgba(184, 134, 11, 0.07);
            border: 1px solid rgba(184, 134, 11, 0.2);
            color: var(--ink-soft);
        }

        .login-alert-info svg {
            stroke: var(--accent);
        }

        .login-alert-success {
            background: rgba(39, 174, 96, 0.08);
            border: 1px solid rgba(39, 174, 96, 0.2);
            color: #27AE60;
        }

        .login-alert-success svg {
            stroke: #27AE60;
        }

        /* Form */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* Field */
        .login-field {
            margin-bottom: 18px;
        }

        .login-field label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--ink-soft);
            margin-bottom: 8px;
            letter-spacing: 0.03em;
        }

        .login-forgot {
            font-size: 12px;
            font-weight: 400;
            color: var(--accent);
            text-decoration: none;
            transition: color 0.2s;
            border-bottom: 1px solid rgba(184, 134, 11, 0.3);
            padding-bottom: 1px;
        }

        .login-forgot:hover {
            color: var(--accent-dark);
        }

        /* Input Wrap */
        .login-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            transition: border-color 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }

        .login-input-wrap.focused {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.1);
        }

        .login-input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 100%;
            flex-shrink: 0;
            pointer-events: none;
            padding: 0 4px;
        }

        .login-input-icon svg {
            width: 16px;
            height: 16px;
            stroke: var(--ink-muted);
            flex-shrink: 0;
        }

        .login-input-wrap.focused .login-input-icon svg {
            stroke: var(--accent);
        }

        .login-input-wrap input {
            flex: 1;
            border: none;
            outline: none;
            padding: 13px 12px 13px 0;
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background: transparent;
            width: 100%;
        }

        .login-input-wrap input::placeholder {
            color: var(--ink-muted);
            font-weight: 300;
        }

        /* Eye Toggle */
        .login-eye-btn {
            width: 44px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
            padding: 0 10px;
            color: var(--ink-muted);
            transition: color 0.2s;
        }

        .login-eye-btn:hover {
            color: var(--accent);
        }

        .login-eye-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Field Error */
        .login-field-error {
            display: block;
            font-size: 11.5px;
            color: #C0392B;
            margin-top: 6px;
            letter-spacing: 0.02em;
        }

        /* Remember Me */
        .login-remember {
            margin-bottom: 22px;
        }

        .login-checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .login-checkbox-label input[type="checkbox"] {
            display: none;
        }

        .login-checkbox-custom {
            width: 18px;
            height: 18px;
            border: 1.5px solid var(--line);
            border-radius: 4px;
            background: var(--bg-card);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            position: relative;
        }

        .login-checkbox-label input:checked+.login-checkbox-custom {
            background: var(--accent);
            border-color: var(--accent);
        }

        .login-checkbox-label input:checked+.login-checkbox-custom::after {
            content: '';
            position: absolute;
            width: 5px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg) translate(-1px, -1px);
        }

        .login-checkbox-text {
            font-size: 13px;
            color: var(--ink-soft);
        }

        /* Submit Button */
        .login-submit-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 15px 24px;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .login-submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        }

        .login-submit-btn:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(184, 134, 11, 0.3);
        }

        .login-submit-btn:active {
            transform: translateY(0);
        }

        .login-submit-btn svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        /* Divider */
        .login-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        .login-divider span {
            font-size: 11.5px;
            color: var(--ink-muted);
            white-space: nowrap;
            letter-spacing: 0.06em;
        }

        /* Social Buttons */
        .login-socials {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .login-socials a {
            flex: 1;
        }

        .login-social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border: 1.5px solid var(--line);
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            font-size: 13px;
            font-weight: 400;
            color: var(--ink-soft);
            cursor: pointer;
            transition: all 0.2s;
        }

        .login-social-btn:hover {
            border-color: var(--ink-muted);
            background: var(--bg);
            color: var(--ink);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .login-social-btn {
            text-decoration: none;
        }

        .login-social-btn svg {
            flex-shrink: 0;
        }

        /* Form Footer */
        .login-form-footer {
            text-align: center;
            padding-top: 8px;
            padding-bottom: 20px;
            border-top: 1px solid var(--line-soft);
            margin-top: 4px;
        }

        .login-form-footer p {
            font-size: 11.5px;
            color: var(--ink-muted);
            line-height: 1.65;
        }

        /* Trust Badges */
        .login-trust {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding-top: 16px;
            flex-wrap: wrap;
        }

        .login-trust-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: var(--ink-muted);
            letter-spacing: 0.04em;
        }

        .login-trust-item svg {
            width: 13px;
            height: 13px;
            stroke: var(--ink-muted);
            flex-shrink: 0;
        }

        .login-trust-sep {
            width: 1px;
            height: 14px;
            background: var(--line);
        }

        /* =============================================
       RESPONSIVE
       ============================================= */

        /* Tablet */
        @media (max-width: 1024px) {
            .login-left-content {
                padding: 36px;
            }

            .login-left-logo {
                padding: 28px 36px;
            }

            .login-left-content h2 {
                font-size: 26px;
            }
        }

        /* Mobile - sembunyikan panel kiri */
        @media (max-width: 768px) {
            .login-grid {
                grid-template-columns: 1fr;
                min-height: 100vh;
            }

            /* Sembunyikan panel kiri */
            .login-left {
                display: none;
            }

            /* Panel kanan penuh */
            .login-right {
                min-height: 100vh;
                padding: 32px 20px;
                align-items: flex-start;
                padding-top: 48px;
            }

            .login-form-wrap {
                max-width: 100%;
            }

            /* Tampilkan mobile logo */
            .login-mobile-logo {
                display: block;
            }

            .login-form-header {
                text-align: center;
            }

            .login-form-header p {
                text-align: center;
            }

            .login-submit-btn {
                padding: 16px 24px;
            }

            .login-trust {
                gap: 12px;
            }

            .login-trust-sep {
                display: none;
            }
        }

        /* Small Mobile */
        @media (max-width: 400px) {
            .login-right {
                padding: 32px 16px;
                padding-top: 40px;
            }

            .login-socials {
                grid-template-columns: 1fr;
            }

            .login-form-header h1 {
                font-size: 22px;
            }

            .login-stat-num {
                font-size: 18px;
            }

            .login-trust {
                flex-direction: column;
                gap: 8px;
            }
        }

        /* Landscape Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .login-right {
                padding: 24px 20px;
                align-items: center;
            }
        }

        /* Large Screen */
        @media (min-width: 1440px) {
            .login-left-content {
                padding: 52px;
            }

            .login-left-logo {
                padding: 40px 52px;
            }

            .login-form-wrap {
                max-width: 480px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="login-page">
        <div class="login-grid">

            <!-- LEFT SIDE - IMAGE -->
            <div class="login-left">
                <div class="login-left-bg">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=900&q=80&auto=format&fit=crop"
                        alt="CHRISBALE Premium Footwear">
                </div>
                <div class="login-left-overlay"></div>

                <!-- Logo di atas -->
                {{-- <div class="login-left-logo">
        <a href="/" class="logo">CHRIS<span>BALE</span></a>
      </div> --}}

                <!-- Quote di bawah -->
                <div class="login-left-content">
                    <h2>Melangkah dengan <em>Gaya</em> — Setiap Hari</h2>
                    <p>Bergabunglah dengan ribuan pelanggan yang telah menemukan alas kaki impian mereka di CHRISBALE.</p>

                    <div class="login-left-stats">
                        <div class="login-stat">
                            <div class="login-stat-num">50K+</div>
                            <div class="login-stat-label">Pelanggan</div>
                        </div>
                        <div class="login-stat-divider"></div>
                        <div class="login-stat">
                            <div class="login-stat-num">500+</div>
                            <div class="login-stat-label">Koleksi</div>
                        </div>
                        <div class="login-stat-divider"></div>
                        <div class="login-stat">
                            <div class="login-stat-num">4.9</div>
                            <div class="login-stat-label">Rating</div>
                        </div>
                    </div>

                    <!-- Testimonial kecil -->
                    <div class="login-left-testi">
                        <div class="login-testi-stars">
                            <svg viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <svg viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                        </div>
                        <p>"Kualitas terbaik yang pernah saya beli. CHRISBALE benar-benar berbeda!"</p>
                        <div class="login-testi-author">— Sarah J., Pembeli Terverifikasi</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE - FORM -->
            <div class="login-right">
                <div class="login-form-wrap">

                    <!-- Mobile Logo -->
                    {{-- <div class="login-mobile-logo">
          <a href="/" class="logo">CHRIS<span>BALE</span></a>
        </div> --}}

                    <!-- Header Form -->
                    <div class="login-form-header">
                        <span class="eyebrow">Selamat Datang Kembali</span>
                        <h1>Masuk ke Akun Anda</h1>
                        <p>Belum punya akun? <a href="/register" class="login-link">Daftar Sekarang</a></p>
                    </div>

                    <!-- Success Alert -->
                    @if (session('success'))
                        <div class="login-alert login-alert-success">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Error Alert -->
                    @if (session('error'))
                        <div class="login-alert login-alert-error">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @elseif ($errors->any())
                        <div class="login-alert login-alert-error">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form class="login-form" action="/login" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="login-field">
                            <label for="email">Alamat Email</label>
                            <div class="login-input-wrap">
                                <span class="login-input-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <polyline points="22,6 12,13 2,6" />
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email" placeholder="nama@email.com"
                                    value="{{ old('email') }}" autocomplete="email" required>
                            </div>
                            @error('email')
                                <span class="login-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="login-field">
                            <label for="password">
                                Password
                                <a href="/forgot-password" class="login-forgot">Lupa Password?</a>
                            </label>
                            <div class="login-input-wrap">
                                <span class="login-input-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </span>
                                <input type="password" id="password" name="password" placeholder="Masukkan password"
                                    autocomplete="current-password" required>
                                <button type="button" class="login-eye-btn" id="togglePassword"
                                    aria-label="Tampilkan password">
                                    <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg id="eyeOffIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" style="display:none;">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                        <line x1="1" y1="1" x2="23" y2="23" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="login-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="login-remember">
                            <label class="login-checkbox-label">
                                <input type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <span class="login-checkbox-custom"></span>
                                <span class="login-checkbox-text">Ingat saya selama 30 hari</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="login-submit-btn">
                            <span>Masuk Sekarang</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>

                        <!-- Divider -->
                        <div class="login-divider">
                            <span>atau lanjutkan dengan</span>
                        </div>

                        <!-- Social Login -->
                        <div class="login-socials">
                            <a href="/auth/google" class="login-social-btn">
                                <svg viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="#4285F4"
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="#34A853"
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="#FBBC05"
                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="#EA4335"
                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                <span>Google</span>
                            </a>
                        </div>

                    </form>

                    <!-- Footer Form -->
                    <div class="login-form-footer">
                        <p>Dengan masuk, Anda menyetujui <a href="/terms" class="login-link">Syarat & Ketentuan</a> dan
                            <a href="/privacy" class="login-link">Kebijakan Privasi</a> kami.</p>
                    </div>

                    <!-- Trust Badges -->
                    <div class="login-trust">
                        <div class="login-trust-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <span>SSL Terenkripsi</span>
                        </div>
                        <div class="login-trust-sep"></div>
                        <div class="login-trust-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                            <span>Data Aman</span>
                        </div>
                        <div class="login-trust-sep"></div>
                        <div class="login-trust-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span>Terverifikasi</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function() {
            // Toggle Password Visibility
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    eyeIcon.style.display = isPassword ? 'none' : 'block';
                    eyeOffIcon.style.display = isPassword ? 'block' : 'none';
                });
            }

            // Input focus animation
            const inputs = document.querySelectorAll('.login-input-wrap input');
            inputs.forEach(function(input) {
                input.addEventListener('focus', function() {
                    this.closest('.login-input-wrap').classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    this.closest('.login-input-wrap').classList.remove('focused');
                });
            });

            // Login Form Submit
            const loginForm = document.querySelector('.login-form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const submitBtn = loginForm.querySelector('.login-submit-btn');
                    submitBtn.innerHTML = '<span>Memproses...</span>';
                    submitBtn.disabled = true;
                });
            }
        })();
    </script>
@endpush
