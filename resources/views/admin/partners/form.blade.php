@extends('layouts.admin')

@section('title', $partner->exists ? 'Ubah Mitra' : 'Mitra Baru')

@section('konten')
    <h1 class="h3 mb-4">{{ $partner->exists ? 'Ubah Mitra: ' . $partner->name : 'Mitra Baru' }}</h1>

    <div class="card admin-card" style="max-width: 640px;">
        <div class="card-body p-4">
            <form action="{{ $partner->exists ? route('admin.partners.update', $partner) : route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($partner->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4 text-center">
                        <img src="{{ media_url($partner->logo_path, 'img/partner-1.svg') }}" class="img-fluid rounded border mb-2" alt="{{ $partner->name }}">
                        <input type="file" name="logo" accept="image/*" class="form-control form-control-sm">
                        <small class="text-muted d-block mt-1">Maks 10 MB.</small>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label" for="mitra-name">Nama mitra *</label>
                        <input type="text" id="mitra-name" name="name" value="{{ old('name', $partner->name) }}" class="form-control" required>

                        <label class="form-label mt-3" for="mitra-group">Grup *</label>
                        <select id="mitra-group" name="group" class="form-select">
                            <option value="klien" {{ old('group', $partner->group) === 'klien' ? 'selected' : '' }}>Mitra Industri</option>
                            <option value="lisensi" {{ old('group', $partner->group) === 'lisensi' ? 'selected' : '' }}>Lembaga Sertifikasi</option>
                        </select>

                        <label class="form-label mt-3" for="mitra-url">Situs web</label>
                        <input type="text" id="mitra-url" name="website_url" value="{{ old('website_url', $partner->website_url) }}" class="form-control">

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="m.isActive" name="is_active" value="1"
                                {{ old('is_active', $partner->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="m.isActive">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
