@extends('layouts.admin')

@section('title', 'Persyaratan PPDB')

@section('konten')
    <h1 class="h3 mb-1">Persyaratan PPDB</h1>
    <p class="text-muted mb-4">Syarat umum dan berkas yang tampil di halaman PPDB.</p>

    <div class="row g-4">
        @foreach (['umum' => 'Syarat Umum', 'berkas' => 'Berkas Pendaftaran'] as $type => $judul)
            @php
                $daftar = $type === 'umum' ? $umum : $berkas;
            @endphp

            <div class="col-lg-6">
                <div class="card admin-card mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $judul }}</h5>
                        <span class="badge bg-light text-dark border">{{ $daftar->count() }} item</span>
                    </div>

                    <div class="card-body p-3">
                        <form action="{{ route('admin.ppdb-requirements.store') }}" method="POST" class="row g-2 align-items-end mb-3">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <div class="col">
                                <textarea name="content" rows="2" class="form-control form-control-sm" placeholder="Tulis persyaratan baru..." required></textarea>
                            </div>
                            <div class="col-auto">
                                <input type="number" name="sort_order" value="0" class="form-control form-control-sm" style="width: 70px;" title="Urutan">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-pill-ppdb"><i class="fa fa-plus"></i></button>
                            </div>
                        </form>

                        <ul class="list-group list-group-flush">
                            @forelse ($daftar as $item)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <span class="small">{{ $item->content }}</span>
                                        <div class="d-flex gap-1">
                                            <details>
                                                <summary class="btn btn-sm btn-outline-primary" style="cursor: pointer;">Ubah</summary>
                                                <form action="{{ route('admin.ppdb-requirements.update', $item) }}" method="POST" class="mt-2">
                                                    @csrf @method('PUT')
                                                    <textarea name="content" rows="2" class="form-control form-control-sm mb-2" required>{{ $item->content }}</textarea>
                                                    <div class="d-flex gap-2">
                                                        <input type="number" name="sort_order" value="{{ $item->sort_order }}" class="form-control form-control-sm" style="width: 70px;" title="Urutan">
                                                        <button type="submit" class="btn btn-sm btn-pill-ppdb">Simpan</button>
                                                    </div>
                                                </form>
                                            </details>
                                            <form action="{{ route('admin.ppdb-requirements.destroy', $item) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Hapus persyaratan ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item px-0 text-muted small">Belum ada item.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
