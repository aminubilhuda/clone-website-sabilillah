<?php

namespace Tests\Feature;

use App\Models\PpdbWave;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class SecurityFixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_svg_uploads_are_rejected_in_settings_and_pages(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $svg = UploadedFile::fake()->createWithContent(
            'evil.svg',
            '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>',
        );

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['logo' => $svg])
            ->assertSessionHasErrors('logo');

        $this->actingAs($admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Halaman Uji',
                'slug' => 'halaman-uji',
                'group' => 'statik',
                'gambar_file' => $svg,
            ])
            ->assertSessionHasErrors('gambar_file');
    }

    public function test_gmaps_embed_rejects_non_https_schemes(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'umum::gmaps_embed' => 'javascript:alert(1)',
            ])
            ->assertSessionHasErrors('umum::gmaps_embed');

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'umum::gmaps_embed' => 'https://www.google.com/maps?q=Tuban&output=embed',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->get(route('contact.kantor'))
            ->assertOk()
            ->assertSee('sandbox="allow-scripts allow-same-origin allow-popups"', false);
    }

    public function test_admin_password_reset_revokes_target_user_sessions_and_remember_token(): void
    {
        config(['session.driver' => 'database']);

        $admin = User::factory()->admin()->create();
        $editor = User::factory()->create([
            'role' => 'editor',
            'remember_token' => 'old-remember-token-value',
        ]);

        DB::table(config('session.table', 'sessions'))->insert([
            'id' => 'target-user-session-id',
            'user_id' => $editor->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('test'),
            'last_activity' => time(),
        ]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $editor), [
                'name' => $editor->name,
                'email' => $editor->email,
                'role' => 'editor',
                'password' => 'new-strong-password-123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertNotSame('old-remember-token-value', $editor->fresh()->remember_token);
        $this->assertDatabaseMissing(config('session.table', 'sessions'), [
            'id' => 'target-user-session-id',
        ]);
    }

    public function test_ppdb_store_rejects_inactive_and_closed_waves(): void
    {
        $inactiveWave = PpdbWave::query()->create([
            'label' => 'Gelombang Nonaktif',
            'mulai' => now()->subDays(5),
            'selesai' => now()->addDays(5),
            'biaya' => 0,
            'sort_order' => 1,
            'is_active' => false,
        ]);

        $closedWave = PpdbWave::query()->create([
            'label' => 'Gelombang Tutup',
            'mulai' => now()->subDays(20),
            'selesai' => now()->subDays(1),
            'biaya' => 0,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $payload = [
            'name' => 'Siswa Uji',
            'asal_sekolah' => 'SMPN 1 Uji',
            'jurusan' => 'akuntansi',
            'parent_name' => 'Ortu Uji',
            'whatsapp' => '08123456789',
            'alamat' => 'Jl. Uji No. 1',
        ];

        $this->post(route('ppdb.store'), [...$payload, 'wave_id' => $inactiveWave->id])
            ->assertSessionHasErrors('wave_id');

        $this->post(route('ppdb.store'), [...$payload, 'wave_id' => $closedWave->id])
            ->assertSessionHasErrors('wave_id');

        $this->assertDatabaseCount('ppdb_applicants', 0);
    }

    public function test_admin_user_seeder_requires_env_passwords_outside_local_or_testing(): void
    {
        $originalEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->expectException(RuntimeException::class);
            (new AdminUserSeeder)->run();
        } finally {
            $this->app['env'] = $originalEnv;
        }
    }
}
