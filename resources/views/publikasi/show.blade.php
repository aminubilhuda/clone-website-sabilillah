@extends('layouts.app')

@section('title', $post->title . ' — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    {{-- Kepala halaman konten --}}
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ $ruteKembali }}" class="text-white-50">{{ $judul }}</a></li>
                    <li class="breadcrumb-item active text-truncate" style="max-width: 320px;">{{ $post->title }}</li>
                </ol>
            </nav>

            <h1 class="h2">{{ $post->title }}</h1>
            <small class="text-white-50">
                <i class="far fa-calendar me-1"></i>{{ $post->published_at?->format('j F Y') }}
                @if ($post->author)
                    &middot; <i class="far fa-user me-1"></i>{{ $post->author->name }}
                @endif
                &middot; <i class="far fa-eye me-1"></i>{{ $post->views }} dibaca
            </small>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    @if ($post->type === 'agenda')
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body d-flex flex-wrap gap-4">
                                <div>
                                    <small class="d-block text-muted">Mulai</small>
                                    <strong>{{ $post->starts_at?->isoFormat('dddd, D MMMM Y · HH:mm') ?? '-' }}</strong>
                                </div>
                                <div>
                                    <small class="d-block text-muted">Selesai</small>
                                    <strong>{{ $post->ends_at?->isoFormat('dddd, D MMMM Y · HH:mm') ?? '-' }}</strong>
                                </div>
                                @if ($post->location)
                                    <div>
                                        <small class="d-block text-muted">Lokasi</small>
                                        <strong>{{ $post->location }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <img src="{{ media_url($post->cover_image) }}" class="img-fluid rounded mb-4 w-100"
                        style="max-height: 430px; object-fit: cover;" alt="{{ $post->title }}">

                    @if ($post->excerpt)
                        <p class="fs-5 fst-italic text-muted">{{ $post->excerpt }}</p>
                    @endif

                    <div class="post-isi">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    @if ($post->document_path)
                        <a href="{{ media_url($post->document_path) }}" class="btn btn-pill-ppdb mt-4" download>
                            <i class="fa fa-download me-2"></i>Unduh Dokumen
                        </a>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        @if ($post->category)
                            <span class="badge bg-light text-dark border">{{ $post->category->name }}</span>
                        @endif

                        <span class="badge bg-light text-dark border">{{ strtoupper($post->type) }}</span>

                        @if ($post->jurusan)
                            <span class="badge bg-light text-dark border">Jurusan: {{ strtoupper($post->jurusan) }}</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                        <a href="{{ $ruteKembali }}" class="btn btn-outline-dark rounded-pill">&larr; Kembali ke {{ $judul }}</a>

                        <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill">Beranda</a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card admin-card mb-4">
                        <div class="card-body">
                            <h5 class="sub-title-bar mb-4">Tulisan Lain</h5>

                            @forelse ($related as $rel)
                                <a href="{{ $rel->detailUrl() }}" class="d-flex gap-2 mb-3 card-linked">
                                    <img src="{{ media_url($rel->cover_image) }}" alt="{{ $rel->title }}"
                                        style="width: 92px; height: 74px; object-fit: cover;" class="rounded">
                                    <div>
                                        <span class="d-block small fw-semibold">{{ str($rel->title)->limit(42) }}</span>
                                        <small class="text-muted">{{ $rel->published_at?->format('j F Y') }}</small>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted small mb-0">Belum ada tulisan lain.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
