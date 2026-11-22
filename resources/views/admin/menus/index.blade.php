@extends('layouts.admin')

@section('title', 'Menu Navigasi')

@section('konten')
    <h1 class="h3 mb-1">Menu Navigasi</h1>
    <p class="text-muted mb-4">Struktur menu header, PPDB pill, dan tautan footer.</p>

    <div class="row g-4">
        <div class="col-lg-8">
            @foreach (['header' => 'Menu Utama (Header)', 'footer' => 'Menu Footer'] as $posisi => $judul)
                @php
                    $indukMenu = $menus->where('position', $posisi)->whereNull('parent_id')->sortBy('sort_order');
                @endphp

                <div class="card admin-card mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $judul }}</h5>
                        <span class="badge bg-light text-dark border">{{ $indukMenu->count() }} menu induk</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Label</th>
                                    <th>Tautan</th>
                                    <th>Status</th>
                                    <th class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($indukMenu as $induk)
                                    <tr class="table-light">
                                        <td class="ps-3 fw-semibold">
                                            <i class="fa fa-folder-open text-primary me-2"></i>{{ $induk->label }}
                                        </td>
                                    <td><code class="d-inline-block text-truncate" style="max-width: 220px;" title="{{ $induk->url }}">{{ $induk->url }}</code></td>
                                        <td>
                                            <span class="badge {{ $induk->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $induk->is_active ? 'aktif' : 'non-aktif' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('admin.menus.destroy', $induk) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Hapus menu ini? Anak menu akan ikut lepas.')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                                            </form>
                                            <a href="{{ route('admin.menus.edit', $induk) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>

                                    @foreach ($menus->where('parent_id', $induk->id)->sortBy('sort_order') as $anak)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="ms-4 text-muted"><i class="fa fa-angle-right me-2"></i>{{ $anak->label }}</span>
                                            </td>
                                            <td><code class="d-inline-block text-truncate" style="max-width: 220px;" title="{{ $anak->url }}">{{ $anak->url }}</code></td>
                                            <td>
                                                <span class="badge {{ $anak->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $anak->is_active ? 'aktif' : 'non-aktif' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <form action="{{ route('admin.menus.destroy', $anak) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus menu ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                                                </form>
                                                <a href="{{ route('admin.menus.edit', $anak) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada menu pada posisi ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-4">
            <div class="admin-sticky">
                <div class="card admin-card">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Tambah Menu Baru</h5>

                        <form action="{{ route('admin.menus.store') }}" method="POST">
                            @csrf

                            <label class="form-label" for="menu-menu">Label *</label>
                            <input type="text" id="menu-menu" name="label" value="{{ old('label') }}"
                                class="form-control @error('label') is-invalid @enderror" required>

                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label class="form-label mt-3" for="menu-url">Alamat tujuan *</label>
                            <input type="text" id="menu-url" name="url" value="{{ old('url', '#') }}" list="menu-url-options"
                                class="form-control @error('url') is-invalid @enderror" placeholder="mis: berita atau halaman/sejarah">

                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label class="form-label mt-3" for="menu-posisi">Posisi *</label>
                            <select id="menu-posisi" name="position" class="form-select">
                                @foreach ($posisiOptions as $nilai => $label)
                                    <option value="{{ $nilai }}" {{ old('position') === $nilai ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>

                            <label class="form-label mt-3" for="menu-induk">Menu induk (opsional)</label>
                            <select id="menu-induk" name="parent_id" class="form-select">
                                <option value="">— menu paling atas —</option>
                                @foreach ($parents as $induk)
                                    <option value="{{ $induk->id }}">{{ $induk->label }}</option>
                                @endforeach
                            </select>

                            <div class="form-check mt-3">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" id="menu-a" name="is_active" value="1" checked>
                                <label class="form-check-label" for="menu-a">Aktif</label>
                            </div>

                            <button type="submit" class="btn btn-pill-ppdb w-100 mt-3">Simpan Menu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.menus.url-options')
@endsection
