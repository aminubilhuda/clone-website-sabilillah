@extends('layouts.admin')

@section('title', 'Mitra & Sertifikasi')

@section('konten')
    <h1 class="h3 mb-1">Mitra &amp; Sertifikasi</h1>
    <p class="text-muted mb-4">Logo yang tampil di marquee "Mitra Industri" dan "Lembaga Sertifikasi" beranda.</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card admin-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Logo</th>
                                <th>Nama</th>
                                <th>Grup</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($partners as $m)
                                <tr>
                                    <td class="ps-3"><img src="{{ media_url($m->logo_path, 'img/partner-1.svg') }}" style="height:36px;" alt="{{ $m->name }}"></td>
                                    <td class="fw-semibold">{{ $m->name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $m->group }}</span></td>
                                    <td>
                                        <span class="badge {{ $m->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $m->is_active ? 'aktif' : 'non-aktif' }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <form action="{{ route('admin.partners.destroy', $m) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus mitra ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route('admin.partners.edit', $m) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-5">Belum ada mitra.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 admin-sticky align-self-start">
            <div class="card admin-card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Tambah Mitra Baru</h5>

                    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <label class="form-label" for="mitra-name">Nama mitra *</label>
                        <input type="text" id="mitra-name" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label class="form-label mt-3" for="mitra-group">Grup *</label>
                        <select id="mitra-group" name="group" class="form-select">
                            <option value="klien" {{ old('group') === 'klien' ? 'selected' : '' }}>Mitra Industri</option>
                            <option value="lisensi" {{ old('group') === 'lisensi' ? 'selected' : '' }}>Lembaga Sertifikasi</option>
                        </select>

                        <label class="form-label mt-3" for="mitra-logo">Logo (maks 10 MB)</label>
                        <input type="file" id="mitra-logo" name="logo" accept="image/*" class="form-control">

                        <label class="form-label mt-3" for="mitra-url">Situs web (opsional)</label>
                        <input type="text" id="mitra-url" name="website_url" value="{{ old('website_url') }}" class="form-control">

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="m.isActive" name="is_active" value="1" checked>
                            <label class="form-check-label" for="m.isActive">Aktif</label>
                        </div>

                        <button type="submit" class="btn btn-pill-ppdb w-100 mt-3">Simpan Mitra</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
