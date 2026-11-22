@extends('layouts.admin')

@section('title', 'Form Pengguna')

@section('konten')
    <h1 class="h3 mb-1">{{ $user->exists ? 'Ubah Pengguna' : 'Pengguna Baru' }}</h1>
    <p class="text-muted mb-4">Peran <strong>admin</strong> mengelola seluruh panel termasuk pengguna; <strong>editor</strong> mengelola konten saja.</p>

    <div class="card admin-card" style="max-width: 640px;">
        <div class="card-body p-4">
            <form action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
                @csrf
                @if ($user->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="user-name">Nama *</label>
                        <input type="text" id="user-name" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="user-email">Email *</label>
                        <input type="email" id="user-email" name="email" value="{{ old('email', $user->email) }}"
                            class="form-control @error('email') is-invalid @enderror" required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="user-username">Username (untuk login)</label>
                        <input type="text" id="user-username" name="username" value="{{ old('username', $user->username) }}"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="huruf, angka, - atau _" autocomplete="off">

                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Boleh dikosongkan; login tetap bisa memakai email.</small>
                    </div>
                </div>

                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="user-password">
                            Kata Sandi {{ $user->exists ? '(kosongkan bila tetap)' : '*' }}
                        </label>

                        <input type="password" id="user-password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            {{ $user->exists ? '' : 'required' }} autocomplete="new-password" minlength="8">

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="user-role">Peran *</label>

                        <select id="user-role" name="role" class="form-select">
                            <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>

                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
