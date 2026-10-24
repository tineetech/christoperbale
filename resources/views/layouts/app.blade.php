<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ================= SEO : Fondasi ================= --}}
    @php
        $seoSiteUrl    = rtrim((string) (env('SEO_URL') ?: config('app.url')), '/');
        $seoCurrentUrl = request()->url(); // tanpa query string (size/color dsb)
        $seoRootPath   = rtrim(str_replace(url('/'), '', $seoCurrentUrl), '/');
        $seoCanonical  = $seoRootPath === ''
            ? $seoSiteUrl . '/'
            : $seoSiteUrl . $seoRootPath;

        $seoTitles     = trim(strip_tags(trim((string) $__env->getSection('title', ''))));
        if (!$seoTitles) {
            $seoTitles = 'CHRISBALE — Alas Kaki Premium & Sepatu Pria Modern';
        }
        $seoDesc     = trim(strip_tags((string) $__env->getSection('meta_description', '')));
        $seoKeywords = trim(strip_tags((string) $__env->getSection('meta_keywords', '')));
        $seoOgImage  = trim((string) $__env->getSection('og_image', ''));
        $seoNoindex  = (bool) $__env->getSection('noindex');
    @endphp

    <title>{{ $seoTitles }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    @if ($seoKeywords)
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta name="robots" content="{{ $seoNoindex ? 'noindex, follow' : 'index, follow' }}">
    <meta name="theme-color" content="#11100E">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="CHRISBALE">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitles }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    @if ($seoOgImage)
        <meta property="og:image" content="{{ $seoOgImage }}">
        <meta property="og:image:alt" content="{{ $seoTitles }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="{{ $seoOgImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seoTitles }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    @if ($seoOgImage)
        <meta name="twitter:image" content="{{ $seoOgImage }}">
    @endif

    @stack('schema')
    <style>
        :root {
            --bg: #FAFAF8;
            --bg-card: #FFFFFF;
            --ink: #11100E;
            --ink-soft: #4A4642;
            --ink-muted: #8A8580;
            --accent: #B8860B;
            --line: #E6E3DE;
        }
        * { box-sizing: border-box; }
        html { background: var(--bg); }
        body {
            margin: 0;
            padding-top: 100px;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img, svg { display: block; max-width: 100%; }
        button, input, select, textarea { font: inherit; color: inherit; }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/logo-cb6.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="/css/app.css">
    @stack('styles')
</head>

<body>

    @include('components.chatbot')
    @if (Auth::check())
    <script>
        document.documentElement.className = "logged-in";
    </script>
    @else
    <script>
        document.documentElement.className = "guest";
    </script>
    @endif

    @include('components.header', ['active' => request()->path()])
    @include('components.mobile-drawer', ['active' => request()->path()])

    @yield('content')

    @include('components.footer')
    <script src="/js/app.js"></script>
    @stack('scripts')
</body>

</html>
