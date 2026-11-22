@extends('layouts.app')

@section('title', 'Kerja Sama — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kerja Sama</li>
                </ol>
            </nav>

            <h1>Kerja Sama</h1>
            <p class="text-white-50 mb-0">Bentuk kolaborasi dan kemitraan bersama sekolah.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5 align-items-start">
                <div class="col-lg-6">
                    <h3 class="sub-title-bar mb-3">{{ setting('kerja_sama_judul', 'Bentuk Kerja Sama') }}</h3>
                    <ul class="list-group list-group-flush">
                        @foreach (preg_split('/\r\n|\r|\n/', strval(setting('kerja_sama_items', ''))) as $itemKerjaSama)
                            @if (trim($itemKerjaSama) !== '')
                                <li class="list-group-item"><i class="fa fa-check text-primary me-2"></i>{{ trim($itemKerjaSama) }}</li>
                            @endif
                        @endforeach
                    </ul>

                    <h5 class="mt-5">{{ setting('kerja_sama_ajakan_judul', 'Ingin berkolaborasi?') }}</h5>
                    <p class="text-muted">{{ setting('kerja_sama_ajakan_teks', 'Silakan hubungi kantor melalui klik tombol berikut — tim kami siap menjawab.') }}</p>

                    <div class="d-flex gap-2 mt-3">
                        <a href="https://wa.me/{{ setting('whatsapp', '620000000000') }}" target="_blank" rel="noopener" class="btn btn-pill-ppdb">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>

                        <a href="{{ route('contact.kantor') }}" class="btn btn-outline-dark rounded-pill px-4">Lihat Alamat</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h3 class="sub-title-bar mb-4">{{ setting('kerja_sama_mitra_judul', 'Mitra Kami') }}</h3>

                    <div class="row g-3">
                        @forelse ($klien as $m)
                            <div class="col-6 col-md-4">
                                <div class="border rounded text-center p-3 h-100 d-flex align-items-center justify-content-center bg-white">
                                    <img src="{{ media_url($m->logo_path, 'img/partner-1.svg') }}" class="img-fluid" alt="{{ $m->name }}" loading="lazy">
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted small">Belum ada mitra terdaftar.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
