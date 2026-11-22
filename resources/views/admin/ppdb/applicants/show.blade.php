@extends('layouts.admin')

@section('title', 'Detail Pendaftar PPDB')

@section('konten')
    <h1 class="h3 mb-1">Detail Pendaftar PPDB</h1>
    <p class="text-muted mb-4">{{ $applicant->name }}</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card admin-card">
                <div class="card-body p-4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Nama Calon Siswa</dt>
                        <dd class="col-sm-8">{{ $applicant->name }}</dd>

                        <dt class="col-sm-4">NISN</dt>
                        <dd class="col-sm-8">{{ $applicant->nisn ?: '-' }}</dd>

                        <dt class="col-sm-4">Asal Sekolah</dt>
                        <dd class="col-sm-8">{{ $applicant->asal_sekolah }}</dd>

                        <dt class="col-sm-4">Jurusan Pilihan 1</dt>
                        <dd class="col-sm-8">{{ strtoupper($applicant->jurusan) }}</dd>

                        <dt class="col-sm-4">Jurusan Pilihan 2</dt>
                        <dd class="col-sm-8">{{ $applicant->jurusan_2 ? strtoupper($applicant->jurusan_2) : '-' }}</dd>

                        <dt class="col-sm-4">Orang Tua / Wali</dt>
                        <dd class="col-sm-8">{{ $applicant->parent_name }}</dd>

                        <dt class="col-sm-4">WhatsApp</dt>
                        <dd class="col-sm-8">
                            <a href="https://wa.me/{{ $applicant->whatsapp }}" target="_blank" rel="noopener">
                                {{ $applicant->whatsapp }} <i class="fab fa-whatsapp ms-1"></i>
                            </a>
                        </dd>

                        <dt class="col-sm-4">Alamat</dt>
                        <dd class="col-sm-8">{{ $applicant->alamat }}</dd>

                        <dt class="col-sm-4">Gelombang</dt>
                        <dd class="col-sm-8">{{ $applicant->wave?->label ?? '-' }}</dd>

                        <dt class="col-sm-4">Tanggal Daftar</dt>
                        <dd class="col-sm-8">{{ $applicant->created_at->format('d F Y H:i') }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $applicant->statusClass() }}">{{ $applicant->statusLabel() }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4 admin-sticky align-self-start">
            <div class="card admin-card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Ubah Status</h5>

                    <form action="{{ route('admin.ppdb-applicants.update', $applicant) }}" method="POST">
                        @csrf @method('PUT')

                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-select mb-3">
                            <option value="baru" {{ $applicant->status === 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diterima" {{ $applicant->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ $applicant->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <label class="form-label" for="notes">Catatan</label>
                        <textarea id="notes" name="notes" rows="4" class="form-control mb-3">{{ old('notes', $applicant->notes) }}</textarea>

                        <button type="submit" class="btn btn-pill-ppdb w-100">Simpan</button>
                    </form>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.ppdb-applicants.index') }}" class="btn btn-outline-dark w-100">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
