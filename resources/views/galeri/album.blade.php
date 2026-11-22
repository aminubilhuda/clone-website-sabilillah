@extends('layouts.app')

@section('title', $album->title . ' — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ $album->kind === 'video' ? route('publikasi.video') : route('publikasi.foto') }}" class="text-white-50">
                            {{ $album->kind === 'video' ? 'Video' : 'Album Foto' }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $album->title }}</li>
                </ol>
            </nav>

            <h1>{{ $album->title }}</h1>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            @if ($album->description)
                <p class="text-muted mb-4">{{ $album->description }}</p>
            @endif

            <div class="row g-3">
                @forelse ($album->items as $item)
                    <div class="col-lg-4 col-md-6 col-6">
                        <figure class="mb-4">
                            @if ($item->isVideo())
                                <a href="{{ $item->media_url }}" target="_blank" rel="noopener" class="d-block position-relative galeri-item">
                                    <img src="{{ media_url($item->media_path, 'img/album-1.svg') }}" style="height:210px; object-fit:cover;"
                                        class="w-100 rounded shadow-sm" alt="{{ $item->caption ?: $album->title }}">

                                    <span class="position-absolute top-50 start-50 translate-middle badge rounded-circle bg-dark bg-opacity-75 p-3">
                                        <i class="fa fa-play"></i>
                                    </span>
                                </a>
                            @else
                                <a href="{{ media_url($item->media_path, 'img/album-1.svg') }}" target="_blank" rel="noopener" class="d-block galeri-item">
                                    <img src="{{ media_url($item->media_path, 'img/album-1.svg') }}" style="height:210px; object-fit:cover;"
                                        class="w-100 rounded shadow-sm" alt="{{ $item->caption ?: $album->title }}" loading="lazy">
                                </a>
                            @endif

                            @if ($item->caption)
                                <figcaption class="text-muted small mt-2">{{ $item->caption }}</figcaption>
                            @endif
                        </figure>
                    </div>
                @empty
                    <div class="col-12 alert alert-light border text-center">Album ini belum memiliki item.</div>
                @endforelse
            </div>

            <a href="{{ $album->kind === 'video' ? route('publikasi.video') : route('publikasi.foto') }}"
                class="btn btn-outline-dark rounded-pill mt-3">&larr; Kembali</a>
        </div>
    </section>
@endsection
