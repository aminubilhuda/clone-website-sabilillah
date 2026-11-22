@extends('layouts.admin')

@section('title', $menu->exists ? 'Ubah Menu' : 'Menu Baru')

@section('konten')
    <h1 class="h3 mb-4">{{ $menu->exists ? 'Ubah Menu: ' . $menu->label : 'Menu Baru' }}</h1>

    <div class="card admin-card" style="max-width: 640px;">
        <div class="card-body p-4">
            <form action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}" method="POST">
                @csrf
                @if ($menu->exists)
                    @method('PUT')
                @endif

                <label class="form-label" for="menu-menu">Label *</label>
                <input type="text" id="menu-menu" name="label" value="{{ old('label', $menu->label) }}" class="form-control" required>

                <label class="form-label mt-3" for="menu-url">Alamat tujuan *</label>
                <input type="text" id="menu-url" name="url" value="{{ old('url', $menu->url) }}" list="menu-url-options" class="form-control" required>

                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="menu-posisi">Posisi *</label>
                        <select id="menu-posisi" name="position" class="form-select">
                            @foreach ($posisiOptions as $nilai => $label)
                                <option value="{{ $nilai }}" {{ old('position', $menu->position) === $nilai ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="menu-induk">Menu induk</label>
                        <select id="menu-induk" name="parent_id" class="form-select">
                            <option value="">— menu paling atas —</option>
                            @foreach ($parents as $induk)
                                <option value="{{ $induk->id }}" {{ old('parent_id', $menu->parent_id) == $induk->id ? 'selected' : '' }}>{{ $induk->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-check mt-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" id="menu-a" name="is_active" value="1"
                        {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="menu-a">Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @include('admin.menus.url-options')
@endsection
