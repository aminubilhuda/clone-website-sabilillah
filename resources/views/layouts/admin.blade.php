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

    <div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-labelledby="mediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaPickerModalLabel">
                        <i class="fa fa-images me-2 text-primary"></i>Pilih dari Media yang Pernah Diunggah
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="search" id="mediaPickerSearch" class="form-control form-control-sm" placeholder="Cari nama file media...">
                    </div>
                    <div id="mediaPickerGrid" class="row g-2"></div>
                    <div id="mediaPickerEmpty" class="text-center text-muted py-4 d-none">
                        Belum ada media yang pernah diunggah. Silakan unggah gambar dari komputer terlebih dahulu.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalEl = document.getElementById('mediaPickerModal');
            if (!modalEl) return;

            const gridEl = document.getElementById('mediaPickerGrid');
            const emptyEl = document.getElementById('mediaPickerEmpty');
            const searchEl = document.getElementById('mediaPickerSearch');
            const endpoint = @json(route('admin.media.index'));
            let activePicker = null;
            let cachedItems = null;
            let backdropEl = null;

            const openModal = async (picker) => {
                activePicker = picker;
                if (searchEl) searchEl.value = '';

                if (!backdropEl) {
                    backdropEl = document.createElement('div');
                    backdropEl.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdropEl);
                }

                modalEl.style.display = 'block';
                modalEl.classList.add('show');
                modalEl.removeAttribute('aria-hidden');
                modalEl.setAttribute('aria-modal', 'true');
                document.body.classList.add('modal-open');

                if (cachedItems === null) {
                    gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">Memuat media...</div>';
                    emptyEl.classList.add('d-none');
                    try {
                        const res = await fetch(endpoint, { headers: { 'Accept': 'application/json' } });
                        const data = await res.json();
                        cachedItems = Array.isArray(data.items) ? data.items : [];
                    } catch {
                        cachedItems = [];
                    }
                }

                renderItems(cachedItems);
            };

            const closeModal = () => {
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                modalEl.removeAttribute('aria-modal');
                document.body.classList.remove('modal-open');

                if (backdropEl) {
                    backdropEl.remove();
                    backdropEl = null;
                }
            };

            const renderItems = (items) => {
                const q = (searchEl?.value || '').trim().toLowerCase();
                const filtered = q ? items.filter(i => i.name.toLowerCase().includes(q) || i.path.toLowerCase().includes(q)) : items;

                gridEl.innerHTML = '';
                emptyEl.classList.toggle('d-none', filtered.length > 0);

                filtered.forEach((item) => {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-sm-4 col-md-3';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-outline-secondary w-100 p-2 text-start h-100 d-flex flex-column';

                    const img = document.createElement('img');
                    img.src = item.url;
                    img.alt = item.name;
                    img.className = 'rounded mb-2 w-100 object-fit-cover';
                    img.style.height = '96px';

                    const label = document.createElement('span');
                    label.className = 'small text-truncate w-100 text-dark';
                    label.textContent = item.name;

                    btn.append(img, label);
                    btn.addEventListener('click', () => {
                        if (!activePicker) return;
                        const fileInput = activePicker.querySelector('[data-media-file]');
                        const existingInput = activePicker.querySelector('[data-media-existing]');
                        const previewWrap = activePicker.querySelector('[data-media-preview-wrap]');
                        const previewImg = activePicker.querySelector('[data-media-preview]');
                        const badge = activePicker.querySelector('[data-media-badge]');

                        if (fileInput) fileInput.value = '';
                        if (existingInput) existingInput.value = item.path;
                        if (previewImg) previewImg.src = item.url;
                        if (previewWrap) previewWrap.style.removeProperty('display');
                        if (badge) {
                            badge.textContent = 'Dari media: ' + item.name;
                            badge.classList.remove('d-none');
                        }

                        closeModal();
                    });

                    col.appendChild(btn);
                    gridEl.appendChild(col);
                });
            };

            document.addEventListener('click', (e) => {
                const openBtn = e.target.closest('[data-media-open]');
                if (openBtn) {
                    openModal(openBtn.closest('[data-media-picker]'));
                    return;
                }

                if (e.target.closest('[data-bs-dismiss="modal"]') || e.target === modalEl) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modalEl.classList.contains('show')) {
                    closeModal();
                }
            });

            document.addEventListener('change', (e) => {
                const fileInput = e.target.closest('[data-media-file]');
                if (!fileInput) return;
                const picker = fileInput.closest('[data-media-picker]');
                const existingInput = picker?.querySelector('[data-media-existing]');
                const badge = picker?.querySelector('[data-media-badge]');
                const previewWrap = picker?.querySelector('[data-media-preview-wrap]');
                const previewImg = picker?.querySelector('[data-media-preview]');

                if (existingInput) existingInput.value = '';
                if (fileInput.files && fileInput.files[0]) {
                    if (previewImg) previewImg.src = URL.createObjectURL(fileInput.files[0]);
                    if (previewWrap) previewWrap.style.removeProperty('display');
                    if (badge) {
                        badge.textContent = 'Dari komputer: ' + fileInput.files[0].name;
                        badge.classList.remove('d-none');
                    }
                }
            });

            searchEl?.addEventListener('input', () => {
                if (cachedItems) renderItems(cachedItems);
            });
        });
    </script>

    @yield('skrip')
    @stack('skrip')
</body>
</html>
