@extends('layouts.app')

@section('title', 'Majalah — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Majalah</li>
                </ol>
            </nav>

            <h1>{{ setting('majalah_judul', 'Majalah') }}</h1>
            <p class="text-white-50 mb-0">{{ setting('majalah_deskripsi', 'Arsip terbitan majalah sekolah.') }}</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse ($posts as $post)
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="{{ route('majalah.detail', $post->slug) }}" class="card-linked card-translinked d-block">
                            <div class="card card-artikel h-100 border-top-3">
                                <img src="{{ media_url($post->cover_image, 'img/majalah-1.svg') }}" alt="{{ $post->title }}"
                                    class="object-cover" style="aspect-ratio: 3 / 3.8;" loading="lazy">

                                <div class="card-body text-center p-3">
                                    <h6 class="card-title mb-2">{{ $post->title }}</h6>
                                    <small class="text-muted">{{ $post->published_at?->format('F Y') }}</small>

                                    @if ($post->document_path)
                                        <span class="badge bg-success bg-opacity-10 text-success border ms-1"><i class="fa fa-file-pdf me-1"></i>PDF</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 alert alert-light border text-center">Belum ada arsip majalah.</div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">{{ $posts->onEachSide(1)->links() }}</div>
        </div>
    </section>
@endsection
