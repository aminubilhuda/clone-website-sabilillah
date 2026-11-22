<section class="program-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
            <h4 class="section-subtitle mb-2">{{ setting('sub_jurusan', 'Kompetensi Keahlian') }}</h4>
            <h1 class="display-6 mb-1">{{ setting('judul_jurusan', 'Jurusan di ' . setting('nama_sekolah')) }}</h1>
        </div>

        <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5">
            @forelse ($jurusanHalaman as $i => $jurusan)
                <div class="col wow fadeInUp" data-wow-delay="{{ 0.1 + $i * 0.05 }}s">
                    <div class="program-card h-100 d-flex flex-column bg-white border-top-4 rounded overflow-hidden">
                        <a href="{{ route('jurusan.show', $jurusan->slug) }}" class="position-relative" style="height: 170px;">
                            <img src="{{ media_url($jurusan->block('gambar'), 'img/jurusan-tkj.svg') }}"
                                class="w-100 h-100 object-cover" alt="{{ $jurusan->title }}" loading="lazy">
                        </a>
                        <div class="text-center p-3 d-flex flex-column flex-grow-1">
                            <h5 class="judul-post mb-2">{{ $jurusan->title }}</h5>

                            @if ($jurusan->block('deskripsi'))
                                <p class="kutipan-post mb-2">{{ str($jurusan->block('deskripsi'))->limit(80) }}</p>
                            @endif

                            <a href="{{ route('jurusan.show', $jurusan->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill mt-auto">Lihat Jurusan</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 alert alert-light border">Data jurusan belum tersedia.</div>
            @endforelse
        </div>

        {{-- BKK & PKL --}}
        <div class="mt-4 wow fadeInUp" data-wow-delay="0.3s">
            <div class="program-card d-flex bg-white border-top-4 rounded overflow-hidden">
                <img src="{{ media_url($bkkPage?->block('gambar'), 'img/bkk-pkl.svg') }}" class="program-tall-img d-none d-md-block" alt="BKK dan PKL SMK Abdi Negara Tuban">

                <div class="p-4 d-flex flex-column justify-content-center">
                    <h4 class="judul-post mb-2">{{ $bkkPage->title ?? 'BKK & PKL' }}</h4>
                    <p class="kutipan-post mb-3">
                        {{ $bkkPage ? str($bkkPage->block('deskripsi', 'Bursa Kerja Khusus dan Praktik Kerja Lapangan menghubungkan siswa dengan dunia usaha dan dunia industri.'))->limit(180) : 'Bursa Kerja Khusus dan Praktik Kerja Lapangan menghubungkan siswa dengan dunia usaha dan dunia industri.' }}
                    </p>

                    <div>
                        <a href="{{ $bkkPage ? route('pages.show', $bkkPage->slug) : route('contact.kerja-sama') }}"
                            class="btn btn-sm btn-outline-primary rounded-pill">Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
