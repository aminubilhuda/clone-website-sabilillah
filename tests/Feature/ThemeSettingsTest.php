<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThemeSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_change_theme_colors_and_homepage_reflects_them(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'tampilan::warna_primer' => '#123456',
                'tampilan::warna_gelap' => '#112233',
                'tampilan::warna_navy' => '#0a0b0c',
                'tampilan::warna_lembut' => '#f0f4f8',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->get('/')
            ->assertOk()
            ->assertSee('--primary: #123456', false)
            ->assertSee('--dark: #112233', false)
            ->assertSee('--darkest: #0a0b0c', false)
            // Teks tombol otomatis putih di atas biru gelap.
            ->assertSee('--on-primary: #ffffff', false);
    }

    public function test_invalid_hex_color_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'tampilan::warna_primer' => 'biru',
            ])
            ->assertSessionHasErrors('tampilan::warna_primer');
    }

    public function test_hex_color_can_be_typed_without_the_hash_prefix(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'tampilan::warna_primer' => '2f81f7',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->assertSame('#2f81f7', Setting::value('warna_primer'));

        $this->get('/')
            ->assertOk()
            ->assertSee('--primary: #2f81f7', false);
    }

    public function test_admin_can_upload_a_school_logo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'logo' => UploadedFile::fake()->image('logo.png', 240, 80),
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $logo = Setting::value('logo');

        $this->assertIsString($logo);
        Storage::disk('public')->assertExists($logo);

        $this->get('/')
            ->assertOk()
            ->assertSee('/storage/'.$logo, false);
    }

    public function test_admin_can_update_homepage_text_and_statistics(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'beranda::judul_testimoni' => 'Kata Mereka Baru',
                'beranda::stat_1_angka' => '1234',
                'beranda::stat_1_label' => 'Siswa Uji',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->get('/')
            ->assertOk()
            ->assertSee('Kata Mereka Baru')
            ->assertSee('1234')
            ->assertSee('Siswa Uji');
    }
}
