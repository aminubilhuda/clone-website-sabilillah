@extends('layouts.admin')

@section('title', 'Pengguna')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Pengguna Panel</h1>
            <small class="text-muted">Akun pengelola konten: <strong>admin</strong> (penuh) dan <strong>editor</strong> (konten saja).</small>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-pill-ppdb rounded-pill">
            <i class="fa fa-user-plus me-1"></i> Pengguna Baru
        </a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Dibuat</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px; font-size: .8rem;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>

                                    <span class="fw-semibold">{{ $user->name }}</span>
                                    @if ($user->username)
                                        <small class="d-block text-muted">@ {{ $user->username }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="small">{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-dark' : 'bg-secondary' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $user->created_at->format('j F Y') }}</td>
                            <td class="text-end pe-3">
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"
                                        {{ auth()->user()->is($user) ? 'title="Tidak dapat menghapus akun sendiri" disabled' : 'title="Hapus"' }}>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $users->onEachSide(1)->links() }}</div>
@endsection
