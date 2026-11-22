@extends('layouts.app')

@section('title', 'FAQ — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>

            <h1>{{ setting('faq_judul', 'Tanya Jawab') }}</h1>
            <p class="text-white-50 mb-0">{{ setting('faq_deskripsi', 'Pertanyaan yang paling sering diajukan.') }}</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container" style="max-width: 860px;">
            @forelse ($faqs as $kategori => $daftarFaq)
                <h4 class="sub-title-bar mb-4 mt-2">{{ $kategori ?? 'Umum' }}</h4>

                <div class="accordion mb-5" id="faq-{{ str($kategori)->slug() }}">
                    @foreach ($daftarFaq as $i => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="kepala-{{ str($kategori)->slug() }}-{{ $i }}">
                                <button class="accordion-button {{ !($i === 0) ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq-{{ str($kategori)->slug() }}-{{ $i }}"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>

                            <div id="faq-{{ str($kategori)->slug() }}-{{ $i }}"
                                class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                aria-labelledby="kepala-{{ str($kategori)->slug() }}-{{ $i }}"
                                data-bs-parent="#faq-{{ str($kategori)->slug() }}">
                                <div class="accordion-body text-muted">{{ $faq->answer }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="alert alert-light border text-center">Belum ada tanya jawab.</div>
            @endforelse
        </div>
    </section>
@endsection
