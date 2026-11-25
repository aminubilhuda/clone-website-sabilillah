<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->firstWhere('role', 'admin') ?? User::query()->first();

        if (! $author) {
            $author = User::query()->create([
                'name' => 'Admin Sekolah',
                'username' => 'admin',
                'email' => 'admin@smkabdinegara.sch.id',
                'password' => 'password',
                'role' => 'admin',
            ]);
        }

        $category = Category::query()
            ->where('type', Post::TYPE_BERITA)
            ->whereIn('slug', ['akademik', 'umum'])
            ->first()
            ?? Category::query()->firstOrCreate(
                ['slug' => 'akademik', 'type' => Post::TYPE_BERITA],
                ['name' => 'Akademik'],
            );

        $title = 'Sosialisasi Program Sekolah ke Orang Tua dan Siswa';
        $slug = 'sosialisasi-program-sekolah-ke-orang-tua-dan-siswa';
        $publishedAt = CarbonImmutable::create(2026, 10, 3, 8, 0, 0, 'Asia/Jakarta');

        $content = implode("\n\n", [
            'Kegiatan sosialisasi program sekolah kepada orang tua/wali dan siswa telah dilaksanakan pada hari Sabtu, 3 Oktober 2026 di lingkungan sekolah.',
            'Acara ini bertujuan menyelaraskan pemahaman antara pihak sekolah, orang tua, dan peserta didik mengenai program pembelajaran, tata tertib, kegiatan ekstrakurikuler, serta rencana praktik kerja lapangan.',
            'Melalui pertemuan ini, diharapkan terjalin komunikasi dan sinergi yang kuat antara sekolah dan keluarga guna mendukung keberhasilan akademik maupun pembentukan karakter siswa.',
        ]);

        Post::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'category_id' => $category->id,
                'type' => Post::TYPE_BERITA,
                'jurusan' => null,
                'title' => $title,
                'excerpt' => 'Sosialisasi program sekolah kepada orang tua dan siswa dilaksanakan pada 3 Oktober 2026 untuk menyelaraskan program pembelajaran dan pembinaan karakter.',
                'content' => $content,
                'cover_image' => 'img/berita-1.svg',
                'user_id' => $author->id,
                'status' => 'published',
                'published_at' => $publishedAt,
                'is_featured' => true,
            ],
        );

        $this->command->info('BeritaSeeder: berita sosialisasi program sekolah (3 Oktober 2026) siap.');
    }
}
