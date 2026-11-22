@extends('layouts.app')

@section('title', $page->title . ' — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('home') }}" class="text-white-50">Kompetensi Keahlian</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
            </nav>

            <h1>{{ $page->title }}</h1>

            @if ($page->subtitle)
                <p class="text-white-50 mb-0">{{ $page->subtitle }}</p>
            @endif
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5 align-items-center mb-5">
                <div class="col-lg-6">
                    <img src="{{ media_url($page->block('gambar'), 'img/jurusan-tkj.svg') }}"
                        class="img-fluid rounded shadow-sm" alt="{{ $page->title }}">
                </div>

                <div class="col-lg-6">
                    <p class="fs-5 mb-4">{{ $page->block('deskripsi', $page->subtitle) }}</p>

                    @if (is_array($page->block('keunggulan')))
                        <h4 class="sub-title-bar mb-3">Keunggulan</h4>
                        <ul class="list-unstyled mb-0">
                            @foreach ($page->block('keunggulan') as $keunggulan)
                                <li class="mb-2">
                                    <i class="fa fa-star text-primary me-2"></i>{{ $keunggulan }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            @if (is_array($page->block('prospek')))
                <div class="card admin-card mb-5">
                    <div class="card-body p-4">
                        <h4 class="sub-title-bar mb-4">Prospek Karier</h4>

                        <div class="row g-3">
                            @foreach ($page->block('prospek') as $prospek)
                                <div class="col-md-4 col-sm-6">
                                    <div class="d-flex align-items-center bg-light rounded p-3">
                                        <i class="fa fa-briefcase text-primary me-2"></i>{{ $prospek }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center mb-5">
                <a href="{{ url(setting('banner_link', 'halaman/tata-cara-pendaftaran')) }}" class="btn btn-pill-ppdb rounded-pill px-4">
                    Daftar Jurusan Ini <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>

            @if ($jurusanLain->count())
                <h3 class="sub-title-bar mb-4">Jurusan Lainnya</h3>

                <div class="row g-4">
                    @foreach ($jurusanLain as $lain)
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('jurusan.show', $lain->slug) }}" class="card-linked d-block h-100">
                                <div class="card card-artikel h-100 overflow-hidden">
                                    <img src="{{ media_url($lain->block('gambar'), 'img/jurusan-tkj.svg') }}"
                                        class="object-cover" style="height: 150px;" alt="{{ $lain->title }}" loading="lazy">

                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-0">{{ $lain->title }}</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
