@extends('layouts.admin')

@section('title', 'FAQ')

@section('konten')
    <h1 class="h3 mb-1">Tanya Jawab (FAQ)</h1>
    <p class="text-muted mb-4">Isi pertanyaan yang sering diajukan oleh pengunjung.</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card admin-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Pertanyaan</th>
                                <th>Kategori</th>
                                <th status="col">Status</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faqs as $f)
                                <tr>
                                    <td class="ps-3">{{ $f->question }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $f->category }}</span></td>
                                    <td>
                                        <span class="badge {{ $f->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $f->is_active ? 'aktif' : 'non-aktif' }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <form action="{{ route('admin.faqs.destroy', $f) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus FAQ ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route('admin.faqs.edit', $f) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-5">Belum ada FAQ.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 admin-sticky align-self-start">
            <div class="card admin-card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Tambah FAQ Baru</h5>

                    <form action="{{ route('admin.faqs.store') }}" method="POST">
                        @csrf

                        <label class="form-label" for="faq-q">Pertanyaan *</label>
                        <input type="text" id="faq-q" name="question" value="{{ old('question') }}"
                            class="form-control @error('question') is-invalid @enderror" required>

                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label class="form-label mt-3" for="faq-a">Jawaban *</label>
                        <textarea id="faq-a" name="answer" rows="4" class="form-control @error('answer') is-invalid @enderror" required>{{ old('answer') }}</textarea>

                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label class="form-label mt-3" for="faq-c">Kategori</label>
                        <input type="text" id="faq-c" name="category" value="{{ old('category', 'Umum') }}" class="form-control">

                        <div class="form-check mt-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="faq-a2" name="is_active" value="1" checked>
                            <label class="form-check-label" for="faq-a2">Aktif</label>
                        </div>

                        <button type="submit" class="btn btn-pill-ppdb w-100 mt-3">Simpan FAQ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
