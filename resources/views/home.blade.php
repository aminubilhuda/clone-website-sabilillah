@extends('layouts.app')

@section('title', setting('nama_sekolah', 'SMK Abdi Negara Tuban') . ' — ' . setting('sub_slogan', ''))

@section('konten')

    {{-- Hero slider --}}
    @include('home.hero')

    {{-- Banner PPDB --}}
    @include('home.banner-ppdb')

    {{-- Sekilas tentang sekolah --}}
    @include('home.sekilas')

    {{-- Statistik --}}
    @include('home.statistik')

    {{-- Berita, agenda, tulisan --}}
    @include('home.publikasi')

    {{-- Kompetensi keahlian --}}
    @include('home.program')

    {{-- Highlight --}}
    @include('home.highlight')

    {{-- Kata mereka --}}
    @include('home.testimoni')

    {{-- Kerja sama --}}
    @include('home.mitra')

@endsection
