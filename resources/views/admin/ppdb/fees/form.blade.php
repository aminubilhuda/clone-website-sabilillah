@extends('layouts.admin')

@section('title', 'Form Biaya PPDB')

@section('konten')
    <h1 class="h3 mb-4">{{ $fee->exists ? 'Ubah Biaya' : 'Biaya Baru' }}</h1>

    <div class="card admin-card" style="max-width: 720px;">
        <div class="card-body p-4">
            <form action="{{ $fee->exists ? route('admin.ppdb-fees.update', $fee) : route('admin.ppdb-fees.store') }}" method="POST">
                @csrf
                @if ($fee->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="label">Label *</label>
                        <input type="text" id="label" name="label" value="{{ old('label', $fee->label) }}"
                            class="form-control @error('label') is-invalid @enderror" required>
                        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="category">Kategori *</label>
                        <select id="category" name="category" class="form-select">
                            @foreach (['BULANAN', 'TAHUNAN', 'SEKALI', 'LAINNYA'] as $kategori)
                                <option value="{{ $kategori }}" {{ old('category', $fee->category) === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <label class="form-label mt-3" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $fee->description) }}</textarea>

                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="nominal">Nominal (Rp) *</label>
                        <input type="number" id="nominal" name="nominal" value="{{ old('nominal', $fee->nominal ?? 0) }}"
                            class="form-control @error('nominal') is-invalid @enderror" min="0" required>
                        @error('nominal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="sort_order">Urutan</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $fee->sort_order ?? 0) }}" class="form-control" min="0">
                    </div>
                </div>

                <div class="form-check mt-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $fee->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.ppdb-fees.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
