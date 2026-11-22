<section class="sekilas">
    <div class="container text-center">
        <div class="row">
            <div class="col-md judul_sekilas">
                <h1 class="display-4">{{ setting('nama_sekolah', 'SMK Abdi Negara Tuban') }}</h1>
                <h2 class="visually-hidden">Profil {{ setting('nama_sekolah') }}</h2>
                <p class="lead">{{ setting('slogan', '') }}</p>

                <hr class="hr">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <img src="{{ media_url(setting('sekilas_gambar'), 'img/sekilas-sekolah.svg') }}" class="img-fluid gambar_sekilas kanan" loading="lazy"
                    alt="Foto sekolah {{ setting('nama_sekolah') }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-toggle-wrap" data-max="{{ strval(setting('tentang', '')) }}">
                    <p class="lead toggle-target" style="font-family: 'Open Sans', sans-serif;">
                        {{ setting('tentang', '') }}
                    </p>

                    <button type="button" class="btn btn-link px-2 py-0 text-primary d-block mx-auto toggle-text" aria-expanded="false">
                        <i class="fa fa-chevron-down"></i> <span>Selengkapnya</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
