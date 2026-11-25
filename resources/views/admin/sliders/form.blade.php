@extends('layouts.admin')

@section('title', 'Form Slider')

@section('konten')
    <h1 class="h3 mb-4">{{ $slider->exists ? 'Ubah Slider' : 'Tambah Utama Slider Baru' }}</h1>

    <div class="card admin-card">
        <div class="card-body p-4">
            <form action="{{ $slider->exists ? route('admin.hero-sliders.update', $slider) : route('admin.hero-sliders.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                @if ($slider->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label d-block" for="gambar">Gambar slider (disarankan 1920×1080, maks 10 MB)</label>
                        @include('admin.components.media-picker', [
                            'name' => 'image',
                            'id' => 'gambar',
                            'current' => $slider->exists ? $slider->image : null,
                        ])
                    </div>

                    <div class="col-md-8">
                        <label class="form-label" for="judul">Judul tampil (opsional)</label>
                        <input type="text" id="judul" name="title" value="{{ old('title', $slider->title) }}" class="form-control">

                        <label class="form-label mt-3" for="tautan">Tautan saat diklik (opsional; mis: <code>berita</code>)</label>
                        <input type="text" id="tautan" name="link" value="{{ old('link', $slider->link) }}" class="form-control">

                        <div class="row">
                            <div class="col-md-4 mt-3">
                                <label class="form-label" for="pemisahan">Urutan tampil</label>
                                <input type="number" id="pemisahan" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 0) }}" class="form-control">
                            </div>

                            <div class="col-md-4 mt-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                        {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Tampilkan di beranda</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 mt-4">
                    <a href="{{ route('admin.hero-sliders.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
