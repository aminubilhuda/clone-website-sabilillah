@extends('layouts.app')

@section('title', $post->title . ' — Majalah ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('majalah.index') }}" class="text-white-50">Majalah</a></li>
                    <li class="breadcrumb-item active">{{ $post->title }}</li>
                </ol>
            </nav>

            <h1 class="h2">{{ $post->title }}</h1>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center align-items-start g-5">
                <div class="col-md-5">
                    <img src="{{ media_url($post->cover_image, 'img/majalah-1.svg') }}"
                        class="img-fluid rounded shadow" alt="{{ $post->title }}">
                </div>

                <div class="col-md-6">
                    <h3>{{ $post->title }}</h3>

                    <p class="text-muted small mb-3">
                        <i class="far fa-calendar me-1"></i>Diterbitkan: {{ $post->published_at?->format('j F Y') }}
                        &middot; dibaca {{ $post->views }}x
                    </p>

                    <p>{!! nl2br(e($post->content)) !!}</p>

                    @if ($post->document_path)
                        <a href="{{ media_url($post->document_path) }}" class="btn btn-pill-ppdb mt-2" download>
                            <i class="fa fa-file-pdf me-2"></i>Unduh PDF
                        </a>
                    @else
                        <p class="text-muted small mt-3"><i class="fa fa-info-circle me-1"></i>Edisi ini belum memiliki berkas PDF.</p>
                    @endif

                    <a href="{{ route('majalah.index') }}" class="btn btn-outline-dark rounded-pill mt-4">&larr; Kembali ke Majalah</a>
                </div>
            </div>
        </div>
    </section>
@endsection
