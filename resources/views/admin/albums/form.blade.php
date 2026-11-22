@extends('layouts.admin')

@section('title', 'Kelola Album')

@section('konten')
    <h1 class="h3 mb-1">{{ $album->exists ? 'Kelola Album: ' . $album->title : 'Album Baru' }}</h1>
    <p class="text-muted mb-4">Atur detail album, lalu kelola item galeri di bawah.</p>

    <form action="{{ $album->exists ? route('admin.albums.update', $album) : route('admin.albums.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if ($album->exists)
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card admin-card">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="judul-album">Judul album *</label>
                                <input type="text" id="judul-album" name="title" value="{{ old('title', $album->title) }}"
                                    class="form-control @error('title') is-invalid @enderror" required>

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="slug-album">Slug (URL)</label>
                                <input type="text" id="slug-album" name="slug" value="{{ old('slug', $album->slug) }}"
                                    class="form-control @error('slug') is-invalid @enderror">

                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-0">
                            <div class="col-md-6">
                                <label class="form-label" for="jenis-album">Jenis album *</label>
                                <select id="jenis-album" name="kind" class="form-select">
                                    <option value="foto" {{ old('kind', $album->kind) === 'foto' ? 'selected' : '' }}>Foto</option>
                                    <option value="video" {{ old('kind', $album->kind) === 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="sampul-album">Sampul album (maks 10 MB)</label>
                                <input type="file" id="sampul-album" name="cover" accept="image/*" class="form-control">
                            </div>
                        </div>

                        <label class="form-label mt-3" for="deskripsi-album">Deskripsi</label>
                        <textarea id="deskripsi-album" name="description" rows="3" class="form-control">{{ old('description', $album->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 admin-sticky align-self-start">
                <div class="card admin-card">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Keterangan</h5>

                        @if ($album->exists)
                            <a href="{{ route('album.show', $album->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary mb-3">
                                <i class="fa fa-external-link-alt me-1"></i>Lihat halaman album
                            </a>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.albums.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                            <button type="submit" class="btn btn-pill-ppdb">{{ $album->exists ? 'Simpan Perubahan' : 'Buat Album' }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if ($album->exists)
        {{-- Item galeri --}}
        <div class="card admin-card mt-4">
            <div class="card-body p-4">
                <h5 class="mb-3">Item Galeri ({{ $album->items->count() }})</h5>

                <form action="{{ route('admin.albums.items.store', $album) }}" method="POST" enctype="multipart/form-data"
                    class="row g-3 align-items-end mb-4">
                    @csrf

                    <div class="col-md-3">
                        <label class="form-label" for="media-file">Unggah gambar (maks 10 MB)</label>
                        <input type="file" id="media-file" name="media_file" accept="image/*" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="media-url">Atau tautan video</label>
                        <input type="text" id="media-url" name="media_url" class="form-control" placeholder="https://www.youtube.com/...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <input type="text" id="keterangan" name="caption" class="form-control" placeholder="mis: dokumentasi #7">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-pill-ppdb w-100"><i class="fa fa-plus me-1"></i> Tambah Item</button>
                    </div>
                </form>

                <div class="row g-2">
                    @forelse ($album->items as $item)
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="border rounded p-2 h-100">
                                @if ($item->isVideo())
                                    <span class="badge bg-dark mb-2"><i class="fa fa-play me-1"></i>{{ str($item->media_url)->limit(60) }}</span>
                                @else
                                    <img src="{{ media_url($item->media_path, 'img/album-1.svg') }}" class="w-100 rounded" style="height:130px; object-fit:cover;" alt="{{ $item->caption }}">
                                @endif

                                <form class="d-flex gap-1 mt-2" action="{{ route('admin.albums.items.update', $item) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="text" name="caption" class="form-control form-control-sm" value="{{ $item->caption }}">
                                    <button class="btn btn-sm btn-outline-primary" type="submit"><i class="fa fa-check"></i></button>
                                </form>

                                <form class="mt-1" action="{{ route('admin.albums.items.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Hapus item ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger w-100"><i class="fa fa-trash me-1"></i>Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">Belum ada item pada album ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
@endsection
