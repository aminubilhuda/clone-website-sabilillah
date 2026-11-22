@extends('layouts.app')

@section('title', 'Pusat Informasi PPDB ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    {{-- Hero --}}
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">PPDB</li>
                </ol>
            </nav>

            <h1>{{ setting('ppdb_hero_title') ?: 'Pusat Informasi PPDB ' . now()->year . '/' . now()->addYear()->year }}</h1>
            <p class="text-white-50 mb-4">
                {{ setting('ppdb_hero_subtitle') ?: 'Penerimaan Murid Baru (PPDB) ' . setting('nama_sekolah') . ' Tahun Pelajaran ' . now()->year . '/' . now()->addYear()->year . ' dengan program beasiswa bebas biaya pendaftaran.' }}
            </p>

            <div class="d-flex flex-wrap gap-2">
                <a href="#formulir" class="btn btn-pill-ppdb rounded-pill px-4">
                    Isi Formulir Pendaftaran <i class="fa fa-arrow-down ms-2"></i>
                </a>
                <a href="#biaya" class="btn btn-outline-light rounded-pill px-4">Lihat Rincian Biaya &amp; SPP</a>
            </div>
        </div>
    </section>

    {{-- Gelombang pendaftaran --}}
    <section class="py-5">
        <div class="container">
            <div class="section-header text-center mx-auto mb-4 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <h4 class="section-subtitle mb-2">Jadwal &amp; Periode</h4>
                <h1 class="display-6 mb-1">{{ setting('ppdb_judul_gelombang', 'Gelombang Pendaftaran') }}</h1>
                <p class="text-muted mb-0">Status gelombang ditentukan otomatis sesuai rentang tanggal pendaftaran aktif.</p>
            </div>

            <div class="row g-4">
                @forelse ($waves as $wave)
                    <div class="col-lg-4 col-md-6">
                        <div class="card admin-card h-100 border-top-4">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">{{ $wave->label }}</h5>
                                    <span class="badge {{ $wave->statusClass() }}">{{ $wave->statusLabel() }}</span>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="far fa-calendar-alt me-1"></i> Periode Pendaftaran:
                                    <strong class="d-block text-dark">{{ $wave->formattedPeriod() }}</strong>
                                </p>

                                <p class="mb-3">
                                    <small class="text-muted d-block">Biaya Formulir:</small>
                                    <strong class="fs-5 text-primary">Rp {{ number_format($wave->biaya, 0, ',', '.') }}</strong>
                                </p>

                                @if ($wave->benefit)
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">Benefit &amp; Keuntungan:</small>
                                        <ul class="list-unstyled small mb-0">
                                            @foreach (preg_split('/\r\n|\r|\n/', $wave->benefit) as $benefit)
                                                @if (trim($benefit) !== '')
                                                    <li><i class="fa fa-check text-primary me-2"></i>{{ trim($benefit) }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mt-auto">
                                    <button type="button" class="btn btn-pill-ppdb rounded-pill w-100 pilih-gelombang"
                                        data-wave="{{ $wave->id }}" data-label="{{ $wave->label }}"
                                        {{ $wave->status() === 'tutup' ? 'disabled' : '' }}>
                                        Pilih {{ $wave->label }} <i class="fa fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 alert alert-light border text-center">Gelombang pendaftaran belum diumumkan.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Persyaratan --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mx-auto mb-4 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <h4 class="section-subtitle mb-2">Ketentuan PPDB</h4>
                <h1 class="display-6 mb-1">{{ setting('ppdb_judul_persyaratan', 'Persyaratan Pendaftaran') }}</h1>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card admin-card h-100">
                        <div class="card-body p-4">
                            <h5 class="sub-title-bar mb-3">Umum</h5>
                            <h6 class="text-muted mb-3">Syarat Umum</h6>

                            @if ($syaratUmum->count())
                                <ol class="mb-0">
                                    @foreach ($syaratUmum as $syarat)
                                        <li class="mb-2">{{ $syarat->content }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <p class="text-muted mb-0">Belum ada syarat umum yang ditetapkan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card admin-card h-100">
                        <div class="card-body p-4">
                            <h5 class="sub-title-bar mb-3">Berkas</h5>
                            <h6 class="text-muted mb-3">Berkas Pendaftaran Fisik/Upload</h6>

                            @if ($syaratBerkas->count())
                                <ol class="mb-0">
                                    @foreach ($syaratBerkas as $berkas)
                                        <li class="mb-2">{{ $berkas->content }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <p class="text-muted mb-0">Belum ada berkas yang ditetapkan.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Langkah & Jalur --}}
    @if (count($langkah) || count($jalur))
        <section class="py-5">
            <div class="container">
                <div class="row g-4">
                    @if (count($langkah))
                        <div class="col-lg-6">
                            <div class="card admin-card h-100">
                                <div class="card-body p-4">
                                    <h5 class="sub-title-bar mb-3">{{ setting('ppdb_judul_langkah', 'Langkah Pendaftaran') }}</h5>
                                    <ol class="mb-0">
                                        @foreach ($langkah as $itemLangkah)
                                            <li class="mb-2">{{ $itemLangkah }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (count($jalur))
                        <div class="col-lg-6">
                            <div class="card admin-card h-100">
                                <div class="card-body p-4">
                                    <h5 class="sub-title-bar mb-3">{{ setting('ppdb_judul_jalur', 'Jalur Penerimaan') }}</h5>
                                    <div class="row g-3">
                                        @foreach ($jalur as $itemJalur)
                                            <div class="col-md-12">
                                                <div class="border rounded p-3 bg-light">
                                                    <strong class="d-block">{{ $itemJalur[0] ?? '' }}</strong>
                                                    <small class="text-muted">{{ $itemJalur[1] ?? '' }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Biaya --}}
    <section class="py-5" id="biaya">
        <div class="container">
            <div class="section-header text-center mx-auto mb-4 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <h4 class="section-subtitle mb-2">Transparan &amp; Terjangkau</h4>
                <h1 class="display-6 mb-1">{{ setting('ppdb_judul_biaya', 'Biaya Pendidikan & SPP') }}</h1>
                <p class="text-muted mb-0">
                    {{ setting('nama_sekolah') }} berkomitmen memberikan pendidikan berkualitas tinggi dengan skema
                    pembiayaan terjangkau dan beasiswa prestasi.
                </p>
            </div>

            <div class="row g-4">
                @forelse ($fees as $fee)
                    <div class="col-lg-4 col-md-6">
                        <div class="card admin-card h-100 text-center">
                            <div class="card-body p-4">
                                <span class="badge bg-light text-dark border mb-2">{{ strtoupper($fee->category) }}</span>
                                <h5 class="mb-2">{{ $fee->label }}</h5>
                                @if ($fee->description)
                                    <p class="text-muted small mb-3">{{ $fee->description }}</p>
                                @endif
                                <div class="fs-3 fw-bold text-primary">Rp {{ number_format($fee->nominal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 alert alert-light border text-center">Rincian biaya belum tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Formulir pendaftaran --}}
    <section class="py-5 bg-light" id="formulir">
        <div class="container" style="max-width: 820px;">
            <div class="section-header text-center mx-auto mb-4 wow fadeInUp" data-wow-delay="0.1s">
                <h4 class="section-subtitle mb-2">Formulir Pendaftaran</h4>
                <h1 class="display-6 mb-1">{{ setting('ppdb_judul_formulir', 'Formulir Pendaftaran Online') }}</h1>
                <p class="text-muted mb-0">
                    Isi data calon siswa secara lengkap. Setelah submit, Anda akan terhubung langsung dengan Panitia PPDB
                    via WhatsApp untuk konfirmasi berkas.
                </p>
            </div>

            <div class="card admin-card">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('ppdb.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="wave_id">Pilihan Gelombang Pendaftaran *</label>
                            <select id="wave_id" name="wave_id" class="form-select @error('wave_id') is-invalid @enderror">
                                <option value="">— Pilih Gelombang —</option>
                                @foreach ($waves as $wave)
                                    <option value="{{ $wave->id }}" {{ old('wave_id') == $wave->id ? 'selected' : '' }}>
                                        {{ $wave->label }} — {{ $wave->formattedPeriod() }} —
                                        Rp {{ number_format($wave->biaya, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>

                            @error('wave_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Nama Lengkap Calon Siswa *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="nisn">NISN (Nomor Induk Siswa Nasional)</label>
                                <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}"
                                    class="form-control @error('nisn') is-invalid @enderror">
                                @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="asal_sekolah">Asal Sekolah (SMP / MTs) *</label>
                                <input type="text" id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                    class="form-control @error('asal_sekolah') is-invalid @enderror" required>
                                @error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="jurusan">Pilihan Program Keahlian 1 (Jurusan) *</label>
                                <select id="jurusan" name="jurusan" class="form-select @error('jurusan') is-invalid @enderror" required>
                                    <option value="">Pilih Jurusan yang Diminati</option>
                                    @foreach ($jurusanOptions as $slug => $title)
                                        <option value="{{ $slug }}" {{ old('jurusan') === $slug ? 'selected' : '' }}>
                                            {{ $title }} ({{ strtoupper($slug) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="jurusan_2">Pilihan Program Keahlian 2 (Jurusan)</label>
                                <select id="jurusan_2" name="jurusan_2" class="form-select @error('jurusan_2') is-invalid @enderror">
                                    <option value="">Pilih Jurusan yang Diminati (opsional)</option>
                                    @foreach ($jurusanOptions as $slug => $title)
                                        <option value="{{ $slug }}" {{ old('jurusan_2') === $slug ? 'selected' : '' }}>
                                            {{ $title }} ({{ strtoupper($slug) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="parent_name">Nama Orang Tua / Wali *</label>
                                <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name') }}"
                                    class="form-control @error('parent_name') is-invalid @enderror" required>
                                @error('parent_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="whatsapp">Nomor WhatsApp Aktif *</label>
                                <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}"
                                    class="form-control @error('whatsapp') is-invalid @enderror" placeholder="08xxxxxxxxxx" required>
                                @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="alamat">Alamat Domisili Lengkap *</label>
                                <textarea id="alamat" name="alamat" rows="3"
                                    class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-pill-ppdb rounded-pill py-3">
                                <i class="fab fa-whatsapp me-2"></i> Kirim Pendaftaran &amp; Konfirmasi WhatsApp
                            </button>
                        </div>

                        <p class="text-muted small text-center mt-3 mb-0">
                            Setelah submit, Anda akan diarahkan ke WhatsApp panitia untuk konfirmasi berkas.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-5">
        <div class="container" style="max-width: 860px;">
            <div class="section-header text-center mx-auto mb-4 wow fadeInUp" data-wow-delay="0.1s">
                <h4 class="section-subtitle mb-2">Tanya Jawab</h4>
                <h1 class="display-6 mb-1">{{ setting('ppdb_judul_faq', 'Pertanyaan yang Sering Diajukan (FAQ)') }}</h1>
            </div>

            @forelse ($faqs as $i => $faq)
                <div class="accordion mb-3" id="ppdbFaq{{ $i }}">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#ppdbFaqBody{{ $i }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="ppdbFaqBody{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#ppdbFaq{{ $i }}">
                            <div class="accordion-body text-muted">{{ $faq->answer }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">Belum ada tanya jawab.</p>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Tombol "Pilih Gelombang X" mengisi dropdown dan menggulir ke formulir.
        document.querySelectorAll('.pilih-gelombang').forEach((button) => {
            button.addEventListener('click', () => {
                const select = document.getElementById('wave_id');
                if (select) {
                    select.value = button.dataset.wave;
                }

                document.getElementById('formulir')?.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
@endpush
