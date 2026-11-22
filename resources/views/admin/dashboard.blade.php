@extends('layouts.admin')

@section('title', 'Dashboard')

@section('konten')
    <h1 class="h3 mb-1">Dashboard</h1>
    <p class="text-muted mb-4">Ringkasan konten dan kunjungan situs — {{ setting('nama_sekolah') }}.</p>

    <div class="row g-3 mb-4">
        @foreach ($counts as $statistik)
            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ $statistik['url'] }}" class="text-decoration-none">
                    <div class="stat-mini">
                        <small>{{ $statistik['label'] }}</small>
                        <h4>{{ $statistik['angka'] }}</h4>
                    </div>
                </a>
            </div>
        @endforeach

        <div class="col-xl-12 mb-4">
            <div class="card admin-card h-100">
                <div class="card-body">
                    <h5 class="mb-4">Tren Kunjungan 14 Hari Terakhir</h5>

                    <div class="d-flex align-items-end gap-1 stat-tren" style="height: 190px;">
                        @php
                            $nilaiMax = max($trenPengunjung->max('nilai'), 1);
                        @endphp

                        @foreach ($trenPengunjung as $hari)
                            <div class="flex-fill text-center" title="{{ $hari['label'] }}: {{ $hari['nilai'] }}">
                                <div class="tren-batang mx-auto" data-tinggi="{{ ceil(($hari['nilai'] / $nilaiMax) * 100) }}"></div>
                                <small class="text-muted d-none d-md-block">{{ $hari['label'] }}</small>
                            </div>
                        @endforeach
                    </div>

                    <div class="row g-3 mt-4">
                        @foreach ($beritaSebar as $label => $nilai)
                            <div class="col-4">
                                <div class="stat-mini text-center">
                                    <small>{{ $label }}</small>
                                    <h4>{{ number_format($nilai, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Artikel terbaru --}}
        <div class="col-xl-12">
            <div class="card admin-card">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center px-3 py-3">
                        <h5 class="mb-0">Artikel Terbaru</h5>

                        <a href="{{ route('admin.posts.create', ['type' => 'berita']) }}" class="btn btn-sm btn-pill-ppdb rounded-pill">
                            <i class="fa fa-plus me-1"></i> Artikel Baru
                        </a>
                    </div>

                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Judul</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Penulis</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($terbaru as $post)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="fw-semibold text-dark">{{ str($post->title)->limit(60) }}</a>
                                        <small class="d-block text-muted">{{ $post->created_at->format('j F Y') }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $post->type }}</span></td>
                                    <td>
                                        <span class="badge {{ $post->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $post->status === 'published' ? 'terbit' : 'draf' }}</span>
                                    </td>
                                    <td class="small">{{ $post->author?->name ?? '-' }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ $post->detailUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat"><i class="fa fa-external-link-alt"></i></a>

                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('skrip')
    <script>
        // Batang grafik mini dari data-tinggi, mengikuti warna tema admin.
        const temaPrimer = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#1b5faa';
        const temaGelap = getComputedStyle(document.documentElement).getPropertyValue('--dark').trim() || '#0e3d73';

        document.querySelectorAll('.tren-batang').forEach((element) => {
            element.style.width = '100%';
            element.style.minHeight = '6px';
            element.style.borderRadius = '6px';
            element.style.background = `linear-gradient(180deg, ${temaPrimer}, ${temaGelap})`;
            element.style.height = element.dataset.tinggi + '%';
        });
    </script>
@endsection
