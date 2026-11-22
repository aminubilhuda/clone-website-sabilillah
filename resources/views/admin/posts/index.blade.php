@extends('layouts.admin')

@section('title', 'Konten & Artikel')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-0">Konten &amp; Artikel</h1>
            <small class="text-muted">Berita, pengumuman, agenda, tulisan, media siswa, majalah, dan tokoh.</small>
        </div>

        <a href="{{ route('admin.posts.create', $type !== '' ? ['type' => $type] : []) }}" class="btn btn-pill-ppdb rounded-pill">
            <i class="fa fa-plus me-1"></i> Tulis Baru
        </a>
    </div>

    {{-- Filter tipe konten --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-sm rounded-pill {{ $type === '' ? 'btn-dark' : 'btn-outline-dark' }}">Semua</a>

        @foreach ($types as $tipe => $label)
            <a href="{{ route('admin.posts.index', ['type' => $tipe]) }}"
                class="btn btn-sm rounded-pill {{ $type === $tipe ? 'btn-dark' : 'btn-outline-dark' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Judul</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Jadwal</th>
                        <th>Lihat</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr class=" {{ $post->trashed() ? 'inhapus-baris' : '' }}">
                            <td class="ps-3">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="fw-semibold text-dark">{{ str($post->title)->limit(56) }}</a>
                                <small class="d-block text-muted">{{ $post->published_at?->format('j F Y') ?? 'belum terbit' }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $types[$post->type] ?? $post->type }}</span></td>
                            <td>
                                <span class="badge {{ $post->trashed() ? 'bg-danger' : ($post->status === 'published' ? 'bg-success' : 'bg-warning text-dark') }}">
                                    {{ $post->trashed() ? 'terhapus' : ($post->status === 'published' ? 'terbit' : 'draf') }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $post->starts_at?->format('j M y H:i') ?? '-' }}</td>
                            <td>
                                @unless ($post->trashed())
                                    <a href="{{ $post->detailUrl() }}" target="_blank" class="text-muted small"><i class="far fa-eye"></i></a>
                                @endunless
                            </td>
                            <td class="text-end pe-3">
                                @unless ($post->trashed())
                                    <form action="{{ route('admin.posts.publish', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-{{ $post->status === 'published' ? 'warning' : 'success' }}" type="submit"
                                            title="{{ $post->status === 'published' ? 'Tarik dari terbit' : 'Terbitkan' }}">
                                            <i class="fa fa-{{ $post->status === 'published' ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Pindahkan ke keranjang hapus?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus"><i class="fa fa-trash"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success" type="submit" title="Pulihkan"><i class="fa fa-undo"></i></button>
                                    </form>
                                @endunless

                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Belum ada artikel pada kategori ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">{{ $posts->onEachSide(1)->links() }}</div>
@endsection

@push('skrip')
    <style>
        .inhapus-baris td {
            opacity: .55;
            text-decoration: line-through;
        }

        .inhapus-baris td .btn,
        .inhapus-baris td a {
            text-decoration: none !important;
        }
    </style>
@endpush
