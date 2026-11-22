<section class="testimoni-section pt-5 pb-4">
    <div class="container">
        <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h4 class="section-subtitle mb-2">{{ setting('sub_testimoni', 'Kata Mereka') }}</h4>
            <h1 class="display-6 mb-1">{{ setting('judul_testimoni', 'Kata Alumni & Mitra Industri') }}</h1>
        </div>

        <div class="owl-carousel testimoni-carousel wow fadeIn" data-wow-delay="0.3s">
            @forelse ($tokoh as $post)
                <div class="testimoni-item text-center">
                    <img class="rounded-circle shadow mb-3" src="{{ media_url($post->cover_image, 'img/avatar-1.svg') }}" style="width: 90px; height: 90px; object-fit: cover;" alt="{{ $post->title }}" loading="lazy">

                    <p class="fs-5 fst-italic mb-3">{{ $post->content }}</p>

                    <h5 class="judul-post mb-0">{{ $post->title }}</h5>
                </div>
            @empty
                <p class="text-center text-muted">Belum ada testimoni.</p>
            @endforelse
        </div>
    </div>
</section>
