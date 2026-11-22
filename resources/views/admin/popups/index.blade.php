@extends('layouts.admin')

@section('title', 'Popup PPDB')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Popup PPDB</h1>
            <small class="text-muted">Gambar lebar yang muncul di awal kunjungan. Hanya satu popup yang aktif.</small>
        </div>

        <a href="{{ route('admin.popups.create') }}" class="btn btn-pill-ppdb rounded-pill">
            <i class="fa fa-plus me-1"></i> Popup Baru
        </a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Tampilan</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($popups as $popup)
                        <tr>
                            <td class="ps-3">
                                <img src="{{ media_url($popup->image, 'img/popup-ppdb.svg') }}" alt="Popup #{{ $popup->id }}"
                                    style="height: 60px;" class="rounded border">
                            </td>
                            <td class="small text-muted">
                                {{ $popup->starts_at?->format('j M y') ?? 'awal' }} &mdash;
                                {{ $popup->ends_at?->format('j M y') ?? 'tanpa akhir' }}
                            </td>
                            <td>
                                <span class="badge {{ $popup->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $popup->is_active ? 'aktif' : 'mati' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <form action="{{ route('admin.popups.destroy', $popup) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus popup ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                </form>
                                <a href="{{ route('admin.popups.edit', $popup) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-5">Belum ada popup.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
