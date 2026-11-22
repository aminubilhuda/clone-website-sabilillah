<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Panel — {{ setting('nama_sekolah', 'SMK Abdi Negara Tuban') }}</title>

    <link href="https://fonts.bunny.net/css?family=open-sans:400,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    @php
        $warnaPrimer = theme_color('warna_primer', '#1b5faa');
        $warnaGelap = theme_color('warna_gelap', '#0e3d73');
    @endphp

    <style>
        :root {
            --primary: {{ $warnaPrimer }};
            --on-primary: {{ contrast_text($warnaPrimer) }};
            --dark: {{ $warnaGelap }};
            --bs-primary: {{ $warnaPrimer }};
            --bs-primary-rgb: {{ hex_rgb($warnaPrimer) }};
            --bs-link-color: {{ $warnaPrimer }};
            --bs-link-color-rgb: {{ hex_rgb($warnaPrimer) }};
            --bs-dark: {{ $warnaGelap }};
            --bs-dark-rgb: {{ hex_rgb($warnaGelap) }};
        }
    </style>
</head>
<body class="login-halaman">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card border-0 shadow">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <img src="{{ media_url(setting('logo'), 'img/logo-sekolah.svg') }}" alt="Logo" class="mb-3" style="height: 62px;">
                            <h4 class="mb-0">Panel Admin</h4>
                            <small class="text-muted">{{ setting('nama_sekolah', 'SMK Abdi Negara Tuban') }}</small>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login.attempt') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="login" class="form-label">Email atau Username</label>
                                <input id="login" type="text" name="login" value="{{ old('login') }}"
                                    class="form-control @error('login') is-invalid @enderror" required autofocus autocomplete="username">

                                @error('login')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required autofocus autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-pill-ppdb">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
