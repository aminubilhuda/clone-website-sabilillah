<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PublikasiController extends Controller
{
    /**
     * Detail berita.
     */
    public function beritaShow(Post $post): View
    {
        return $this->show($post, Post::TYPE_BERITA, 'Berita');
    }

    /**
     * Detail pengumuman.
     */
    public function pengumumanShow(Post $post): View
    {
        return $this->show($post, Post::TYPE_PENGUMUMAN, 'Pengumuman');
    }

    /**
     * Detail agenda.
     */
    public function agendaShow(Post $post): View
    {
        return $this->show($post, Post::TYPE_AGENDA, 'Agenda');
    }

    /**
     * Detail tulisan inspirasi.
     */
    public function tulisanShow(Post $post): View
    {
        return $this->show($post, Post::TYPE_TULISAN, 'Tulisan Inspirasi');
    }

    /**
     * Detail media siswa.
     */
    public function mediaShow(Post $post): View
    {
        return $this->show($post, Post::TYPE_MEDIA, 'Media Siswa');
    }

    /**
     * Artikel listing "Berita".
     */
    public function berita(Request $request): View
    {
        $kategori = Category::query()
            ->where('type', Post::TYPE_BERITA)
            ->orderBy('name')
            ->get();

        $posts = Post::query()
            ->published()
            ->ofType(Post::TYPE_BERITA)
            ->with('category')
            ->when($request->string('kategori')->toString(), fn ($query, $slug) => $query
                ->whereHas('category', fn ($q) => $q->where('slug', $slug)))
            ->when($request->string('jurusan')->toString(), fn ($query, $jurusan) => $query->where('jurusan', $jurusan))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        page_meta('Berita', 'Kumpulan berita terbaru '.setting('nama_sekolah').'.', url('img/berita-1.svg'), route('publikasi.berita'));

        return view('publikasi.listing', [
            'posts' => $posts,
            'kategori' => $kategori,
            'filterKategori' => $request->string('kategori')->toString(),
            'filterJurusan' => $request->string('jurusan')->toString(),
            'judul' => 'Berita',
            'deskripsi' => 'Seputar kegiatan dan informasi terbaru sekolah.',
            'type' => Post::TYPE_BERITA,
            'jurusanOptions' => $this->jurusanOptions(),
        ]);
    }

    /**
     * Artikel listing "Pengumuman".
     */
    public function pengumuman(): View
    {
        $posts = Post::query()
            ->published()
            ->ofType(Post::TYPE_PENGUMUMAN)
            ->with('category')
            ->latest('published_at')
            ->paginate(12);

        page_meta('Pengumuman', 'Pengumuman resmi '.setting('nama_sekolah').'.', route('publikasi.pengumuman'));

        return view('publikasi.listing', [
            'posts' => $posts,
            'kategori' => collect(),
            'filterKategori' => null,
            'filterJurusan' => null,
            'judul' => 'Pengumuman',
            'deskripsi' => 'Pengumuman resmi sekolah.',
            'type' => Post::TYPE_PENGUMUMAN,
            'jurusanOptions' => collect(),
        ]);
    }

    /**
     * Artikel listing "Agenda".
     */
    public function agenda(): View
    {
        $aktif = Post::query()
            ->published()
            ->ofType(Post::TYPE_AGENDA)
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->paginate(9);

        $lampau = Post::query()
            ->published()
            ->ofType(Post::TYPE_AGENDA)
            ->where('starts_at', '<', now())
            ->orderByDesc('starts_at')
            ->paginate(6);

        page_meta('Agenda', 'Agenda kegiatan '.setting('nama_sekolah').'.', route('publikasi.agenda'));

        return view('publikasi.agenda', ['aktif' => $aktif, 'lampau' => $lampau]);
    }

    /**
     * Artikel listing "Tulisan Inspirasi".
     */
    public function tulisan(): View
    {
        $posts = Post::query()
            ->published()
            ->ofType(Post::TYPE_TULISAN)
            ->with('category')
            ->latest('published_at')
            ->paginate(9);

        page_meta('Tulisan Inspirasi', 'Tulisan inspirasi dari pendidik dan murid '.setting('nama_sekolah').'.', route('publikasi.tulisan'));

        return view('publikasi.listing', [
            'posts' => $posts,
            'kategori' => collect(),
            'filterKategori' => null,
            'filterJurusan' => null,
            'judul' => 'Tulisan Inspirasi',
            'deskripsi' => 'Tulisan inspirasi dari pendidik dan murid.',
            'type' => Post::TYPE_TULISAN,
            'jurusanOptions' => collect(),
        ]);
    }

    /**
     * Artikel listing "Media Siswa".
     */
    public function media(): View
    {
        $posts = Post::query()
            ->published()
            ->ofType(Post::TYPE_MEDIA)
            ->with('category')
            ->latest('published_at')
            ->paginate(9);

        page_meta('Media Siswa', 'Karya dan publikasi murid '.setting('nama_sekolah').'.', route('publikasi.media'));

        return view('publikasi.listing', [
            'posts' => $posts,
            'kategori' => collect(),
            'filterKategori' => null,
            'filterJurusan' => null,
            'judul' => 'Media Siswa',
            'deskripsi' => 'Karya dan publikasi murid.',
            'type' => Post::TYPE_MEDIA,
            'jurusanOptions' => collect(),
        ]);
    }

    /**
     * Listing majalah.
     */
    public function majalah(): View
    {
        $posts = Post::query()
            ->published()
            ->ofType(Post::TYPE_MAJALAH)
            ->latest('published_at')
            ->paginate(12);

        page_meta('Majalah', 'Arsip majalah edisi '.setting('nama_sekolah').'.', route('majalah.index'));

        return view('publikasi.majalah', ['posts' => $posts]);
    }

    /**
     * Detail artikel beragam jenis (berita, media, tulisan, pengumuman, agenda).
     */
    private function show(Post $post, string $type, string $judul): View
    {
        abort_unless($post->type === $type, 404);
        abort_unless($post->status === 'published' && $post->published_at !== null && $post->published_at->lte(now()), 404);

        $post->load(['category', 'author']);

        $related = Post::query()
            ->published()
            ->ofType($type)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->take(3)
            ->get();

        $post->increment('views');

        $ruteKembali = match ($type) {
            Post::TYPE_AGENDA => route('publikasi.agenda'),
            Post::TYPE_TULISAN => route('publikasi.tulisan'),
            Post::TYPE_MEDIA => route('publikasi.media'),
            Post::TYPE_PENGUMUMAN => route('publikasi.pengumuman'),
            default => route('publikasi.berita'),
        };

        page_meta($post->title, strval(str($post->excerpt ?: strip_tags($post->content))->limit(160)), media_url($post->cover_image), $post->detailUrl());

        return view('publikasi.show', [
            'post' => $post,
            'related' => $related,
            'ruteKembali' => $ruteKembali,
            'judul' => $judul,
        ]);
    }

    /**
     * Detail majalah, dengan tombol unduh dokumen bila tersedia.
     */
    public function majalahShow(Post $post): View
    {
        abort_unless($post->type === Post::TYPE_MAJALAH, 404);
        abort_unless($post->status === 'published', 404);

        $post->increment('views');

        page_meta($post->title, strval(str($post->excerpt ?: '')->limit(160)), media_url($post->cover_image), $post->detailUrl());

        return view('publikasi.majalah-detail', ['post' => $post]);
    }

    /**
     * Daftar jurusan yang dapat dipakai sebagai filter berita.
     *
     * @return Collection<string, string>
     */
    private function jurusanOptions(): Collection
    {
        return Page::query()
            ->published()
            ->where('group', 'jurusan')
            ->orderBy('sort_order')
            ->pluck('title', 'slug');
    }
}
