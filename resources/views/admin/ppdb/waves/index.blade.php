@extends('layouts.admin')

@section('title', 'Gelombang PPDB')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gelombang PPDB</h1>
            <small class="text-muted">Periode pendaftaran yang tampil di halaman PPDB.</small>
        </div>
        <a href="{{ route('admin.ppdb-waves.create') }}" class="btn btn-pill-ppdb rounded-pill"><i class="fa fa-plus me-1"></i> Gelombang Baru</a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Label</th>
                        <th>Periode</th>
                        <th>Biaya Formulir</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($waves as $wave)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $wave->label }}</td>
                            <td class="small">{{ $wave->formattedPeriod() }}</td>
                            <td>Rp {{ number_format($wave->biaya, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $wave->statusClass() }}">{{ $wave->statusLabel() }}</span>
                                @if (! $wave->is_active)
                                    <span class="badge bg-secondary ms-1">non-aktif</span>
                                @endif
                            </td>
                            <td>{{ $wave->sort_order }}</td>
                            <td class="text-end pe-3">
                                <form action="{{ route('admin.ppdb-waves.destroy', $wave) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus gelombang ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                                <a href="{{ route('admin.ppdb-waves.edit', $wave) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Belum ada gelombang pendaftaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
