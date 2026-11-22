@extends('layouts.admin')

@section('title', 'Form Gelombang PPDB')

@section('konten')
    <h1 class="h3 mb-4">{{ $wave->exists ? 'Ubah Gelombang' : 'Gelombang Baru' }}</h1>

    <div class="card admin-card" style="max-width: 720px;">
        <div class="card-body p-4">
            <form action="{{ $wave->exists ? route('admin.ppdb-waves.update', $wave) : route('admin.ppdb-waves.store') }}" method="POST">
                @csrf
                @if ($wave->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="label">Label *</label>
                        <input type="text" id="label" name="label" value="{{ old('label', $wave->label) }}"
                            class="form-control @error('label') is-invalid @enderror" required>
                        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="sort_order">Urutan</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $wave->sort_order ?? 0) }}" class="form-control">
                    </div>
                </div>

                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="mulai">Mulai *</label>
                        <input type="date" id="mulai" name="mulai" value="{{ old('mulai', $wave->mulai?->format('Y-m-d')) }}"
                            class="form-control @error('mulai') is-invalid @enderror" required>
                        @error('mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="selesai">Selesai *</label>
                        <input type="date" id="selesai" name="selesai" value="{{ old('selesai', $wave->selesai?->format('Y-m-d')) }}"
                            class="form-control @error('selesai') is-invalid @enderror" required>
                        @error('selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <label class="form-label mt-3" for="biaya">Biaya Formulir (Rp)</label>
                <input type="number" id="biaya" name="biaya" value="{{ old('biaya', $wave->biaya ?? 0) }}" class="form-control" min="0">

                <label class="form-label mt-3" for="benefit">Benefit (satu per baris)</label>
                <textarea id="benefit" name="benefit" rows="4" class="form-control">{{ old('benefit', $wave->benefit) }}</textarea>

                <div class="form-check mt-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $wave->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.ppdb-waves.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
