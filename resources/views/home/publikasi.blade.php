<section class="publikasi-section py-5">
    <div class="container">
        <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h4 class="section-subtitle mb-2">{{ setting('sub_publikasi', 'Zona Publikasi') }}</h4>
            <h1 class="display-6 mb-1">{{ setting('judul_publikasi', 'Berita, Agenda, dan Tulisan Inspirasi') }}</h1>
        </div>

        {{-- Carousel berita --}}
        <div class="row g-4 wow fadeInUp" data-wow-delay="0.2s">
            <div class="col-12">
                <div class="owl-carousel berita-carousel">
                    @forelse ($berita as $post)
                        <div class="causes-item d-flex flex-column bg-white border-top border-3 rounded-top overflow-hidden" style="min-height:400px;">
                            <div class="position-relative" style="height: 220px;">
                                <a href="{{ $post->detailUrl() }}" class="d-block w-100 h-100">
                                    <img class="w-100 h-100 object-cover" src="{{ media_url($post->cover_image) }}"
                                        alt="{{ $post->title }}" loading="lazy">
                                </a>

                                @if ($post->category)
                                    <a href="{{ route('publikasi.berita', ['kategori' => $post->category->slug]) }}"
                                        class="badge bg-dark bg-opacity-75 text-white position-absolute bottom-0 start-0 m-2 rounded-pill px-3 py-2 text-decoration-none">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                            </div>

                            <div class="p-4 pb-0">
                                <a href="{{ $post->detailUrl() }}" class="judul-post">
                                    <h5 class="judul-post">{{ str($post->title)->limit(60) }}</h5>
                                </a>

                                <p class="kutipan-post">{{ str($post->excerpt ?: strip_tags($post->content))->limit(110) }}</p>
                            </div>

                            <div class="p-4 pt-0">
                                <div class="d-flex align-items-end justify-content-between date-post">
                                    {{ $post->published_at?->format('j F Y') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light border">Belum ada berita yang diterbitkan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Baris agenda + tulisan --}}
        <div class="row g-4 mt-3">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.2s">
                <h3 class="sub-title-bar mb-4">{{ setting('judul_agenda', 'Agenda') }}</h3>

                <div class="owl-carousel agenda-carousel">
                    @forelse ($agenda as $post)
                        <a href="{{ $post->detailUrl() }}" class="agenda-item d-flex align-items-center bg-white p-2 mb-3 border-bottom">
                            <div class="agenda-tanggal text-center p-3 me-3">
                                <h5 class="mb-0 fw-bold">{{ $post->starts_at?->day ?? 'an' }}</h5>
                                <small class="text-uppercase">{{ $post->starts_at?->translatedFormat('M') ?? '-' }}</small>
                            </div>

                            <div class="ps-2 overflow-hidden">
                                <h6 class="judul-post mb-1 text-truncate">{{ $post->title }}</h6>
                                @if ($post->location)
                                    <small class="text-muted"><i class="fa fa-map-marker-alt me-1"></i>{{ $post->location }}</small>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="alert alert-light border">Belum ada agenda terdekat.</div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                <h3 class="sub-title-bar mb-4">{{ setting('judul_tulisan', 'Tulisan Inspirasi') }}</h3>

                <div class="owl-carousel tulisan-carousel">
                    @forelse ($tulisan as $post)
                        <a href="{{ $post->detailUrl() }}" class="tulisan-item d-flex bg-white border border-top-4 rounded overflow-hidden mb-3">
                            <img src="{{ media_url($post->cover_image) }}" class="img-fluid tulisan-cover" style="width: 150px; object-fit: cover;" alt="{{ $post->title }}" loading="lazy">
                            <div class="w-100 d-flex flex-column justify-content-center p-3 overflow-hidden">
                                <h5 class="judul-post mb-1">{{ str($post->title)->limit(50) }}</h5>
                                <p class="date-post mb-0">{{ $post->published_at?->format('j F Y') }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="alert alert-light border">Belum ada tulisan inspirasi.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
