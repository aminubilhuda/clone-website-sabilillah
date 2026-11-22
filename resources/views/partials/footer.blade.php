{{-- Footer utama --}}
<div class="container-fluid bg-dark text-white-50 footer mt-5 pt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <h5 class="section-footer-title mb-4">Tentang {{ setting('nama_sekolah', 'SMK Abdi Negara Tuban') }}</h5>
                <p>{{ setting('tentang', 'Sekolah menengah kejuruan yang mencetak lulusan siap kerja.') }}</p>

                <div class="d-flex pt-2">
                    @foreach (['facebook' => 'facebook-f', 'instagram' => 'instagram', 'tiktok' => 'tiktok', 'youtube' => 'youtube'] as $kanal => $icon)
                        <a class="btn btn-outline-light btn-social" href="{{ setting($kanal, '#') }}"
                            target="_blank" rel="noopener" aria-label="{{ $kanal }}"><i class="fab fa-{{ $icon }}"></i></a>
                    @endforeach
                </div>
            </div>

            @foreach ($sharedMenus->where('position', 'footer') as $kolom)
                <div class="col-lg-2 col-md-6">
                    <h5 class="section-footer-title mb-4">{{ $kolom->label }}</h5>

                    <a class="btn btn-link btn-sm px-0" href="{{ str_starts_with($kolom->url, 'http') ? $kolom->url : url($kolom->url) }}">{{ $kolom->label }}</a>

                    @foreach ($kolom->children as $tautan)
                        <a class="btn btn-link btn-sm px-0" href="{{ str_starts_with($tautan->url, 'http') ? $tautan->url : url($tautan->url) }}">{{ $tautan->label }}</a>
                    @endforeach
                </div>
            @endforeach

            <div class="col-lg-4 col-md-6">
                <h5 class="section-footer-title mb-4">{{ setting('footer_kontak_judul', 'Kontak Sekolah') }}</h5>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ setting('alamat', 'Tuban, Jawa Timur') }}</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ setting('telfon', '-') }}</p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>{{ setting('email', '-') }}</p>

                <div class="stat-pengunjung mt-4">
                    <h6 class="text-white-50 mb-3 text-uppercase" style="letter-spacing: 1.5px;">{{ setting('footer_stats_judul', 'Statistik Website') }}</h6>
                    <div class="row g-2 text-white">
                        <div class="col-6">
                            <div class="stat-kotak bg-white bg-opacity-10 rounded p-2">
                                <small class="d-block text-white-50">Hari Ini</small>
                                <strong>{{ number_format($sharedVisitorStats['hari_ini'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-kotak bg-white bg-opacity-10 rounded p-2">
                                <small class="d-block text-white-50">Kemarin</small>
                                <strong>{{ number_format($sharedVisitorStats['kemarin'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-kotak bg-white bg-opacity-10 rounded p-2">
                                <small class="d-block text-white-50">Bulan Ini</small>
                                <strong>{{ number_format($sharedVisitorStats['bulan_ini'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-kotak bg-white bg-opacity-10 rounded p-2">
                                <small class="d-block text-white-50">Total</small>
                                <strong>{{ number_format($sharedVisitorStats['total'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright py-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; {{ now()->year }} <a class="border-bottom text-white" href="{{ route('home') }}">{{ setting('nama_sekolah', 'SMK Abdi Negara Tuban') }}</a>. Hak cipta dilindungi.
                </div>
            </div>
        </div>
    </div>
</div>
