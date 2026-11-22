@extends('layouts.admin')

@section('title', 'Kategori')

@section('konten')
    <h1 class="h3 mb-1">Kategori</h1>
    <p class="text-muted mb-4">Pengelompokan konten per tipe artikel.</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card admin-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Nama</th>
                                <th>Slug</th>
                                <th>Tipe</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategori as $cat)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $cat->name }}</td>
                                    <td><code>{{ $cat->slug }}</code></td>
                                    <td><span class="badge bg-light text-dark border">{{ $cat->type }}</span></td>
                                    <td class="text-end pe-3">
                                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-5">Belum ada kategori.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 admin-sticky align-self-start">
            <div class="card admin-card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Tambah Kategori Baru</h5>

                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <label class="form-label" for="namakategori">Nama kategori *</label>
                        <input type="text" id="namakategori" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label class="form-label mt-3" for="tipekategori">Tipe *</label>
                        <select id="tipekategori" name="type" class="form-select @error('type') is-invalid @enderror">
                            @foreach ($types as $tipe)
                                <option value="{{ $tipe }}" {{ old('type') === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                            @endforeach
                        </select>

                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-pill-ppdb w-100 mt-3">Simpan Kategori</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
