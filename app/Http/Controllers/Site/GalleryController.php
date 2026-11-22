<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    /**
     * Galeri foto.
     */
    public function foto(): View
    {
        $albums = Album::query()
            ->where('kind', 'foto')
            ->withCount('items')
            ->orderByDesc('created_at')
            ->paginate(9);

        page_meta('Album Foto', 'Galeri foto kegiatan SMK Abdi Negara Tuban.', route('publikasi.foto'));

        return view('galeri.albums', [
            'albums' => $albums,
            'judul' => 'Album Foto',
            'kind' => 'foto',
        ]);
    }

    /**
     * Galeri video.
     */
    public function video(): View
    {
        $albums = Album::query()
            ->where('kind', 'video')
            ->withCount('items')
            ->orderByDesc('created_at')
            ->paginate(9);

        page_meta('Video', 'Galeri video kegiatan SMK Abdi Negara Tuban.', route('publikasi.video'));

        return view('galeri.albums', [
            'albums' => $albums,
            'judul' => 'Video',
            'kind' => 'video',
        ]);
    }

    /**
     * Galeri detail album.
     */
    public function album(Album $album): View
    {
        $album->load('items');

        page_meta($album->title, strval(str($album->description ?: '')->limit(160)), media_url($album->cover_image), route('album.show', $album->slug));

        return view('galeri.album', ['album' => $album]);
    }
}
