<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\AlbumItem;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Popup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudAuditTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_all_admin_create_forms_render(): void
    {
        $admin = $this->admin();

        $urls = [
            '/admin/posts/create',
            '/admin/pages/create',
            '/admin/hero-sliders/create',
            '/admin/popups/create',
            '/admin/albums/create',
            '/admin/partners/create',
            '/admin/extracurriculars/create',
            '/admin/faqs/create',
            '/admin/menus/create',
            '/admin/ppdb-waves/create',
            '/admin/ppdb-fees/create',
            '/admin/users/create',
        ];

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_categories_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Kategori Uji',
            'type' => 'berita',
        ])->assertRedirect();

        $category = Category::query()->where('name', 'Kategori Uji')->firstOrFail();

        $this->actingAs($admin)->put("/admin/categories/{$category->id}", [
            'name' => 'Kategori Diubah',
            'type' => 'berita',
        ])->assertRedirect(route('admin.categories.index'));

        $this->assertSame('Kategori Diubah', $category->fresh()->name);

        $this->actingAs($admin)->delete("/admin/categories/{$category->id}")->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_pages_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/pages', [
            'title' => 'Halaman Uji',
            'group' => 'profil',
            'is_published' => '1',
        ])->assertRedirect(route('admin.pages.index'));

        $page = Page::query()->where('title', 'Halaman Uji')->firstOrFail();

        $this->actingAs($admin)->put("/admin/pages/{$page->id}", [
            'title' => 'Halaman Diubah',
            'group' => 'profil',
            'is_published' => '1',
            'blocks_text' => '{"intro":"uji"}',
        ])->assertRedirect(route('admin.pages.index'));

        $this->assertSame('Halaman Diubah', $page->fresh()->title);
        $this->assertSame('uji', $page->fresh()->block('intro'));

        $this->actingAs($admin)->delete("/admin/pages/{$page->id}")->assertRedirect();
        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_page_structured_fields_are_converted_to_blocks(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/pages', [
            'title' => 'Halaman Struktur',
            'group' => 'profil',
            'is_published' => '1',
            'intro' => 'Intro uji',
            'isi' => "Paragraf satu\nParagraf dua",
            'misi' => "Misi satu\nMisi dua",
            'struktur' => 'Kepala Sekolah | Pimpinan',
            'fasilitas' => "Lab Komputer\nPerpustakaan",
        ])->assertRedirect(route('admin.pages.index'));

        $page = Page::query()->where('title', 'Halaman Struktur')->firstOrFail();

        $this->assertSame('Intro uji', $page->block('intro'));
        $this->assertSame(['Paragraf satu', 'Paragraf dua'], $page->block('isi'));
        $this->assertSame(['Misi satu', 'Misi dua'], $page->block('misi'));
        $this->assertSame([['Kepala Sekolah', 'Pimpinan']], $page->block('struktur'));
        $this->assertSame(['Lab Komputer', 'Perpustakaan'], $page->block('fasilitas'));
    }

    public function test_popups_crud_with_upload(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/popups', [
            'link' => 'halaman/tata-cara-pendaftaran',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('popup.png', 400, 400),
        ])->assertRedirect(route('admin.popups.index'));

        $popup = Popup::query()->firstOrFail();
        $this->assertNotNull($popup->image);
        Storage::disk('public')->assertExists($popup->image);

        $this->actingAs($admin)->put("/admin/popups/{$popup->id}", [
            'link' => 'halaman/jalur-penerimaan',
            'is_active' => '1',
        ])->assertRedirect(route('admin.popups.index'));

        $this->assertSame('halaman/jalur-penerimaan', $popup->fresh()->link);

        $this->actingAs($admin)->delete("/admin/popups/{$popup->id}")->assertRedirect();
        $this->assertDatabaseMissing('popups', ['id' => $popup->id]);
    }

    public function test_albums_and_items_crud_with_upload(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/albums', [
            'title' => 'Album Uji',
            'kind' => 'foto',
            'description' => 'Deskripsi uji.',
            'cover' => UploadedFile::fake()->image('cover.png', 300, 200),
        ])->assertRedirect(route('admin.albums.index'));

        $album = Album::query()->where('title', 'Album Uji')->firstOrFail();
        $this->assertNotNull($album->cover_image);
        Storage::disk('public')->assertExists($album->cover_image);

        $this->actingAs($admin)->put("/admin/albums/{$album->id}", [
            'title' => 'Album Diubah',
            'kind' => 'foto',
            'description' => 'Deskripsi diubah.',
        ])->assertRedirect(route('admin.albums.index'));

        $this->assertSame('Album Diubah', $album->fresh()->title);

        // Item
        $this->actingAs($admin)->post("/admin/albums/{$album->id}/items", [
            'caption' => 'Item uji',
            'media_url' => 'https://example.com/video',
        ])->assertRedirect();

        $item = AlbumItem::query()->where('album_id', $album->id)->firstOrFail();
        $this->assertSame('Item uji', $item->caption);

        $this->actingAs($admin)->patch("/admin/album-items/{$item->id}", [
            'caption' => 'Item diubah',
        ])->assertRedirect();

        $this->assertSame('Item diubah', $item->fresh()->caption);

        $this->actingAs($admin)->delete("/admin/album-items/{$item->id}")->assertRedirect();
        $this->assertDatabaseMissing('album_items', ['id' => $item->id]);

        $this->actingAs($admin)->delete("/admin/albums/{$album->id}")->assertRedirect();
        $this->assertDatabaseMissing('albums', ['id' => $album->id]);
    }

    public function test_partners_crud_with_upload(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/partners', [
            'name' => 'Mitra Uji',
            'group' => 'klien',
            'website_url' => 'https://example.com',
            'is_active' => '1',
            'logo' => UploadedFile::fake()->image('logo.png', 200, 100),
        ])->assertRedirect(route('admin.partners.index'));

        $partner = Partner::query()->where('name', 'Mitra Uji')->firstOrFail();
        $this->assertNotNull($partner->logo_path);
        Storage::disk('public')->assertExists($partner->logo_path);

        $this->actingAs($admin)->put("/admin/partners/{$partner->id}", [
            'name' => 'Mitra Diubah',
            'group' => 'lisensi',
            'website_url' => 'https://example.com/2',
            'is_active' => '1',
        ])->assertRedirect(route('admin.partners.index'));

        $this->assertSame('Mitra Diubah', $partner->fresh()->name);

        $this->actingAs($admin)->delete("/admin/partners/{$partner->id}")->assertRedirect();
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
    }

    public function test_faqs_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/faqs', [
            'question' => 'Pertanyaan uji?',
            'answer' => 'Jawaban uji.',
            'category' => 'Umum',
            'is_active' => '1',
        ])->assertRedirect(route('admin.faqs.index'));

        $faq = Faq::query()->where('question', 'Pertanyaan uji?')->firstOrFail();

        $this->actingAs($admin)->put("/admin/faqs/{$faq->id}", [
            'question' => 'Pertanyaan diubah?',
            'answer' => 'Jawaban diubah.',
            'category' => 'PPDB',
            'is_active' => '1',
        ])->assertRedirect(route('admin.faqs.index'));

        $this->assertSame('Pertanyaan diubah?', $faq->fresh()->question);

        $this->actingAs($admin)->delete("/admin/faqs/{$faq->id}")->assertRedirect();
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_menus_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/menus', [
            'label' => 'Menu Uji',
            'url' => 'berita',
            'position' => 'header',
            'parent_id' => '',
            'is_active' => '1',
        ])->assertRedirect(route('admin.menus.index'));

        $menu = Menu::query()->where('label', 'Menu Uji')->firstOrFail();

        $this->actingAs($admin)->put("/admin/menus/{$menu->id}", [
            'label' => 'Menu Diubah',
            'url' => 'agenda',
            'position' => 'footer',
            'parent_id' => '',
            'is_active' => '1',
        ])->assertRedirect(route('admin.menus.index'));

        $this->assertSame('Menu Diubah', $menu->fresh()->label);

        $this->actingAs($admin)->delete("/admin/menus/{$menu->id}")->assertRedirect();
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_users_crud_and_self_delete_guard(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Pengguna Uji',
            'email' => 'uji@example.com',
            'password' => 'password123',
            'role' => 'editor',
        ])->assertRedirect(route('admin.users.index'));

        $user = User::query()->where('email', 'uji@example.com')->firstOrFail();

        $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Pengguna Diubah',
            'email' => 'uji@example.com',
            'role' => 'admin',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertSame('Pengguna Diubah', $user->fresh()->name);

        // Hapus pengguna lain berhasil.
        $this->actingAs($admin)->delete("/admin/users/{$user->id}")->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        // Tidak dapat menghapus diri sendiri.
        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
