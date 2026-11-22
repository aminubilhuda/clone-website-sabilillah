@extends('layouts.admin')

@section('title', 'Pendaftar PPDB')

@section('konten')
    <h1 class="h3 mb-1">Pendaftar PPDB</h1>
    <p class="text-muted mb-4">Daftar calon siswa yang mengisi formulir pendaftaran online.</p>

    <form method="GET" action="{{ route('admin.ppdb-applicants.index') }}" class="row g-2 mb-4">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua status</option>
                @foreach (['baru' => 'Baru', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $nilai => $label)
                    <option value="{{ $nilai }}" {{ $status === $nilai ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama, NISN, atau asal sekolah">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-pill-ppdb w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.ppdb-applicants.index') }}" class="btn btn-outline-dark w-100">Reset</a>
        </div>
    </form>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Nama Calon Siswa</th>
                        <th>Jurusan</th>
                        <th>Asal Sekolah</th>
                        <th>Gelombang</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applicants as $applicant)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('admin.ppdb-applicants.show', $applicant) }}" class="fw-semibold text-dark">{{ $applicant->name }}</a>
                                @if ($applicant->nisn)
                                    <small class="d-block text-muted">NISN: {{ $applicant->nisn }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ strtoupper($applicant->jurusan) }}</span>
                                @if ($applicant->jurusan_2)
                                    <span class="badge bg-light text-dark border mt-1">{{ strtoupper($applicant->jurusan_2) }}</span>
                                @endif
                            </td>
                            <td class="small">{{ $applicant->asal_sekolah }}</td>
                            <td class="small">{{ $applicant->wave?->label ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $applicant->statusClass() }}">{{ $applicant->statusLabel() }}</span>
                            </td>
                            <td class="small text-muted">{{ $applicant->created_at->format('d M Y H:i') }}</td>
                            <td class="text-end pe-3">
                                <form action="{{ route('admin.ppdb-applicants.destroy', $applicant) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus pendaftar ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                                <a href="{{ route('admin.ppdb-applicants.show', $applicant) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5">Belum ada pendaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $applicants->onEachSide(1)->links() }}</div>
@endsection
