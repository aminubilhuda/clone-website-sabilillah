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
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@smkabdinegara.sch.id'],
            [
                'name' => 'Admin Abdi Negara',
                'username' => 'admin',
                'password' => 'password',
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
                'password' => 'password',
                'role' => 'editor',
            ],
        );

        if (blank($editor->username)) {
            $editor->update(['username' => 'editor']);
        }

        $this->command->info('AdminUserSeeder: akun admin dan editor siap.');
    }
}
