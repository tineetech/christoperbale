@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan — CHRISBALE')

@section('content') <style>
        .error-page {
            min-height: calc(100vh - 100px);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 15% 25%, rgba(184, 134, 11, 0.08), transparent 28%),
                radial-gradient(circle at 85% 75%, rgba(184, 134, 11, 0.07), transparent 30%),
                var(--bg);
            padding: 70px 24px;
        }

        /* Decorative background */
        .error-bg-number {
            position: absolute;
            inset: 50% auto auto 50%;
            transform: translate(-50%, -50%);
            font-family: 'Playfair Display', serif;
            font-size: clamp(220px, 38vw, 560px);
            font-weight: 700;
            line-height: 1;
            color: transparent;
            -webkit-text-stroke: 1px rgba(184, 134, 11, 0.09);
            user-select: none;
            pointer-events: none;
            white-space: nowrap;
        }

        .error-orbit {
            position: absolute;
            width: 520px;
            height: 520px;
            border: 1px solid rgba(184, 134, 11, 0.12);
            border-radius: 50%;
            animation: errorRotate 25s linear infinite;
        }

        .error-orbit::before,
        .error-orbit::after {
            content: '';
            position: absolute;
            width: 7px;
            height: 7px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 18px rgba(184, 134, 11, 0.65);
        }

        .error-orbit::before {
            top: 38px;
            left: 50%;
        }

        .error-orbit::after {
            bottom: 80px;
            right: 25px;
            width: 4px;
            height: 4px;
            opacity: 0.6;
        }

        @keyframes errorRotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .error-decoration {
            position: absolute;
            border-radius: 50%;
            filter: blur(1px);
            pointer-events: none;
        }

        .error-decoration.one {
            width: 180px;
            height: 180px;
            top: 12%;
            left: 8%;
            border: 1px solid rgba(184, 134, 11, 0.08);
            animation: floatError 5s ease-in-out infinite;
        }

        .error-decoration.two {
            width: 100px;
            height: 100px;
            right: 12%;
            top: 20%;
            border: 1px solid rgba(184, 134, 11, 0.12);
            animation: floatError 6s ease-in-out infinite reverse;
        }

        .error-decoration.three {
            width: 140px;
            height: 140px;
            bottom: 10%;
            left: 15%;
            border: 1px solid rgba(184, 134, 11, 0.07);
            animation: floatError 7s ease-in-out infinite;
        }

        @keyframes floatError {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-18px) rotate(8deg);
            }
        }

        .error-content {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            gap: 70px;
            align-items: center;
        }

        .error-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 360px;
        }

        .error-404 {
            position: relative;
            font-family: 'Playfair Display', serif;
            font-size: clamp(130px, 17vw, 220px);
            line-height: 0.8;
            font-weight: 700;
            letter-spacing: -0.08em;
            color: var(--ink);
            z-index: 2;
            text-shadow: 10px 12px 0 rgba(184, 134, 11, 0.12);
            animation: errorAppear 0.8s ease both;
        }

        .error-404 span {
            color: var(--accent);
        }

        .error-circle {
            position: absolute;
            width: 290px;
            height: 290px;
            border: 1px solid var(--line);
            border-radius: 50%;
            z-index: 1;
        }

        .error-circle::before {
            content: '';
            position: absolute;
            inset: 20px;
            border: 1px dashed rgba(184, 134, 11, 0.25);
            border-radius: 50%;
            animation: errorRotate 18s linear infinite reverse;
        }

        .error-circle::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            background: var(--accent);
            border-radius: 50%;
            top: 28px;
            right: 52px;
            box-shadow: 0 0 0 8px rgba(184, 134, 11, 0.08);
        }

        .error-copy {
            animation: errorSlide 0.8s 0.15s ease both;
        }

        .error-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.22em;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .error-eyebrow::before {
            content: '';
            width: 32px;
            height: 1px;
            background: var(--accent);
        }

        .error-copy h1 {
            font-size: clamp(30px, 4vw, 48px);
            line-height: 1.12;
            color: var(--ink);
            margin-bottom: 18px;
            letter-spacing: -0.02em;
        }

        .error-copy h1 em {
            color: var(--accent);
            font-style: normal;
        }

        .error-copy>p {
            max-width: 480px;
            color: var(--ink-soft);
            font-size: 14px;
            line-height: 1.85;
            margin: 0 0 28px;
        }

        .error-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .error-home-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 24px;
            background: var(--accent);
            color: #fff;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            transition:
                background .25s ease,
                transform .25s ease,
                box-shadow .25s ease;
        }

        .error-home-btn:hover {
            background: var(--accent-dark);
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(184, 134, 11, 0.25);
        }

        .error-home-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            transition: transform .25s ease;
        }

        .error-home-btn:hover svg {
            transform: translateX(-3px);
        }

        .error-back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 22px;
            border: 1px solid var(--line);
            color: var(--ink-soft);
            background: rgba(255, 255, 255, 0.5);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.05em;
            transition:
                border-color .25s ease,
                color .25s ease,
                background .25s ease,
                transform .25s ease;
        }

        .error-back-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #fff;
            transform: translateY(-3px);
        }

        .error-back-btn svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }

        .error-help {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--line-soft);
            max-width: 480px;
        }

        .error-help-icon {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            border-radius: 50%;
            color: var(--accent);
            background: var(--bg-card);
        }

        .error-help-icon svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.7;
        }

        .error-help p {
            margin: 0;
            color: var(--ink-muted);
            font-size: 11.5px;
            line-height: 1.6;
        }

        .error-help strong {
            color: var(--ink-soft);
            font-weight: 500;
        }

        @keyframes errorAppear {
            from {
                opacity: 0;
                transform: scale(.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes errorSlide {
            from {
                opacity: 0;
                transform: translateX(25px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media(max-width: 800px) {
            .error-page {
                min-height: calc(100vh - 102px);
                padding: 60px 20px;
            }

            .error-content {
                grid-template-columns: 1fr;
                gap: 30px;
                text-align: center;
            }

            .error-visual {
                min-height: 230px;
            }

            .error-404 {
                font-size: clamp(110px, 30vw, 180px);
            }

            .error-circle {
                width: 220px;
                height: 220px;
            }

            .error-eyebrow {
                justify-content: center;
            }

            .error-copy>p {
                margin-left: auto;
                margin-right: auto;
            }

            .error-actions {
                justify-content: center;
            }

            .error-help {
                margin-left: auto;
                margin-right: auto;
                text-align: left;
            }

            .error-bg-number {
                font-size: 55vw;
            }

            .error-orbit {
                width: 350px;
                height: 350px;
            }
        }

        @media(max-width: 480px) {
            .error-page {
                padding: 45px 16px;
            }

            .error-visual {
                min-height: 190px;
            }

            .error-404 {
                font-size: 105px;
            }

            .error-circle {
                width: 180px;
                height: 180px;
            }

            .error-circle::before {
                inset: 14px;
            }

            .error-actions {
                flex-direction: column;
                width: 100%;
            }

            .error-home-btn,
            .error-back-btn {
                width: 100%;
            }

            .error-help {
                margin-top: 24px;
            }

            .error-decoration.one {
                left: -70px;
            }

            .error-decoration.two {
                right: -30px;
            }
        }

        @media(prefers-reduced-motion: reduce) {

            .error-orbit,
            .error-circle::before,
            .error-decoration.one,
            .error-decoration.two,
            .error-decoration.three,
            .error-404,
            .error-copy {
                animation: none;
            }

            .error-404,
            .error-copy {
                opacity: 1;
                transform: none;
            }
        }
    </style>

    <div class="error-page">

        {{-- Background decorations --}}
        <div class="error-bg-number">404</div>
        <div class="error-orbit"></div>

        <div class="error-decoration one"></div>
        <div class="error-decoration two"></div>
        <div class="error-decoration three"></div>

        <div class="error-content">

            {{-- Visual --}}
            <div class="error-visual">
                <div class="error-circle"></div>

                <div class="error-404">
                    4<span>0</span>4
                </div>
            </div>

            {{-- Content --}}
            <div class="error-copy">

                <div class="error-eyebrow">
                    Error 404
                </div>

                <h1>
                    Sepertinya halaman ini
                    <em>tersesat.</em>
                </h1>

                <p>
                    Halaman yang Anda cari mungkin sudah dipindahkan,
                    dihapus, atau alamat yang Anda masukkan tidak tersedia.
                    Jangan khawatir, Anda masih bisa kembali menjelajahi
                    koleksi dan pengalaman terbaik dari CHRISBALE.
                </p>

                <div class="error-actions">

                    <a href="{{ url('/') }}" class="error-home-btn">
                        <svg viewBox="0 0 24 24">
                            <path d="M19 12H5" />
                            <path d="M12 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Beranda
                    </a>

                    <a href="javascript:history.back()" class="error-back-btn">
                        Halaman Sebelumnya
                    </a>

                </div>

                <div class="error-help">

                    <div class="error-help-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M9.5 9a2.5 2.5 0 1 1 4.2 1.8c-.9.7-1.7 1.1-1.7 2.2" />
                            <path d="M12 16.5h.01" />
                        </svg>
                    </div>

                    <p>
                        <strong>Masih mencari sesuatu?</strong><br>
                        Coba kembali ke halaman sebelumnya atau mulai
                        dari beranda untuk menemukan apa yang Anda cari.
                    </p>

                </div>

            </div>

        </div>

    </div>

@endsection
