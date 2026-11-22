<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    /**
     * Halaman statik generik (profil/PPDB).
     */
    public function show(Page $page): View
    {
        abort_unless($page->is_published, 404);
        abort_if($page->group === 'jurusan', 404);

        page_meta($page->title, strval($page->subtitle ?: ''), media_url($page->block('gambar')));

        return view('pages.show', ['page' => $page]);
    }

    /**
     * Halaman detail kompetensi keahlian (jurusan).
     */
    public function jurusan(Page $page): View
    {
        abort_unless($page->is_published, 404);
        abort_unless($page->group === 'jurusan', 404);

        $jurusanLain = Page::query()
            ->published()
            ->where('group', 'jurusan')
            ->whereKeyNot($page->getKey())
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        page_meta(
            $page->title,
            strval($page->subtitle ?: $page->block('deskripsi', '')),
            media_url($page->block('gambar')),
            route('jurusan.show', $page->slug),
        );

        return view('pages.jurusan', ['page' => $page, 'jurusanLain' => $jurusanLain]);
    }
}
