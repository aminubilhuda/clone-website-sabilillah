@extends('layouts.admin')

@section('title', 'Form Artikel')

@section('konten')
    <h1 class="h3 mb-1">{{ $post->exists ? 'Ubah ' . $types[$post->type] : 'Tulis ' . $types[$post->type] . ' Baru' }}</h1>
    <p class="text-muted mb-4">Isi detail artikel melalui formulir berkas.</p>

    <form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card admin-card">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label" for="judul">Judul *</label>
                            <input type="text" id="judul" name="title" value="{{ old('title', $post->title) }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror" required>

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ringkasan">Ringkasan singkat</label>
                            <textarea id="ringkasan" name="excerpt" rows="2" class="form-control">{{ old('excerpt', $post->excerpt) }}</textarea>
                            <small class="text-muted">Tampil pada kartu &amp; hasil pencarian. Biarkan kosong untuk diisi otomatis dari isi.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="isi">Isi artikel</label>
                            <textarea id="isi" name="content" rows="12" class="form-control">{{ old('content', $post->content) }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="gambar-sampul">Sampul gambar (16:9 disarankan, maks 10 MB)</label>
                                <input type="file" id="gambar-sampul" name="cover_image" accept="image/*" class="form-control">

                                @error('cover_image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="dokumen">Dokumen PDF (majalah/pengumuman)</label>
                                <input type="file" id="dokumen" name="document" accept="application/pdf" class="form-control">

                                @error('document')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror

                                @if ($post->document_path)
                                    <a href="{{ media_url($post->document_path) }}" target="_blank" class="small">Berkas saat ini: {{ $post->document_path }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 admin-sticky align-self-start">
                <div class="card admin-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Publikasi</h5>

                        <label class="form-label" for="tipe">Tipe konten *</label>
                        <select id="tipe" name="type" class="form-select @error('type') is-invalid @enderror">
                            @foreach ($types as $tipe => $label)
                                <option value="{{ $tipe }}" {{ old('type', $post->type) === $tipe ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>

                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label class="form-label mt-3" for="status">Status *</label>
                        <select id="status" name="status" class="form-select">
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Terbit</option>
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>

                        <label class="form-label mt-3" for="kategori">Kategori</label>
                        <select id="kategori" name="category_id" class="form-select">
                            <option value="">— tanpa kategori —</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('category_id', $post->category_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->name }}
                                </option>
                            @endforeach
                        </select>

                        <label class="form-label mt-3" for="jurusan">Jurusan (opsional)</label>
                        <select id="jurusan" name="jurusan" class="form-select">
                            <option value="">— semua jurusan —</option>
                            @foreach ($jurusanOptions as $slug => $nama)
                                <option value="{{ $slug }}" {{ old('jurusan', $post->jurusan) === $slug ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                                {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Tampilkan sebagai unggulan / sorotan</label>
                        </div>
                    </div>
                </div>

                <div class="card admin-card agenda-detail {{ (str($post->type)->toString() === 'agenda') ? '' : 'd-none' }}">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Detail Agenda</h5>

                        <label class="form-label" for="Dimulai">Mulai</label>
                        <input type="datetime-local" id="Dimulai" name="starts_at"
                            value="{{ old('starts_at', $post->starts_at?->format('Y-m-d\\TH:i')) }}" class="form-control">

                        <label class="form-label mt-3" for="Berakhir">Selesai</label>
                        <input type="datetime-local" id="Berakhir" name="ends_at"
                            value="{{ old('ends_at', $post->ends_at?->format('Y-m-d\\TH:i')) }}" class="form-control">

                        @error('ends_at')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <label class="form-label mt-3" for="lokasi">Lokasi</label>
                        <input type="text" id="lokasi" name="location" value="{{ old('location', $post->location) }}" class="form-control">
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 mt-4">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('skrip')
    <script>
        // Tampilkan panel agenda hanya bila tipe = agenda
        const pilihTipe = document.getElementById('tipe');
        const panelAgenda = document.querySelector('.agenda-detail');

        const perbaruiPanel = () => {
            if (panelAgenda !== null) {
                panelAgenda.classList.toggle('d-none', pilihTipe.value !== 'agenda');
            }
        };

        pilihTipe.addEventListener('change', perbaruiPanel);
        perbaruiPanel();
    </script>
@endpush
