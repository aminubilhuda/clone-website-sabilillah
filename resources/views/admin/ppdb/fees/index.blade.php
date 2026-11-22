@extends('layouts.admin')

@section('title', 'Biaya PPDB')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Biaya PPDB</h1>
            <small class="text-muted">Rincian biaya yang tampil di halaman PPDB (SPP, daftar ulang, seragam, dll).</small>
        </div>
        <a href="{{ route('admin.ppdb-fees.create') }}" class="btn btn-pill-ppdb rounded-pill"><i class="fa fa-plus me-1"></i> Biaya Baru</a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Label</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fees as $fee)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $fee->label }}</td>
                            <td><span class="badge bg-light text-dark border">{{ strtoupper($fee->category) }}</span></td>
                            <td class="small text-muted">{{ str($fee->description ?: '-')->limit(50) }}</td>
                            <td>Rp {{ number_format($fee->nominal, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $fee->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $fee->is_active ? 'aktif' : 'non-aktif' }}</span>
                            </td>
                            <td>{{ $fee->sort_order }}</td>
                            <td class="text-end pe-3">
                                <form action="{{ route('admin.ppdb-fees.destroy', $fee) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus biaya ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                                <a href="{{ route('admin.ppdb-fees.edit', $fee) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">Belum ada rincian biaya.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
