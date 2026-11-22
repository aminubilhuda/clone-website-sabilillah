@extends('layouts.app')

@section('title', $page->title . ' — ' . setting('nama_sekolah', 'SMK Abdi Negara Tuban'))

@section('konten')
    <section class="page-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="d-inline-block rounded mb-3 px-3 py-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                    <li class="breadcrumb-item active">{{ $page->title }}</li>
                </ol>
            </nav>

            <h1>{{ $page->title }}</h1>

            @if ($page->subtitle)
                <p class="text-white-50 mb-0">{{ $page->subtitle }}</p>
            @endif
        </div>
    </section>

    <section class="py-5">
        <div class="container" style="max-width: 900px;">
            @if ($page->block('intro'))
                <p class="lead">{{ $page->block('intro') }}</p>
            @endif

            @php
                $blocks = $page->blocks ?? [];
            @endphp

            @if (isset($blocks['isi']) && is_array($blocks['isi']))
                @foreach ($blocks['isi'] as $paragraf)
                    <p class="text-body">{{ $paragraf }}</p>
                @endforeach
            @endif

            @if (isset($blocks['sambutan']))
                <div class="card admin-card my-4">
                    <div class="card-body p-4">
                        <h3 class="sub-title-bar mb-3">Sambutan</h3>
                        <p class="mb-0">{{ $blocks['sambutan'] }}</p>
                    </div>
                </div>
            @endif

            @if (isset($blocks['visi']))
                <div class="card admin-card my-4">
                    <div class="card-body p-4">
                        <h3 class="sub-title-bar mb-3">Visi</h3>
                        <p class="fs-5 mb-0">{{ $blocks['visi'] }}</p>
                    </div>
                </div>
            @endif

            @if (isset($blocks['misi']) && is_array($blocks['misi']))
                <div class="card admin-card my-4">
                    <div class="card-body p-4">
                        <h3 class="sub-title-bar mb-3">Misi</h3>
                        <ol class="mb-0">
                            @foreach ($blocks['misi'] as $misi)
                                <li class="mb-2">{{ $misi }}</li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endif

            @if (isset($blocks['langkah']) && is_array($blocks['langkah']))
                <div class="card admin-card my-4">
                    <div class="card-body p-4">
                        <h3 class="sub-title-bar mb-3">Langkah Pendaftaran</h3>
                        <ol class="mb-0">
                            @foreach ($blocks['langkah'] as $langkah)
                                <li class="mb-2">{{ $langkah }}</li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endif

            @if (isset($blocks['jalur']) && is_array($blocks['jalur']))
                <div class="row g-3 my-4">
                    @foreach ($blocks['jalur'] as $jalur)
                        <div class="col-md-4">
                            <div class="card admin-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $jalur[0] ?? '' }}</h5>
                                    <p class="text-muted mb-0">{{ $jalur[1] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (isset($blocks['tabel']) && is_array($blocks['tabel']))
                <div class="table-responsive my-4">
                    <table class="table table-striped table-hover align-middle admin-card bg-white">
                        <thead>
                            <tr>
                                @foreach ($blocks['tabel'][0] ?? [] as $th)
                                    <th>{{ $th }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_slice($blocks['tabel'], 1) as $row)
                                <tr>
                                    @foreach ($row as $td)
                                        <td>{{ $td }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (isset($blocks['struktur']) && is_array($blocks['struktur']))
                <div class="row g-3 my-4">
                    @foreach ($blocks['struktur'] as $item)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center border rounded bg-white p-3">
                                <i class="fa fa-user-tie fa-lg text-primary me-3"></i>
                                <div>
                                    <strong class="d-block">{{ $item[0] ?? '' }}</strong>
                                    <small class="text-muted">{{ $item[1] ?? '' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (isset($blocks['guru']) && is_array($blocks['guru']))
                <div class="row g-3 my-4">
                    @foreach ($blocks['guru'] as $item)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center border rounded bg-white p-3">
                                <i class="fa fa-chalkboard-teacher fa-lg text-primary me-3"></i>
                                <div>
                                    <strong class="d-block">{{ $item[0] ?? '' }}</strong>
                                    <small class="text-muted">{{ $item[1] ?? '' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (isset($blocks['fasilitas']) && is_array($blocks['fasilitas']))
                <div class="row g-3 my-4">
                    @foreach ($blocks['fasilitas'] as $jenisFasilitas)
                        <div class="col-md-4 col-sm-6">
                            <div class="d-flex align-items-center bg-light rounded p-3">
                                <i class="fa fa-check-circle text-primary me-2"></i>{{ $jenisFasilitas }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
