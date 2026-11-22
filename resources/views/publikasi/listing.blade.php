@extends('layouts.app')

@section('title', $judul . ' — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    {{-- Kepala halaman --}}
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $judul }}</li>
                </ol>
            </nav>

            <h1>{{ $judul }}</h1>
            <p class="mb-0 text-white-50">{{ $deskripsi }}</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            @if ($kategori->count())
                {{-- Filter kategori berupa pil tautan --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('publikasi.berita') }}"
                        class="btn btn-sm rounded-pill {{ $filterKategori ? 'btn-outline-dark' : 'btn-dark' }}">Semua</a>

                    @foreach ($kategori as $kat)
                        <a href="{{ route('publikasi.berita', ['kategori' => $kat->slug]) }}"
                            class="btn btn-sm rounded-pill {{ $filterKategori === $kat->slug ? 'btn-dark' : 'btn-outline-dark' }}">
                            {{ $kat->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($jurusanOptions->count())
                {{-- Filter jurusan --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <small class="text-muted me-1">Jurusan:</small>

                    <a href="{{ route('publikasi.berita', array_filter(['kategori' => $filterKategori ?: null])) }}"
                        class="btn btn-sm rounded-pill {{ $filterJurusan ? 'btn-outline-primary' : 'btn-primary' }}">Semua</a>

                    @foreach ($jurusanOptions as $slug => $nama)
                        <a href="{{ route('publikasi.berita', array_filter(['kategori' => $filterKategori ?: null, 'jurusan' => $slug])) }}"
                            class="btn btn-sm rounded-pill {{ $filterJurusan === $slug ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $nama }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($filterJurusan)
                <div class="alert alert-light border d-flex justify-content-between align-items-center">
                    <span>Menampilkan berita jurusan: <strong>{{ $jurusanOptions[$filterJurusan] ?? strtoupper($filterJurusan) }}</strong></span>
                    <a href="{{ route('publikasi.berita') }}" class="small">Lepas filter &times;</a>
                </div>
            @endif

            <div class="row g-4">
                @forelse ($posts as $post)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ $post->detailUrl() }}" class="card-linked">
                            <article class="card card-artikel border-top-4 rounded overflow-hidden">
                                <img src="{{ media_url($post->cover_image) }}" class="card-img-top object-cover"
                                    style="height: 215px;" alt="{{ $post->title }}" loading="lazy">

                                <div class="card-body p-4">
                                    @if ($post->category)
                                        <span class="badge bg-light text-dark border mb-2">{{ $post->category->name }}</span>
                                    @endif

                                    <h5 class="card-title">{{ str($post->title)->limit(70) }}</h5>
                                    <p class="text-muted small mb-0">{{ str($post->excerpt ?: strip_tags($post->content))->limit(120) }}</p>
                                </div>

                                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center p-4 pt-0">
                                    <small class="text-muted"><i class="far fa-calendar me-1"></i>{{ $post->published_at?->format('j F Y') }}</small>
                                    <small class="text-muted"><i class="far fa-eye me-1"></i>{{ $post->views }}</small>
                                </div>
                            </article>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border text-center">
                            Belum ada {{ mb_strtolower($judul) }} yang diterbitkan.
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $posts->onEachSide(1)->links() }}
            </div>
        </div>
    </section>
@endsection
