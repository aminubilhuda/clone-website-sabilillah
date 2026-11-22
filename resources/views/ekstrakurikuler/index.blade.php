@extends('layouts.app')

@section('title', 'Ekstrakurikuler — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ekstrakurikuler</li>
                </ol>
            </nav>

            <h1>Ekstrakurikuler</h1>
            <p class="text-white-50 mb-0">Kegiatan pengembangan minat, bakat, dan karakter siswa.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse ($items as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card admin-card h-100 text-center">
                            <div class="card-body p-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3"
                                    style="width: 64px; height: 64px;">
                                    <i class="fa fa-2x {{ $item->iconClass() }} text-primary"></i>
                                </div>

                                <h5 class="mb-2">{{ $item->name }}</h5>

                                @if ($item->description)
                                    <p class="text-muted small mb-0">{{ $item->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 alert alert-light border text-center">Belum ada data ekstrakurikuler.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
