@extends('layouts.admin')

@section('title', 'Slider Hero')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Slider Hero</h1>
            <small class="text-muted">Gambar beranda yang bergantian di bagian atas situs.</small>
        </div>

        <a href="{{ route('admin.hero-sliders.create') }}" class="btn btn-pill-ppdb rounded-pill">
            <i class="fa fa-plus me-1"></i> Utama Slider
        </a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Tampilan</th>
                        <th>Judul</th>
                        <th>Tautan</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($sliders as $slider)
                        <tr>
                            <td class="ps-3">
                                <img src="{{ media_url($slider->image, 'img/hero-1.svg') }}" alt="{{ $slider->title }}"
                                    style="height: 44px;" class="rounded border">
                            </td>
                            <td>{{ $slider->title ?? '-' }}</td>
                            <td><code>{{ $slider->link ?? '-' }}</code></td>
                            <td>{{ $slider->sort_order }}</td>
                            <td>
                                <span class="badge {{ $slider->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $slider->is_active ? 'aktif' : 'non-aktif' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <form action="{{ route('admin.hero-sliders.destroy', $slider) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus slider ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                </form>
                                <a href="{{ route('admin.hero-sliders.edit', $slider) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Belum ada slider. Tambahkan untuk ditampilkan di beranda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
