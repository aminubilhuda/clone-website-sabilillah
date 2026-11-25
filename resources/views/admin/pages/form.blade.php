@extends('layouts.admin')

@section('title', 'Form Halaman')

@section('konten')
    @php
        $blocks = $page->blocks ?? [];

        $list = function (string $key) use ($blocks): string {
            return is_array($blocks[$key] ?? null) ? implode("\n", $blocks[$key]) : '';
        };

        $pairs = function (string $key) use ($blocks): string {
            if (! is_array($blocks[$key] ?? null)) {
                return '';
            }

            return implode("\n", array_map(
                fn ($row) => implode(' | ', array_slice((array) $row, 0, 2)),
                $blocks[$key],
            ));
        };

        $tabel = function () use ($blocks): string {
            if (! is_array($blocks['tabel'] ?? null)) {
                return '';
            }

            return implode("\n", array_map(fn ($row) => implode(' | ', (array) $row), $blocks['tabel']));
        };
    @endphp

    <h1 class="h3 mb-1">{{ $page->exists ? 'Ubah Halaman' : 'Halaman Baru' }}</h1>
    <p class="text-muted mb-4">Pilih jenis halaman, lalu isi kolom yang muncul — tidak perlu menulis JSON.</p>

    <form action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if ($page->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h5 class="mb-1">Jenis Halaman</h5>
                                <p class="text-muted small mb-0" id="grup-note">Kolom di bawah menyesuaikan jenis halaman.</p>
                            </div>

                            <div style="min-width: 220px;">
                                <select id="grup" name="group" class="form-select">
                                    @foreach ($groups as $grup)
                                        <option value="{{ $grup }}" {{ old('group', $page->group) === $grup ? 'selected' : '' }}>
                                            {{ ['profil' => 'Profil', 'ppdb' => 'PPDB', 'jurusan' => 'Jurusan', 'statik' => 'Halaman Statis'][$grup] ?? $grup }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card admin-card mb-4" data-groups="profil,ppdb,jurusan,statik">
                    <div class="card-body p-4">
                        <h5 class="sub-title-bar mb-3">Konten Utama</h5>

                        <label class="form-label" for="intro">Intro / Pembuka</label>
                        <textarea id="intro" name="intro" rows="3" class="form-control" placeholder="Paragraf pembuka halaman...">{{ old('intro', $blocks['intro'] ?? '') }}</textarea>

                        <label class="form-label mt-3" for="isi">Isi Halaman</label>
                        <textarea id="isi" name="isi" rows="6" class="form-control" placeholder="Satu paragraf per baris...">{{ old('isi', $list('isi')) }}</textarea>
                        <small class="text-muted">Tulis satu paragraf per baris.</small>

                        <label class="form-label mt-3" for="gambar">Gambar Halaman (path)</label>
                        <input type="text" id="gambar" name="gambar" value="{{ old('gambar', $blocks['gambar'] ?? '') }}"
                            class="form-control" placeholder="img/nama-gambar.svg atau upload di bawah">

                        <label class="form-label mt-2" for="gambar_file">Atau upload / pilih gambar halaman</label>
                        @include('admin.components.media-picker', [
                            'name' => 'gambar_file',
                            'id' => 'gambar_file',
                            'current' => $blocks['gambar'] ?? null,
                        ])
                    </div>
                </div>

                <div class="card admin-card mb-4" data-groups="profil">
                    <div class="card-body p-4">
                        <h5 class="sub-title-bar mb-3">Visi &amp; Misi</h5>

                        <label class="form-label" for="visi">Visi</label>
                        <textarea id="visi" name="visi" rows="2" class="form-control">{{ old('visi', $blocks['visi'] ?? '') }}</textarea>

                        <label class="form-label mt-3" for="misi">Misi</label>
                        <textarea id="misi" name="misi" rows="5" class="form-control" placeholder="Satu poin misi per baris...">{{ old('misi', $list('misi')) }}</textarea>
                    </div>
                </div>

                <div class="card admin-card mb-4" data-groups="profil">
                    <div class="card-body p-4">
                        <h5 class="sub-title-bar mb-3">Sambutan</h5>

                        <label class="form-label" for="sambutan">Teks Sambutan</label>
                        <textarea id="sambutan" name="sambutan" rows="5" class="form-control">{{ old('sambutan', $blocks['sambutan'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="card admin-card mb-4" data-groups="profil">
                    <div class="card-body p-4">
                        <h5 class="sub-title-bar mb-3">Struktur &amp; Guru</h5>

                        <label class="form-label" for="struktur">Struktur Organisasi</label>
                        <textarea id="struktur" name="struktur" rows="5" class="form-control" placeholder="Nama | Jabatan (satu per baris)">{{ old('struktur', $pairs('struktur')) }}</textarea>

                        <label class="form-label mt-3" for="guru">Guru &amp; Staf</label>
                        <textarea id="guru" name="guru" rows="5" class="form-control" placeholder="Nama | Jabatan (satu per baris)">{{ old('guru', $pairs('guru')) }}</textarea>
                    </div>
                </div>

                <div class="card admin-card mb-4" data-groups="profil">
                    <div class="card-body p-4">
                        <h5 class="sub-title-bar mb-3">Fasilitas</h5>

                        <label class="form-label" for="fasilitas">Daftar Fasilitas</label>
                        <textarea id="fasilitas" name="fasilitas" rows="5" class="form-control" placeholder="Satu fasilitas per baris...">{{ old('fasilitas', $list('fasilitas')) }}</textarea>
                    </div>
                </div>

                <div class="card admin-card mb-4" data-groups="ppdb">
                    <div class="card-body p-4">
                        <h5 class="sub-title-bar mb-3">Konten PPDB</h5>

                        <label class="form-label" for="langkah">Langkah Pendaftaran</label>
                        <textarea id="langkah" name="langkah" rows="5" class="form-control" placeholder="Satu langkah per baris...">{{ old('langkah', $list('langkah')) }}</textarea>

                        <label class="form-label mt-3" for="jalur">Jalur Penerimaan</label>
                        <textarea id="jalur" name="jalur" rows="4" class="form-control" placeholder="Judul | Deskripsi (satu per baris)">{{ old('jalur', $pairs('jalur')) }}</textarea>

                        <label class="form-label mt-3" for="tabel">Tabel Biaya</label>
                        <textarea id="tabel" name="tabel" rows="5" class="form-control" placeholder="Kolom 1 | Kolom 2 | Kolom 3 (baris pertama = header)">{{ old('tabel', $tabel()) }}</textarea>
                        <small class="text-muted">Pisahkan kolom dengan tanda <code>|</code>.</small>
                    </div>
                </div>

                <div class="card admin-card mb-4" data-groups="jurusan">
                    <div class="card-body p-4">
                        <h5 class="sub-title-bar mb-3">Konten Jurusan</h5>

                        <label class="form-label" for="deskripsi">Deskripsi Jurusan</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $blocks['deskripsi'] ?? '') }}</textarea>

                        <label class="form-label mt-3" for="keunggulan">Keunggulan</label>
                        <textarea id="keunggulan" name="keunggulan" rows="4" class="form-control" placeholder="Satu keunggulan per baris...">{{ old('keunggulan', $list('keunggulan')) }}</textarea>

                        <label class="form-label mt-3" for="prospek">Prospek Karier</label>
                        <textarea id="prospek" name="prospek" rows="4" class="form-control" placeholder="Satu prospek per baris...">{{ old('prospek', $list('prospek')) }}</textarea>
                    </div>
                </div>

                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <details>
                            <summary class="fw-semibold">Opsi lanjutan: JSON blok</summary>
                            <p class="text-muted small mt-2 mb-2">Kosongkan kecuali Anda perlu menambahkan kunci blok khusus.</p>

                            <textarea name="blocks_text" rows="10" class="form-control font-monospace">{{ old('blocks_text') }}</textarea>

                            @error('blocks_text')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </details>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 admin-sticky align-self-start">
                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Informasi Halaman</h5>

                        <label class="form-label" for="judul">Judul *</label>
                        <input type="text" id="judul" name="title" value="{{ old('title', $page->title) }}"
                            class="form-control @error('title') is-invalid @enderror" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <label class="form-label mt-3" for="subjudul">Sub-judul</label>
                        <input type="text" id="subjudul" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}" class="form-control">

                        <label class="form-label mt-3" for="slugbaru">Slug (URL)</label>
                        <input type="text" id="slugbaru" name="slug" value="{{ old('slug', $page->slug) }}"
                            class="form-control @error('slug') is-invalid @enderror" placeholder="kosongkan agar dibuat dari judul">
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <label class="form-label mt-3" for="urutan">Urutan</label>
                        <input type="number" id="urutan" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" class="form-control">

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_published" value="0">
                            <input class="form-check-input" type="checkbox" id="terpublikasi" name="is_published" value="1"
                                {{ old('is_published', $page->exists ? $page->is_published : true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="terpublikasi">Publikasikan halaman</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                            <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                        </div>
                    </div>
                </div>

                <div class="card admin-card">
                    <div class="card-body p-4">
                        <h6 class="mb-2">Panduan format</h6>
                        <ul class="text-muted small ps-3 mb-0">
                            <li><strong>Isi/Misi/Fasilitas/Keunggulan/Prospek/Langkah</strong>: satu item per baris.</li>
                            <li><strong>Struktur/Guru/Jalur</strong>: <code>Nama | Jabatan</code> per baris.</li>
                            <li><strong>Tabel</strong>: <code>Kolom 1 | Kolom 2</code>; baris pertama jadi header.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('skrip')
    <script>
        // Tampilkan hanya kolom yang relevan dengan jenis halaman.
        const grupSelect = document.getElementById('grup');
        const grupNote = document.getElementById('grup-note');
        const grupLabels = {
            profil: 'Profil',
            ppdb: 'PPDB',
            jurusan: 'Jurusan',
            statik: 'Halaman Statis',
        };

        const terapkanGrup = () => {
            const grup = grupSelect.value;

            document.querySelectorAll('[data-groups]').forEach((card) => {
                const tampil = card.dataset.groups.split(',').includes(grup);
                card.classList.toggle('d-none', !tampil);

                card.querySelectorAll('input, select, textarea').forEach((field) => {
                    field.disabled = !tampil;
                });
            });

            if (grupNote) {
                grupNote.textContent = 'Kolom di bawah disesuaikan untuk halaman ' + (grupLabels[grup] || grup) + '.';
            }
        };

        grupSelect.addEventListener('change', terapkanGrup);
        terapkanGrup();
    </script>
@endpush
