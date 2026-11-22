@extends('layouts.admin')

@section('title', 'Form Popup')

@section('konten')
    <h1 class="h3 mb-4">{{ $popup->exists ? 'Ubah Popup PPDB' : 'Popup PPDB Baru' }}</h1>

    <div class="card admin-card">
        <div class="card-body p-4">
            <form action="{{ $popup->exists ? route('admin.popups.update', $popup) : route('admin.popups.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($popup->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label d-block" for="gambar">Gambar popup (maks 10 MB)</label>
                        <input type="file" id="gambar" name="image" accept="image/*" class="form-control">

                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        @if ($popup->exists)
                            <img src="{{ media_url($popup->image, 'img/popup-ppdb.svg') }}" class="img-fluid rounded mt-2" alt="Popup sekarang">
                        @endif
                    </div>

                    <div class="col-md-8">
                        <label class="form-label" for="tautan">Tautan klik (opsional)</label>
                        <input type="text" id="tautan" name="link" value="{{ old('link', $popup->link) }}" class="form-control" placeholder="mis: halaman/tata-cara-pendaftaran">

                        <label class="form-label mt-3" for="aktif">Periode tampil</label>
                        <div class="row">
                            <div class="col-md-5">
                                <input type="datetime-local" id="starter" name="starts_at"
                                    value="{{ old('starts_at', $popup->starts_at?->format('Y-m-d\\TH:i')) }}"
                                    class="form-control" placeholder="Sekarang">
                            </div>
                            <div class="col-md-5">
                                <input type="datetime-local" id="selesai" name="ends_at"
                                    value="{{ old('ends_at', $popup->ends_at?->format('Y-m-d\\TH:i')) }}"
                                    class="form-control">
                            </div>
                        </div>

                        @error('ends_at')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $popup->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan popup</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 mt-4">
                    <a href="{{ route('admin.popups.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
