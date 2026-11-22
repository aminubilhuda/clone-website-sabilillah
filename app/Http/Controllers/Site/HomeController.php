<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Renders the multi-section landing page.
     */
    public function index(): View
    {
        $nama = strval(setting('nama_sekolah', 'SMK Abdi Negara Tuban'));

        page_meta($nama, strval(setting('meta_description', '')));

        return view('home', [
            'heroSliders' => HeroSlider::query()->active()->get(),
            'bannerPpdb' => setting('banner_active', false) === true,
            'bannerPpdbText' => setting('banner_text'),
            'bannerPpdbLink' => setting('banner_link'),
            'bannerPpdbImage' => setting('banner_image'),

            'berita' => Post::query()
                ->published()
                ->ofType(Post::TYPE_BERITA)
                ->with('category')
                ->latest('published_at')
                ->take(9)
                ->get(),

            'pengumuman' => Post::query()
                ->published()
                ->ofType(Post::TYPE_PENGUMUMAN)
                ->latest('published_at')
                ->take(4)
                ->get(),

            'agenda' => Post::query()
                ->published()
                ->ofType(Post::TYPE_AGENDA)
                ->orderBy('starts_at')
                ->take(6)
                ->get(),

            'tulisan' => Post::query()
                ->published()
                ->ofType(Post::TYPE_TULISAN)
                ->with('category')
                ->latest('published_at')
                ->take(6)
                ->get(),

            'jurusanHalaman' => Page::query()
                ->published()
                ->where('group', 'jurusan')
                ->orderBy('sort_order')
                ->get(),

            'bkkPage' => Page::query()
                ->published()
                ->where('slug', 'bkk-pkl')
                ->first(),

            'highlight' => Post::query()
                ->published()
                ->ofType(Post::TYPE_BERITA)
                ->where('is_featured', true)
                ->take(4)
                ->get(),

            'tokoh' => Post::query()
                ->published()
                ->ofType(Post::TYPE_TOKOH)
                ->take(6)
                ->get(),

            'klien' => Partner::query()
                ->active()
                ->where('group', Partner::GROUP_KLIEN)
                ->get(),

            'lisensi' => Partner::query()
                ->active()
                ->where('group', Partner::GROUP_LISENSI)
                ->get(),
        ]);
    }
}
