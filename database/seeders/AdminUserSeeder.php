<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Menyiapkan akun panel admin yang dipakai di production.
 * Bersifat idempoten (firstOrCreate) agar aman dijalankan ulang.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $isLocalOrTesting = app()->environment(['local', 'testing']);

        $adminPassword = env('ADMIN_SEED_PASSWORD') ?: ($isLocalOrTesting ? 'password' : null);
        $editorPassword = env('EDITOR_SEED_PASSWORD') ?: ($isLocalOrTesting ? 'password' : null);

        if (blank($adminPassword) || blank($editorPassword)) {
            throw new \RuntimeException('ADMIN_SEED_PASSWORD and EDITOR_SEED_PASSWORD must be set when running AdminUserSeeder outside local/testing.');
        }

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@smkabdinegara.sch.id'],
            [
                'name' => 'Admin Abdi Negara',
                'username' => 'admin',
                'password' => $adminPassword,
                'role' => 'admin',
            ],
        );

        if (blank($admin->username)) {
            $admin->update(['username' => 'admin']);
        }

        $editor = User::query()->firstOrCreate(
            ['email' => 'editor@smkabdinegara.sch.id'],
            [
                'name' => 'Editor Abdi Negara',
                'username' => 'editor',
                'password' => $editorPassword,
                'role' => 'editor',
            ],
        );

        if (blank($editor->username)) {
            $editor->update(['username' => 'editor']);
        }

        $this->command->info('AdminUserSeeder: akun admin dan editor siap.');
    }
}
