@extends('layouts.app')

@section('title', $judul . ' — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $judul }}</li>
                </ol>
            </nav>

            <h1>{{ $judul }}</h1>
            <p class="text-white-50 mb-0">
                {{ $kind === 'video' ? 'Galeri video kegiatan sekolah.' : 'Galeri foto kegiatan sekolah.' }}
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse ($albums as $album)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('album.show', $album->slug) }}" class="card-linked d-block h-100">
                            <div class="card card-artikel h-100 overflow-hidden">
                                <div class="position-relative">
                                    <img src="{{ media_url($album->cover_image, 'img/album-1.svg') }}"
                                        class="card-img-top object-cover" style="height: 230px;" alt="{{ $album->title }}" loading="lazy">

                                    <span class="position-absolute badge rounded-pill bg-dark bg-opacity-75 text-white m-2 top-0 end-0">
                                        <i class="fa fa-{{ $album->kind === 'video' ? 'play' : 'camera' }} me-1"></i>{{ $album->items_count }} item
                                    </span>
                                </div>

                                <div class="card-body p-4">
                                    <h5 class="card-title">{{ $album->title }}</h5>
                                    <p class="text-muted small mb-0">{{ str($album->description ?: '')->limit(90) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 alert alert-light border text-center">Belum ada {{ $kind }} album.</div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">{{ $albums->onEachSide(1)->links() }}</div>
        </div>
    </section>
@endsection
