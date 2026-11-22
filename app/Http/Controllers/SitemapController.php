<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    /**
     * Builds a dynamic XML sitemap for public content.
     */
    public function index(): Response
    {
        $posts = Post::query()->published()
            ->orderByDesc('published_at')
            ->get();

        $pages = Page::query()->published()
            ->orderBy('group')
            ->get();

        $albums = Album::query()
            ->orderBy('slug')
            ->get();

        $staticRoutes = [
            ['route' => 'ppdb.index', 'priority' => '0.9', 'freq' => 'weekly'],
            ['route' => 'ekstrakurikuler.index', 'priority' => '0.6', 'freq' => 'monthly'],
            ['route' => 'publikasi.berita', 'priority' => '0.8', 'freq' => 'daily'],
            ['route' => 'publikasi.pengumuman', 'priority' => '0.7', 'freq' => 'weekly'],
            ['route' => 'publikasi.agenda', 'priority' => '0.7', 'freq' => 'weekly'],
            ['route' => 'publikasi.tulisan', 'priority' => '0.6', 'freq' => 'weekly'],
            ['route' => 'publikasi.media', 'priority' => '0.6', 'freq' => 'weekly'],
            ['route' => 'publikasi.foto', 'priority' => '0.6', 'freq' => 'monthly'],
            ['route' => 'publikasi.video', 'priority' => '0.6', 'freq' => 'monthly'],
            ['route' => 'majalah.index', 'priority' => '0.6', 'freq' => 'monthly'],
            ['route' => 'contact.kantor', 'priority' => '0.6', 'freq' => 'yearly'],
            ['route' => 'contact.kerja-sama', 'priority' => '0.5', 'freq' => 'yearly'],
            ['route' => 'contact.faq', 'priority' => '0.5', 'freq' => 'yearly'],
        ];

        $entries = collect()
            ->push(['loc' => route('home'), 'priority' => '1.0', 'freq' => 'daily'])
            ->merge(collect($staticRoutes)->map(fn (array $entry) => [
                'loc' => route($entry['route']),
                'priority' => $entry['priority'],
                'freq' => $entry['freq'],
            ]))
            ->merge($posts->map(fn (Post $post) => [
                'loc' => $post->detailUrl(),
                'priority' => '0.8',
                'freq' => 'weekly',
                'updated' => $post->updated_at?->toAtomString(),
            ]))
            ->merge($pages->map(fn (Page $page) => [
                'loc' => ($page->group === 'jurusan' ? route('jurusan.show', $page->slug) : route('pages.show', $page->slug)),
                'priority' => '0.6',
                'freq' => 'monthly',
                'updated' => $page->updated_at?->toAtomString(),
            ]))
            ->merge($albums->map(fn (Album $album) => [
                'loc' => route('album.show', $album->slug),
                'priority' => '0.5',
                'freq' => 'monthly',
                'updated' => $album->updated_at?->toAtomString(),
            ]));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($entries as $entry) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>'.htmlspecialchars($entry['loc'])."</loc>\n";

            if (isset($entry['updated']) && $entry['updated'] !== null) {
                $xml .= '        <lastmod>'.$entry['updated']."</lastmod>\n";
            }

            $xml .= '        <changefreq>'.$entry['freq']."</changefreq>\n";
            $xml .= '        <priority>'.$entry['priority']."</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
