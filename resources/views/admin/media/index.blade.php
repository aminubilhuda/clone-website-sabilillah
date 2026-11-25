@extends('layouts.admin')

@section('title', 'Kelola Media')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h4 mb-1">Kelola Media</h1>
            <p class="text-muted small mb-0">Seluruh gambar yang tersimpan di <code>uploads/</code> maupun aset bawaan <code>public/img/</code> ({{ $items->count() }} berkas).</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-5">
            <div class="card admin-card h-100">
                <div class="card-body p-4">
                    <h6 class="mb-3"><i class="fa fa-upload me-2 text-primary"></i>Unggah Gambar Baru</h6>
                    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                        @csrf
                        <input type="file" name="file" accept="image/png,image/jpeg,image/webp,image/gif" class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <small class="text-muted">PNG, JPG, WebP, atau GIF — maks 10 MB.</small>
                            <button type="submit" class="btn btn-pill-ppdb btn-sm">Unggah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card admin-card h-100">
                <div class="card-body p-4">
                    <h6 class="mb-3"><i class="fa fa-filter me-2 text-primary"></i>Filter &amp; Pencarian</h6>
                    <form method="GET" action="{{ route('admin.media.manage') }}" class="row g-2 align-items-end">
                        <div class="col-sm-4">
                            <label class="form-label small" for="filter-sumber">Sumber</label>
                            <select id="filter-sumber" name="sumber" class="form-select">
                                <option value="">Semua ({{ $items->count() }})</option>
                                <option value="uploads" {{ $filter === 'uploads' ? 'selected' : '' }}>Upload (uploads/)</option>
                                <option value="img" {{ $filter === 'img' ? 'selected' : '' }}>Bawaan (img/)</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label small" for="cari-media">Nama / Path</label>
                            <input type="search" id="cari-media" name="cari" value="{{ $search }}" class="form-control" placeholder="mis: 202611 atau logo">
                        </div>
                        <div class="col-sm-3 d-grid">
                            <button type="submit" class="btn btn-outline-primary">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($items->isEmpty())
        <div class="card admin-card">
            <div class="card-body p-5 text-center text-muted">
                Tidak ada berkas media yang cocok.
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($items as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card admin-card h-100">
                        <div class="bg-light border-bottom d-flex align-items-center justify-content-center p-2" style="height: 150px;">
                            <img src="{{ $item['url'] }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="max-height: 134px; object-fit: contain;">
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="small fw-semibold text-truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</div>
                            <div class="text-muted text-truncate mb-2" style="font-size: 0.75rem;" title="{{ $item['path'] }}">{{ $item['path'] }}</div>

                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge {{ $item['source'] === 'uploads' ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                    {{ $item['source'] === 'uploads' ? 'Upload' : 'Bawaan' }}
                                </span>
                                <span class="badge text-bg-light border">
                                    {{ number_format($item['size'] / 1024, 1) }} KB
                                </span>
                                @if (($item['refs'] ?? 0) > 0)
                                    <span class="badge text-bg-success">Dipakai ({{ $item['refs'] }})</span>
                                @elseif ($item['source'] === 'uploads')
                                    <span class="badge text-bg-warning">Belum dipakai</span>
                                @endif
                            </div>

                            <div class="mt-auto d-flex gap-2">
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary flex-grow-1"
                                    onclick="navigator.clipboard?.writeText(@js($item['path']))">
                                    <i class="fa fa-copy me-1"></i>Salin Path
                                </button>

                                @if ($item['source'] === 'uploads')
                                    <form method="POST" action="{{ route('admin.media.destroy') }}"
                                        onsubmit="return confirm('Hapus gambar {{ $item['name'] }} dari penyimpanan?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path" value="{{ $item['path'] }}">
                                        @if (($item['refs'] ?? 0) > 0)
                                            <input type="hidden" name="force" value="1">
                                        @endif
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
