<section class="highlight-section py-5 bg-dark">
    <div class="container">
        <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h4 class="section-subtitle mb-2">{{ setting('sub_sorotan', 'Sorotan') }}</h4>
            <h1 class="display-6 text-white mb-1">{{ setting('judul_sorotan', 'Menyorot Kegiatan Sekolah') }}</h1>
        </div>

        <div class="owl-carousel highlight-carousel wow" data-wow-delay="0.3s">
            @forelse ($highlight as $post)
                <div class="highlight-item overflow-hidden rounded-3 bg-dark bg-opacity-25 h-100">
                    <a href="{{ $post->detailUrl() }}" class="d-block position-relative" style="height: 200px;">
                        <img src="{{ media_url($post->cover_image) }}" class="w-100 h-100 object-cover" alt="{{ $post->title }}" loading="lazy">
                    </a>
                    <div class="p-4">
                        <a href="{{ $post->detailUrl() }}" class="judul-post text-white d-block">{{ str($post->title)->limit(60) }}</a>
                        <small class="date-post text-white-50 d-block mt-2">{{ $post->published_at?->format('j F Y') }}</small>

                        <p class="kutipan-post text-white-50 mt-2 mb-0">{{ str($post->excerpt ?: strip_tags($post->content))->limit(150) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-white-50">Belum ada sorotan saat ini.</p>
            @endforelse
        </div>
    </div>
</section>
