<?php

use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExtracurricularController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController as PageAdminController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PpdbApplicantController;
use App\Http\Controllers\Admin\PpdbFeeController;
use App\Http\Controllers\Admin\PpdbRequirementController;
use App\Http\Controllers\Admin\PpdbWaveController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\ExtracurricularController as SiteExtracurricularController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\PpdbController;
use App\Http\Controllers\Site\PublikasiController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('berita', [PublikasiController::class, 'berita'])->name('publikasi.berita');
Route::get('berita/{post:slug}', [PublikasiController::class, 'beritaShow'])->name('berita.show');

Route::get('pengumuman', [PublikasiController::class, 'pengumuman'])->name('publikasi.pengumuman');
Route::get('pengumuman/{post:slug}', [PublikasiController::class, 'pengumumanShow'])->name('pengumuman.show');

Route::get('agenda', [PublikasiController::class, 'agenda'])->name('publikasi.agenda');
Route::get('agenda/{post:slug}', [PublikasiController::class, 'agendaShow'])->name('agenda.show');

Route::get('tulisan-inspirasi', [PublikasiController::class, 'tulisan'])->name('publikasi.tulisan');
Route::get('tulisan/{post:slug}', [PublikasiController::class, 'tulisanShow'])->name('tulisan.show');

Route::get('media-siswa', [PublikasiController::class, 'media'])->name('publikasi.media');
Route::get('media-siswa/{post:slug}', [PublikasiController::class, 'mediaShow'])->name('media.show');

Route::get('majalah', [PublikasiController::class, 'majalah'])->name('majalah.index');
Route::get('majalah/{post:slug}', [PublikasiController::class, 'majalahShow'])->name('majalah.detail');

Route::get('foto', [GalleryController::class, 'foto'])->name('publikasi.foto');
Route::get('video', [GalleryController::class, 'video'])->name('publikasi.video');
Route::get('galeri/{album:slug}', [GalleryController::class, 'album'])->name('album.show');

Route::get('hubungi-kami', [ContactController::class, 'kantor'])->name('contact.kantor');
Route::get('kerja-sama', [ContactController::class, 'kerjaSama'])->name('contact.kerja-sama');
Route::get('faq', [ContactController::class, 'faq'])->name('contact.faq');

Route::permanentRedirect('halaman/ekstrakurikuler', '/ekstrakurikuler');
Route::permanentRedirect('halaman/tata-cara-pendaftaran', '/ppdb');
Route::permanentRedirect('halaman/jalur-penerimaan', '/ppdb');
Route::permanentRedirect('halaman/biaya-pendidikan', '/ppdb#biaya');

Route::get('halaman/{page:slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('ppdb', [PpdbController::class, 'index'])->name('ppdb.index');
Route::post('ppdb', [PpdbController::class, 'store'])->name('ppdb.store');

Route::get('ekstrakurikuler', [SiteExtracurricularController::class, 'index'])->name('ekstrakurikuler.index');

Route::get('jurusan/{page:slug}', [PageController::class, 'jurusan'])->name('jurusan.show');

Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Panel admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'staff'])
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('hero-sliders', SliderController::class)
            ->parameters(['hero-sliders' => 'slider'])
            ->except('show');
        Route::resource('popups', PopupController::class)->except('show');

        Route::resource('ppdb-waves', PpdbWaveController::class)
            ->parameters(['ppdb-waves' => 'wave'])
            ->except('show');
        Route::resource('ppdb-requirements', PpdbRequirementController::class)
            ->parameters(['ppdb-requirements' => 'requirement'])
            ->except(['show', 'create', 'edit']);
        Route::resource('ppdb-applicants', PpdbApplicantController::class)
            ->parameters(['ppdb-applicants' => 'applicant'])
            ->only(['index', 'show', 'update', 'destroy']);
        Route::resource('ppdb-fees', PpdbFeeController::class)
            ->parameters(['ppdb-fees' => 'fee'])
            ->except('show');
        Route::resource('extracurriculars', ExtracurricularController::class)
            ->parameters(['extracurriculars' => 'extracurricular'])
            ->except('show');

        Route::resource('posts', PostController::class)->except('show');
        Route::post('posts/{post}/publish', [PostController::class, 'togglePublish'])->name('posts.publish');
        Route::post('posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore')->whereNumber('id');

        Route::resource('categories', CategoryController::class)->except(['show', 'create']);
        Route::resource('pages', PageAdminController::class)->except('show');
        Route::resource('albums', AlbumController::class)->except('show');
        Route::post('albums/{album}/items', [AlbumController::class, 'storeItem'])->name('albums.items.store');
        Route::patch('album-items/{item}', [AlbumController::class, 'updateItem'])->name('albums.items.update');
        Route::delete('album-items/{item}', [AlbumController::class, 'destroyItem'])->name('albums.items.destroy');

        Route::resource('partners', PartnerController::class)->except('show');
        Route::resource('faqs', FaqController::class)->except('show');
        Route::resource('menus', MenuController::class)->except('show');

        Route::get('pengaturan', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('pengaturan', [SettingController::class, 'update'])->name('settings.update');
        Route::get('media-library', [MediaLibraryController::class, 'index'])->name('media.index');

        Route::middleware('can:manage-users')->group(function (): void {
            Route::resource('users', UserController::class)->except('show');
        });
    });
