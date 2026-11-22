<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', $seoMeta['title'] ?: setting('nama_sekolah', 'SMK Abdi Negara Tuban'))</title>

    <meta name="description" content="@yield('description', $seoMeta['description'])">
    <meta name="keywords" content="@yield('keywords', setting('meta_keywords', 'smk abdi negara tuban'))">
    <meta name="author" content="@yield('author', setting('meta_author', ''))">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', $seoMeta['title'])">
    <meta property="og:description" content="@yield('description', $seoMeta['description'])">
    <meta property="og:url" content="@yield('og_url', $seoMeta['url'] ?? url()->current())">
    <meta property="og:image" content="@yield('og_image', $seoMeta['image'] ?? media_url(setting('logo'), 'img/logo-sekolah.svg'))">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ media_url(setting('logo'), 'img/logo-sekolah.svg') }}">

    {{-- Font identitas --}}
    <link href="https://fonts.bunny.net/css?family=open-sans:400,500,600,700,800|raleway:700,800,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tema warna dari pengaturan admin — ditempatkan setelah CSS agar menimpa default --}}
    @php
        $warnaPrimer = theme_color('warna_primer', '#1b5faa');
        $warnaGelap = theme_color('warna_gelap', '#0e3d73');
        $warnaNavy = theme_color('warna_navy', '#072a52');
        $warnaLembut = theme_color('warna_lembut', '#e7f0fa');
    @endphp

    <style>
        :root {
            --primary: {{ $warnaPrimer }};
            --on-primary: {{ contrast_text($warnaPrimer) }};
            --dark: {{ $warnaGelap }};
            --darkest: {{ $warnaNavy }};
            --secondary: {{ $warnaLembut }};
            --light: {{ $warnaLembut }};
            --bs-primary: {{ $warnaPrimer }};
            --bs-primary-rgb: {{ hex_rgb($warnaPrimer) }};
            --bs-link-color: {{ $warnaPrimer }};
            --bs-link-color-rgb: {{ hex_rgb($warnaPrimer) }};
            --bs-link-hover-color: {{ $warnaGelap }};
            --bs-dark: {{ $warnaGelap }};
            --bs-dark-rgb: {{ hex_rgb($warnaGelap) }};
        }
    </style>

    @stack('head')

    @if (filled(setting('google_analytics')))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ setting('google_analytics') }}');
        </script>
    @endif
</head>
<body>
    {{-- Popup PPDB (via admin) --}}
    @include('partials.popup')

    <div id="tema-sekolah" class="position-relative">
        @include('partials.topbar')
        @include('partials.navbar')

        <main>
            @yield('konten')
        </main>

        @include('partials.footer')
    </div>

    <button type="button" id="btn-atas" class="btn-atas" aria-label="Kembali ke atas">
        <i class="fa fa-chevron-up"></i>
    </button>

    @include('partials.wa')

    @stack('scripts')
</body>
</html>
