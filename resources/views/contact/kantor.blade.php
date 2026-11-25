@extends('layouts.app')

@section('title', 'Hubungi Kami — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hubungi Kami</li>
                </ol>
            </nav>

            <h1>Hubungi Kami</h1>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <h3 class="sub-title-bar mb-4">{{ setting('kontak_judul_info', 'Alamat & Kanal Resmi') }}</h3>

                    <p class="mb-2"><i class="fa fa-map-marker-alt text-dark me-3"></i>{{ setting('alamat', 'Kota Malang') }}</p>
                    <p class="mb-2"><i class="fa fa-phone-alt text-dark me-3"></i>{{ setting('telfon', '-') }}</p>
                    <p class="mb-2"><i class="fa fa-envelope text-dark me-3"></i>{{ setting('email', '-') }}</p>
                    <p class="mb-4"><i class="fa fa-clock text-dark me-3"></i>{{ setting('jam_layanan', '-') }}</p>

                    <div class="d-flex gap-2 mt-3">
                        @foreach (['facebook' => 'facebook-f', 'instagram' => 'instagram', 'tiktok' => 'tiktok', 'youtube' => 'youtube'] as $kanal => $icon)
                            <a href="{{ setting($kanal, '#') }}" target="_blank" rel="noopener"
                                class="btn btn-outline-dark btn-social-mini" aria-label="{{ $kanal }}"><i class="fab fa-{{ $icon }}"></i></a>
                        @endforeach
                    </div>

                    <a href="https://wa.me/{{ setting('whatsapp', '620000000000') }}" target="_blank" rel="noopener"
                        class="btn btn-pill-ppdb mt-4"><i class="fab fa-whatsapp me-2"></i>Chat WhatsApp Sekolah</a>
                </div>

                <div class="col-lg-7">
                    <h3 class="sub-title-bar mb-4">{{ setting('kontak_judul_peta', 'Peta Lokasi') }}</h3>

                    @if (filled($gmapsEmbed) && str_starts_with((string) $gmapsEmbed, 'https://'))
                        <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                            <iframe src="{{ $gmapsEmbed }}" style="border:0;" sandbox="allow-scripts allow-same-origin allow-popups" referrerpolicy="no-referrer-when-downgrade" allowfullscreen loading="lazy" title="Peta sekolah"></iframe>
                        </div>
                    @else
                        <div class="alert alert-warning">Petakan Google Maps belum dikonfigurasi panel admin (setel key <code>gmaps_embed</code> di Pengaturan).</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
