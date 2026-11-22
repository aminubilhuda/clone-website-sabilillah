@if ($bannerPpdb)
    <div class="pensekban py-2 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row align-items-center justify-content-between flex-row g-2">
                <div class="col-md-8 pe-3 d-flex align-items-center gap-3">
                    <img src="{{ media_url($bannerPpdbImage, 'img/banner-ppdb.svg') }}" alt="Banner PPDB" class="banner-leap d-none d-md-block">
                    <div class="text-white">
                        <h5 class="mb-0">{{ setting('banner_heading', 'PPDB Saat Ini Dibuka') }}</h5>
                        <small class="text-white-50">{{ $bannerPpdbText }}</small>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ str_starts_with($bannerPpdbLink, 'http') ? $bannerPpdbLink : url($bannerPpdbLink) }}"
                        class="btn btn-pill-ppdb btn-sm rounded-pill px-4">Lihat PPDB <i class="fa fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
@endif
