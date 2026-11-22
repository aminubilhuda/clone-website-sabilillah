@extends('layouts.app')

@section('title', $judulBalon ?? 'Agenda — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Agenda</li>
                </ol>
            </nav>

            <h1>Agenda</h1>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h3 class="sub-title-bar mb-4">{{ setting('agenda_judul_mendatang', 'Agenda Mendatang') }}</h3>

            <div class="row g-4 mb-5">
                @forelse ($aktif as $post)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ $post->detailUrl() }}" class="card-linked d-block h-100">
                            <div class="card card-artikel h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="agenda-tanggal text-center p-2 px-3">
                                            <h5 class="mb-0 fw-bold">{{ $post->starts_at?->day ?? 'an' }}</h5>
                                            <small class="text-uppercase">{{ $post->starts_at?->translatedFormat('M') ?? '-' }}</small>
                                        </div>

                                        <div>
                                            <small class="text-muted d-block"><i class="far fa-clock me-1"></i>{{ $post->starts_at?->format('H:i') }}</small>
                                            @if ($post->ends_at)
                                                <small class="text-muted d-block">s.d. {{ $post->ends_at->format('H:i') }}</small>
                                            @endif
                                        </div>
                                    </div>

                                    <h5 class="card-title mb-1">{{ $post->title }}</h5>

                                    @if ($post->location)
                                        <small class="text-muted"><i class="fa fa-map-marker-alt me-1"></i>{{ $post->location }}</small>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 alert alert-light border">Belum ada agenda mendatang.</div>
                @endforelse
            </div>

            <h3 class="sub-title-bar mb-4">{{ setting('agenda_judul_lampau', 'Agenda Lampau') }}</h3>

            <div class="row g-3">
                @forelse ($lampau as $post)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ $post->detailUrl() }}" class="card-linked d-block">
                            <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                                <span>{{ $post->title }}</span>
                                <small class="text-muted ms-2">{{ $post->starts_at?->format('j F Y') }}</small>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-muted small">Belum ada agenda lampau.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
