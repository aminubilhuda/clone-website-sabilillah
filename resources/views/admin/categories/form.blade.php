@extends('layouts.admin')

@section('title', 'Ubah Kategori')

@section('konten')
    <h1 class="h3 mb-4">Ubah Kategori: {{ $category->name }}</h1>

    <div class="card admin-card" style="max-width: 560px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf @method('PUT')

                <label class="form-label" for="namakategori">Nama kategori *</label>
                <input type="text" id="namakategori" name="name" value="{{ old('name', $category->name) }}"
                    class="form-control @error('name') is-invalid @enderror" required>

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <label class="form-label mt-3" for="tipekategori">Tipe *</label>
                <select id="tipekategori" name="type" class="form-select">
                    @foreach ($types as $type)
                        <option value="{{ $type }}" {{ old('type', $category->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
