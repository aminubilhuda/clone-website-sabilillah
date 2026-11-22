<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->seed(DemoContentSeeder::class);
    }

    public function test_homepage_renders_the_school_brand_and_jurusan(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('SMK Abdi Negara Tuban')
            ->assertSee('Teknik Komputer dan Jaringan')
            ->assertSee('--primary: #1b5faa', false);
    }

    public function test_publication_listings_and_detail_pages_render(): void
    {
        $post = Post::query()->where('type', Post::TYPE_BERITA)->firstOrFail();

        $this->get('/berita')->assertOk();
        $this->get(route('berita.show', $post->slug))->assertOk()->assertSee($post->title);

        $this->get('/pengumuman')->assertOk();
        $this->get('/agenda')->assertOk();
        $this->get('/tulisan-inspirasi')->assertOk();
        $this->get('/media-siswa')->assertOk();

        $this->get('/foto')->assertOk();
        $this->get('/video')->assertOk();
        $this->get('/majalah')->assertOk();
    }

    public function test_jurusan_pages_render_and_only_jurusan_group_is_accessible(): void
    {
        $jurusan = Page::query()->where('slug', 'tkj')->firstOrFail();

        $this->get(route('jurusan.show', 'tkj'))
            ->assertOk()
            ->assertSee($jurusan->title);

        // Halaman grup profil tidak boleh diakses lewat rute jurusan.
        $this->get('/jurusan/sejarah')->assertNotFound();
    }

    public function test_static_pages_and_sitemap_render(): void
    {
        $this->get('/halaman/sejarah')->assertOk();
        $this->get('/halaman/visi-misi')->assertOk();

        $legacy = $this->get('/halaman/tata-cara-pendaftaran');
        $legacy->assertStatus(301);
        $this->assertStringContainsString('/ppdb', strval($legacy->headers->get('Location')));

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('/jurusan/tkj', false);
    }

    public function test_ppdb_page_renders_waves_requirements_fees_and_form(): void
    {
        $this->get('/ppdb')
            ->assertOk()
            ->assertSee('Gelombang Pendaftaran')
            ->assertSee('Persyaratan Pendaftaran')
            ->assertSee('Langkah Pendaftaran')
            ->assertSee('Jalur Penerimaan')
            ->assertSee('Biaya Pendidikan')
            ->assertSee('Seragam Sekolah')
            ->assertSee('Formulir Pendaftaran Online')
            ->assertSee('jurusan_2', false);
    }
}
