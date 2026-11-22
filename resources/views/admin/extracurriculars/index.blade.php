@extends('layouts.admin')

@section('title', 'Ekstrakurikuler')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Ekstrakurikuler</h1>
            <small class="text-muted">Kegiatan ekstrakurikuler yang tampil di halaman Ekstrakurikuler.</small>
        </div>
        <a href="{{ route('admin.extracurriculars.create') }}" class="btn btn-pill-ppdb rounded-pill"><i class="fa fa-plus me-1"></i> Ekstrakurikuler Baru</a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Ikon</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $item->name }}</td>
                            <td><i class="fa {{ $item->iconClass() }} text-primary"></i> <code>{{ $item->iconClass() }}</code></td>
                            <td class="small text-muted">{{ str($item->description ?: '-')->limit(60) }}</td>
                            <td>
                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $item->is_active ? 'aktif' : 'non-aktif' }}</span>
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td class="text-end pe-3">
                                <form action="{{ route('admin.extracurriculars.destroy', $item) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus ekstrakurikuler ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                                <a href="{{ route('admin.extracurriculars.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Belum ada ekstrakurikuler.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
