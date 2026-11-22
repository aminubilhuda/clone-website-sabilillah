<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database (production-ready).
     *
     * Isi: akun panel admin + struktur situs (pengaturan, menu, halaman,
     * jurusan, slider, kategori, mitra, FAQ). Tanpa konten demo.
     *
     * Untuk mengisi contoh konten (berita, album, statistik dummy) pada
     * lingkungan pengembangan, jalankan:
     *   php artisan db:seed --class=DemoContentSeeder
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SiteContentSeeder::class,
            HomepageSettingsSeeder::class,
            ExtracurricularSeeder::class,
        ]);
    }
}
