@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('konten')
    <h1 class="h3 mb-1">Pengaturan Situs</h1>
    <p class="text-muted mb-4">Identitas sekolah, tema warna, kanal komunikasi, dan banner PPDB.</p>

    @php
        $judulGrup = [
            'umum' => 'Identitas Sekolah',
            'beranda' => 'Beranda (Teks & Statistik)',
            'tampilan' => 'Tema Warna',
            'halaman' => 'Teks Halaman Lain',
            'ppdb' => 'Halaman & Banner PPDB',
        ];

        $defaultWarna = [
            'warna_primer' => '#1b5faa',
            'warna_gelap' => '#0e3d73',
            'warna_navy' => '#072a52',
            'warna_lembut' => '#e7f0fa',
        ];

        $fieldPenuh = ['nama_sekolah', 'tentang', 'alamat', 'gmaps_embed', 'meta_description'];
    @endphp

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Logo Sekolah</h5>

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <img src="{{ media_url(setting('logo'), 'img/logo-sekolah.svg') }}" alt="Logo {{ setting('nama_sekolah') }}"
                                class="border rounded bg-white p-1" style="height: 64px; width: auto;">

                            <div class="flex-grow-1" style="min-width: 240px;">
                                <input type="file" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">

                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted">
                                    SVG, PNG, JPG, atau WebP — maksimal 5 MB. Disarankan latar transparan dan rasio mendatar.
                                    Logo ini dipakai di navbar, footer, panel admin, dan favicon.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Gambar Sekilas Beranda</h5>

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <img src="{{ media_url(setting('sekilas_gambar'), 'img/sekilas-sekolah.svg') }}" alt="Gambar sekilas"
                                class="border rounded bg-white p-1" style="height: 90px; width: auto; max-width: 220px; object-fit: cover;">

                            <div class="flex-grow-1" style="min-width: 240px;">
                                <input type="file" name="sekilas_gambar" accept="image/*" class="form-control @error('sekilas_gambar') is-invalid @enderror">

                                @error('sekilas_gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted">
                                    Foto yang tampil di section "Sekilas Sekolah" pada beranda. Maksimal 5 MB.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Gambar Banner PPDB</h5>

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <img src="{{ media_url(setting('banner_image'), 'img/banner-ppdb.svg') }}" alt="Banner PPDB"
                                class="border rounded bg-white p-1" style="height: 70px; width: auto; max-width: 240px; object-fit: cover;">

                            <div class="flex-grow-1" style="min-width: 240px;">
                                <input type="file" name="banner_image_file" accept="image/*" class="form-control @error('banner_image_file') is-invalid @enderror">

                                @error('banner_image_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted">Gambar strip banner PPDB di beranda. Maksimal 5 MB.</small>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($fields as $group => $groupFields)
                    <div class="card admin-card mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="mb-0 text-uppercase" style="letter-spacing: 1px; font-size: .95rem;">{{ $judulGrup[$group] ?? ucfirst($group) }}</h5>
                        </div>

                        <div class="card-body px-4 pb-4">
                            <div class="row g-3">
                                @foreach ($groupFields as $key => [$label, $tipe])
                                    @php
                                        $nilai = old($group . '::' . $key, strval(setting($key, $defaultWarna[$key] ?? '')));
                                        $isWarna = $tipe === 'color';
                                        $warnaValid = $isWarna && preg_match('/^#[0-9a-fA-F]{6}$/', $nilai) === 1;

                                        if ($isWarna) {
                                            $nilai = $warnaValid ? $nilai : ($defaultWarna[$key] ?? '#1b5faa');
                                        }
                                    @endphp

                                    <div class="{{ ($isWarna || ! in_array($key, $fieldPenuh, true)) ? 'col-md-6' : 'col-12' }}">
                                        <label class="form-label" for="{{ $group }}-{{ $key }}">{{ $label }}</label>

                                        @if ($isWarna)
                                            <div class="d-flex align-items-center gap-2 color-pair">
                                                <input type="color"
                                                    id="picker-{{ $group }}-{{ $key }}"
                                                    value="{{ $nilai }}"
                                                    class="form-control form-control-color"
                                                    aria-label="Pilih {{ $label }}">

                                                <input type="text"
                                                    id="{{ $group }}-{{ $key }}"
                                                    name="{{ $group }}::{{ $key }}"
                                                    value="{{ $nilai }}"
                                                    maxlength="7"
                                                    class="form-control form-control-sm font-monospace @error($group . '::' . $key) is-invalid @enderror"
                                                    placeholder="#1B5FAA"
                                                    aria-label="Kode warna {{ $label }}">
                                            </div>

                                            <small class="text-muted">Bisa diketik (contoh <code>#1B5FAA</code> atau <code>1B5FAA</code>).</small>

                                            @error($group . '::' . $key)
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        @elseif ($key === 'tentang')
                                            <textarea id="{{ $group }}-{{ $key }}" name="{{ $group }}::{{ $key }}" rows="4" class="form-control">{{ $nilai }}</textarea>
                                            <small class="text-muted">Teks profil singkat yang tampil di beranda &amp; footer.</small>
                                        @elseif ($tipe === 'textarea')
                                            <textarea id="{{ $group }}-{{ $key }}" name="{{ $group }}::{{ $key }}" rows="4" class="form-control">{{ $nilai }}</textarea>
                                        @else
                                            <input type="{{ $tipe }}"
                                                id="{{ $group }}-{{ $key }}"
                                                name="{{ $group }}::{{ $key }}"
                                                value="{{ $nilai }}"
                                                class="form-control">

                                            @if ($key === 'whatsapp')
                                                <small class="text-muted">Format internasional tanpa tanda +, contoh: 6281234567890</small>
                                            @elseif ($key === 'gmaps_embed')
                                                <small class="text-muted">Tempel nilai <code>src</code> dari Google Maps &rarr; Bagikan &rarr; Sematkan peta.</small>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4 admin-sticky align-self-start">
                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Kelola PPDB</h5>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.ppdb-waves.index') }}" class="btn btn-outline-primary btn-sm text-start">
                                <i class="fa fa-calendar-alt me-2"></i> Gelombang Pendaftaran
                            </a>
                            <a href="{{ route('admin.ppdb-requirements.index') }}" class="btn btn-outline-primary btn-sm text-start">
                                <i class="fa fa-clipboard-list me-2"></i> Persyaratan Pendaftaran
                            </a>
                            <a href="{{ route('admin.ppdb-fees.index') }}" class="btn btn-outline-primary btn-sm text-start">
                                <i class="fa fa-money-bill-wave me-2"></i> Biaya Pendidikan
                            </a>
                            <a href="{{ route('admin.ppdb-applicants.index') }}" class="btn btn-outline-primary btn-sm text-start">
                                <i class="fa fa-user-graduate me-2"></i> Data Pendaftar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Banner PPDB</h5>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="banner-aktif"
                                name="ppdb::banner_active" value="1"
                                {{ old('ppdb::banner_active', setting('banner_active', false)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="banner-aktif">Tampilkan banner PPDB di beranda</label>
                        </div>

                        <p class="text-muted small mb-0">
                            Teks dan tautan banner diatur pada kartu "Banner PPDB" di atas
                            (<code>banner_text</code>, <code>banner_link</code>, <code>banner_image</code>).
                        </p>
                    </div>
                </div>

                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Tema Warna</h5>

                        <p class="text-muted small mb-2">
                            Warna utama dipakai untuk tombol, aksen, dan tautan. Warna gelap untuk navbar dan judul section,
                            navy untuk footer &amp; sidebar admin.
                        </p>

                        <p class="text-muted small mb-0">
                            Teks di atas tombol dihitung otomatis agar tetap terbaca. Perubahan langsung tampil di situs
                            tanpa rebuild CSS.
                        </p>
                    </div>
                </div>

                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Catatan</h5>

                        <ul class="text-muted small ps-3 mb-0">
                            <li>Perubahan tersimpan langsung pada tabel <code>settings</code> dan cache dibersihkan otomatis.</li>
                            <li>Untuk gambar banner atau peta, gunakan URL lengkap.</li>
                            <li>Sosial media dikosongkan akan menampilkan tautan <code>#</code>.</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-dark rounded-pill">Lihat Situs</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan Pengaturan</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('skrip')
    <script>
        // Sinkronkan color picker dengan input teks (warna bisa diketik).
        document.querySelectorAll('.color-pair').forEach((pair) => {
            const picker = pair.querySelector('input[type="color"]');
            const text = pair.querySelector('input[type="text"]');

            if (!picker || !text) {
                return;
            }

            picker.addEventListener('input', () => {
                text.value = picker.value.toUpperCase();
            });

            text.addEventListener('input', () => {
                const value = text.value.trim();

                if (/^#?[0-9a-fA-F]{6}$/.test(value)) {
                    picker.value = value.startsWith('#') ? value : '#' + value;
                }
            });

            text.addEventListener('blur', () => {
                let value = text.value.trim();

                if (value !== '' && !value.startsWith('#')) {
                    value = '#' + value;
                }

                text.value = value.toUpperCase();

                if (/^#[0-9a-fA-F]{6}$/.test(text.value)) {
                    picker.value = text.value;
                }
            });
        });
    </script>
@endpush
