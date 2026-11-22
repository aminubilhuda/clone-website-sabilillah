@extends('layouts.admin')

@section('title', 'Halaman & Jurusan')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Halaman &amp; Jurusan</h1>
            <small class="text-muted">Halaman profil dan kompetensi keahlian (jurusan).</small>
        </div>

        <a href="{{ route('admin.pages.create') }}" class="btn btn-pill-ppdb rounded-pill"><i class="fa fa-plus me-1"></i> Halaman Baru</a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Judul</th>
                        <th>Slug</th>
                        <th>Grup</th>
                        <th>Publikasi</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="fw-semibold text-dark">{{ $page->title }}</a>
                            </td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td><span class="badge bg-light text-dark border">{{ $page->group }}</span></td>
                            <td>
                                <span class="badge {{ $page->is_published ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $page->is_published ? 'terpublikasi' : 'draf' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ $page->group === 'jurusan' ? route('jurusan.show', $page->slug) : route('pages.show', $page->slug) }}"
                                    target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat"><i class="fa fa-external-link-alt"></i></a>

                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus halaman ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                </form>

                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">Belum ada halaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
