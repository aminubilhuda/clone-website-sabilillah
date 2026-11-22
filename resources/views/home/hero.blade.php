{{-- Carousel hero --}}
<div class="hero-wrap wow fadeIn" data-wow-delay="0.1s">
    <div class="owl-carousel hero-carousel" aria-label="Sorotan utama sekolah">
        @foreach ($heroSliders as $slider)
            <div class="hero-item position-relative">
                <img class="slider-media w-100" src="{{ media_url($slider->image, 'img/hero-1.svg') }}" alt="{{ $slider->title . ' — ' . setting('nama_sekolah') }}">
                <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100">
                    <div class="hero-inner container">
                        @if ($slider->title)
                            <a href="{{ str_starts_with($slider->link, 'http') ? $slider->link : url($slider->link) }}" class="hero-title d-block h1">
                                {{ $slider->title }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
