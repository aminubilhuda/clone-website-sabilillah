@extends('layouts.admin')

@section('title', 'Form Ekstrakurikuler')

@section('konten')
    <h1 class="h3 mb-4">{{ $item->exists ? 'Ubah Ekstrakurikuler' : 'Ekstrakurikuler Baru' }}</h1>

    <div class="card admin-card" style="max-width: 720px;">
        <div class="card-body p-4">
            <form action="{{ $item->exists ? route('admin.extracurriculars.update', $item) : route('admin.extracurriculars.store') }}" method="POST">
                @csrf
                @if ($item->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="name">Nama *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="sort_order">Urutan</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="form-control" min="0">
                    </div>
                </div>

                <label class="form-label mt-3" for="icon">Ikon Font Awesome</label>
                <input type="text" id="icon" name="icon" value="{{ old('icon', $item->icon) }}"
                    class="form-control @error('icon') is-invalid @enderror" placeholder="mis: fa-robot, fa-futbol, fa-music">
                @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Gunakan nama kelas Font Awesome 5, contoh: <code>fa-robot</code>, <code>fa-futbol</code>, <code>fa-music</code>, <code>fa-palette</code>.</small>

                <label class="form-label mt-3" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $item->description) }}</textarea>

                <div class="form-check mt-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.extracurriculars.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
