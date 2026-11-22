<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\HeroSlider;
use App\Models\Page;
use App\Models\Popup;
use App\Models\Post;
use App\Models\VisitorStat;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Overview panel: ringkasan konten dan tren pengunjung.
     */
    public function index(): View
    {
        $mulai = CarbonImmutable::today()->subDays(13);

        $tren = VisitorStat::query()
            ->where('date', '>=', $mulai)
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->date->toDateString() => $row->views]);

        $labels = collect(range(0, 13))
            ->map(fn ($i) => $mulai->copy()->addDays($i))
            ->map(fn ($tanggal) => ['kunci' => $tanggal->toDateString(), 'label' => $tanggal->isoFormat('dd, D MMM')]);

        $dataTren = $labels->map(fn ($label) => [
            'label' => $label['label'],
            'nilai' => (int) ($tren[$label['kunci']] ?? 0),
        ]);

        $counts = [
            Post::TYPE_BERITA => ['Berita', route('admin.posts.index', ['type' => Post::TYPE_BERITA])],
            Post::TYPE_PENGUMUMAN => ['Pengumuman', route('admin.posts.index', ['type' => Post::TYPE_PENGUMUMAN])],
            Post::TYPE_AGENDA => ['Agenda', route('admin.posts.index', ['type' => Post::TYPE_AGENDA])],
            Post::TYPE_TULISAN => ['Tulisan', route('admin.posts.index', ['type' => Post::TYPE_TULISAN])],
            Post::TYPE_MEDIA => ['Media Siswa', route('admin.posts.index', ['type' => Post::TYPE_MEDIA])],
            Post::TYPE_MAJALAH => ['Majalah', route('admin.posts.index', ['type' => Post::TYPE_MAJALAH])],
            Post::TYPE_TOKOH => ['Tokoh', route('admin.posts.index', ['type' => Post::TYPE_TOKOH])],
            'halaman' => ['Halaman', route('admin.pages.index')],
            'album' => ['Album', route('admin.albums.index')],
            'slider' => ['Slider', route('admin.hero-sliders.index')],
            'popup' => ['Popup', route('admin.popups.index')],
        ];

        $counts = collect($counts)
            ->map(fn ($item, $key) => [
                'key' => $key,
                'label' => $item[0],
                'url' => $item[1],
                'angka' => in_array($key, ['halaman', 'album', 'slider', 'popup'], true)
                    ? match ($key) {
                        'halaman' => Page::query()->count(),
                        'album' => Album::query()->count(),
                        'slider' => HeroSlider::query()->count(),
                        default => Popup::query()->count(),
                    }
                    : Post::query()->ofType($key)->count(),
            ]);

        return view('admin.dashboard', [
            'beritaSebar' => collect([
                'Hari Ini' => $dataTren->last()['nilai'],
                'Kemarin' => $dataTren->slice(-2, 1)->first()['nilai'],
                'Total' => VisitorStat::query()->sum('views'),
            ]),
            'counts' => $counts,
            'terbaru' => Post::query()->with('author')->orderByDesc('created_at')->take(8)->get(),
            'trenPengunjung' => $dataTren,
        ]);
    }
}
