<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Admin') — {{ setting('nama_sekolah', 'SMK Abdi Negara Tuban') }}</title>

    <link href="https://fonts.bunny.net/css?family=open-sans:400,600,700|raleway:700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

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
</head>
<body>
    <div class="d-flex admin-layout">
        {{-- Sidebar --}}
        <aside class="admin-sidebar d-none d-lg-block position-fixed h-100 overflow-auto">
            <div class="d-flex align-items-center gap-2 mb-4 px-2 mt-2">
                <img src="{{ media_url(setting('logo'), 'img/logo-sekolah.svg') }}" alt="Logo" style="height: 40px;">
            </div>

            <span class="admin-brand d-block px-2 py-1 fs-6">Panel Admin</span>

            <nav class="nav flex-column gap-1 mt-3">
                @include('admin.partials.sidebar-nav')
            </nav>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" target="_blank" class="nav-link"><i class="fa fa-external-link-alt"></i> Lihat Situs</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 border-0 bg-transparent"><i class="fa fa-sign-out-alt"></i> Keluar</button>
                </form>
            </div>
        </aside>

        {{-- Konten utama --}}
        <div class="flex-grow-1 admin-content">
            <header class="admin-topbar d-flex align-items-center justify-content-between p-3 mb-4 sticky-top">
                <div>
                    <button class="btn btn-sm btn-outline-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarPonsel">
                        <i class="fa fa-bars"></i>
                    </button>

                    <small class="text-muted ms-2 d-none d-md-inline">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</small>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                        <i class="fa fa-sign-out-alt me-1"></i>Keluar
                    </button>
                </form>
            </header>

            {{-- Offcanvas sidebar untuk layar kecil --}}
            <div class="offcanvas offcanvas-start admin-sidebar offcanvas-sidebar" tabindex="-1" id="sidebarPonsel">
                <div class="offcanvas-header">
                    <span class="admin-brand d-block w-100">Panel Admin</span>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
                </div>

                <div class="offcanvas-body doc-body d-block pt-0">
                    <nav class="nav flex-column gap-1">
                        @include('admin.partials.sidebar-nav')
                    </nav>
                </div>
            </div>

            <main class="px-3 px-md-4 pb-5">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @yield('konten')
            </main>
        </div>
    </div>

    @yield('skrip')
    @stack('skrip')
</body>
</html>
