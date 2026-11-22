@extends('layouts.admin')

@section('title', 'Galeri / Album')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Galeri / Album</h1>
            <small class="text-muted">Album foto dan video kegiatan sekolah.</small>
        </div>

        <a href="{{ route('admin.albums.create') }}" class="btn btn-pill-ppdb rounded-pill"><i class="fa fa-plus me-1"></i> Album Baru</a>
    </div>

    <div class="row g-4">
        @forelse ($albums as $album)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card admin-card h-100">
                    <img src="{{ media_url($album->cover_image, 'img/album-1.svg') }}" class="object-cover" style="height:150px;" alt="{{ $album->title }}">
                    <div class="card-body p-3">
                        <h6 class="mb-1">{{ $album->title }}</h6>
                        <small class="text-muted">{{ $album->items_count }} item &middot; {{ $album->kind }}</small>
                    </div>

                    <div class="card-footer bg-transparent border-0 d-flex gap-1 pb-3">
                        <a href="{{ route('admin.albums.edit', $album) }}" class="btn btn-sm btn-outline-primary flex-fill"><i class="fa fa-edit"></i> Kelola</a>

                        <form action="{{ route('admin.albums.destroy', $album) }}" method="POST"
                            onsubmit="return confirm('Hapus album beserta isinya?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-muted text-center py-5">Belum ada album.</div>
        @endforelse
    </div>
@endsection
