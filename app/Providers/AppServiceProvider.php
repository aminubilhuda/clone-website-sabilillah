<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Popup;
use App\Models\User;
use App\Support\Seo;
use App\Support\VisitorStats;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::define('manage-users', fn (User $user): bool => $user->role === 'admin');

        View::composer(['layouts.app', 'layouts.admin'], static function ($view): void {
            $ruteKonten = [
                'Konten' => [
                    ['url' => route('admin.posts.index'), 'aktif' => url('admin/posts').'*', 'icon' => 'fa-newspaper', 'label' => 'Konten & Artikel'],
                    ['url' => route('admin.categories.index'), 'aktif' => url('admin/categories').'*', 'icon' => 'fa-tags', 'label' => 'Kategori'],
                    ['url' => route('admin.pages.index'), 'aktif' => url('admin/pages').'*', 'icon' => 'fa-file-alt', 'label' => 'Halaman & Jurusan'],
                    ['url' => route('admin.hero-sliders.index'), 'aktif' => url('admin/hero-sliders').'*', 'icon' => 'fa-images', 'label' => 'Slider Hero'],
                ],
                'PPDB' => [
                    ['url' => route('admin.popups.index'), 'aktif' => url('admin/popups').'*', 'icon' => 'fa-bullhorn', 'label' => 'Popup PPDB'],
                    ['url' => route('admin.ppdb-waves.index'), 'aktif' => url('admin/ppdb-waves').'*', 'icon' => 'fa-calendar-alt', 'label' => 'Gelombang PPDB'],
                    ['url' => route('admin.ppdb-requirements.index'), 'aktif' => url('admin/ppdb-requirements').'*', 'icon' => 'fa-clipboard-list', 'label' => 'Persyaratan PPDB'],
                    ['url' => route('admin.ppdb-fees.index'), 'aktif' => url('admin/ppdb-fees').'*', 'icon' => 'fa-money-bill-wave', 'label' => 'Biaya PPDB'],
                    ['url' => route('admin.ppdb-applicants.index'), 'aktif' => url('admin/ppdb-applicants').'*', 'icon' => 'fa-user-graduate', 'label' => 'Pendaftar PPDB'],
                ],
                'Media & Kemitraan' => [
                    ['url' => route('admin.media.manage'), 'aktif' => url('admin/media').'*', 'icon' => 'fa-folder-open', 'label' => 'Kelola Media'],
                    ['url' => route('admin.albums.index'), 'aktif' => url('admin/albums').'*', 'icon' => 'fa-photo-video', 'label' => 'Galeri / Album'],
                    ['url' => route('admin.partners.index'), 'aktif' => url('admin/partners').'*', 'icon' => 'fa-handshake', 'label' => 'Mitra & Sertifikasi'],
                    ['url' => route('admin.extracurriculars.index'), 'aktif' => url('admin/extracurriculars').'*', 'icon' => 'fa-futbol', 'label' => 'Ekstrakurikuler'],
                ],
                'Tampilan & Sistem' => [
                    ['url' => route('admin.faqs.index'), 'aktif' => url('admin/faqs').'*', 'icon' => 'fa-question-circle', 'label' => 'FAQ'],
                    ['url' => route('admin.menus.index'), 'aktif' => url('admin/menus').'*', 'icon' => 'fa-bars', 'label' => 'Menu Navigasi'],
                    ['url' => route('admin.settings.edit'), 'aktif' => url('admin/pengaturan').'*', 'icon' => 'fa-cog', 'label' => 'Pengaturan'],
                ],
            ];

            $view->with('ruteKonten', $ruteKonten);

            $view->with('sharedMenus', Menu::query()
                ->where('position', 'header')
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->with(['children' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')])
                ->get());

            $view->with('sharedPopup', Popup::query()->active()->first());

            $view->with('sharedVisitorStats', VisitorStats::summary());

            $view->with('seoMeta', Seo::$meta);
        });
    }
}
