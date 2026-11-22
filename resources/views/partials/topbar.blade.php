<div class="top-bar d-none d-md-block wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-center text-md-start">
                <div class="topbar-info">
                    <span class="me-3"><i class="fa fa-map-marker-alt me-2"></i>{{ setting('alamat', 'Kota Malang') }}</span>
                    <span class="me-3"><i class="fa fa-phone-alt me-2"></i>{{ setting('telfon', '-') }}</span>
                    <span><i class="fa fa-envelope me-2"></i>{{ setting('email', '-') }}</span>
                </div>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <div class="topbar-social">
                    @foreach (['facebook' => 'facebook-f', 'instagram' => 'instagram', 'tiktok' => 'tiktok', 'youtube' => 'youtube'] as $kanal => $icon)
                        <a href="{{ setting($kanal, '#') }}" target="_blank" rel="noopener" aria-label="{{ $kanal }}" class="ms-2"><i class="fab fa-{{ $icon }}"></i></a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
