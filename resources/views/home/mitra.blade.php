{{-- Marquee mitra kerja sama + lisensi --}}
<div class="logo-marquee py-4 bg-white border-top wow fadeIn" data-wow-delay="0.2s" data-marquee-speed="32">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <h4 class="section-subtitle mb-0">{{ setting('judul_mitra', 'Mitra Industri') }}</h4>
        </div>

        <div class="logo-marquee__viewport">
            <div class="logo-marquee__track js-marquee-track">
                <div class="logo-marquee__group">
                    @forelse ($klien as $partner)
                        <div class="logo-marquee__item"><img src="{{ media_url($partner->logo_path, 'img/partner-1.svg') }}" alt="{{ $partner->name }}" loading="lazy" decoding="async"></div>
                    @empty
                        <div class="logo-marquee__item"><span class="text-muted">Belum ada mitra industri terdaftar</span></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="logo-marquee logo-marquee--compact py-3 bg-light border-top wow fadeIn" data-wow-delay="0.25s" data-marquee-speed="24">
    <div class="container text-center mb-2">
        <h4 class="section-subtitle mb-0">{{ setting('judul_lisensi', 'Lembaga Sertifikasi Profesi') }}</h4>
    </div>

    <div class="logo-marquee__viewport">
        <div class="logo-marquee__track js-marquee-track">
            <div class="logo-marquee__group">
                @forelse ($lisensi as $l)
                    <div class="logo-marquee__item logo-marquee__item--small"><img src="{{ media_url($l->logo_path, 'img/lisensi-1.svg') }}" alt="{{ $l->name }}" loading="lazy" decoding="async"></div>
                @empty
                    <div class="logo-marquee__item"><span class="text-muted">Belum ada lembaga sertifikasi terdaftar</span></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
