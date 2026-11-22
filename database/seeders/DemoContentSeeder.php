<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\AlbumItem;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\VisitorStat;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

/**
 * Mengisi konten demo SMK Abdi Negara Tuban: pengguna panel admin,
 * artikel beragam jenis, album galeri, dan statistik pengunjung.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        /* ------------------------------------------------------------------
         | Pengurus panel
         | ------------------------------------------------------------------ */
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@smkabdinegara.sch.id'],
            [
                'name' => 'Admin Abdi Negara',
                'username' => 'admin',
                'password' => 'password',
                'role' => 'admin',
            ],
        );

        $editor = User::query()->firstOrCreate(
            ['email' => 'editor@smkabdinegara.sch.id'],
            [
                'name' => 'Editor Abdi Negara',
                'username' => 'editor',
                'password' => 'password',
                'role' => 'editor',
            ],
        );

        $kategoriByType = Category::query()
            ->get(['id', 'type', 'slug'])
            ->groupBy('type');

        /* ------------------------------------------------------------------
         | Artikel beragam jenis
         | ------------------------------------------------------------------ */
        $berita = [
            ['Praktik Konfigurasi Jaringan di Lab TKJ', 'kegiatan-siswa', 'tkj', 'img/berita-1.svg'],
            ['Siswa RPL Kembangkan Aplikasi Kasir Sekolah', 'prestasi', 'rpl', 'img/berita-2.svg'],
            ['Kunjungan Industri ke Perusahaan Mitra', 'pkl-industri', null, 'img/berita-3.svg'],
            ['Lomba Kompetensi Siswa Tingkat Kabupaten', 'prestasi', null, 'img/berita-4.svg'],
            ['Ekstrakurikuler Robotik Mulai Latihan Perdana', 'kegiatan-siswa', null, 'img/berita-5.svg'],
            ['Peringatan Hari Besar Nasional di Sekolah', 'umum', null, 'img/berita-6.svg'],
            ['Bakti Sosial Siswa SMK Abdi Negara Tuban', 'umum', null, 'img/berita-7.svg'],
            ['Uji Kompetensi Keahlian Berjalan Lancar', 'akademik', null, 'img/berita-8.svg'],
            ['Field Trip DKV ke Studio Kreatif', 'kegiatan-siswa', 'dkv', 'img/berita-1.svg'],
            ['Kelas AKL Belajar Menyusun Laporan Keuangan', 'akademik', 'akl', 'img/berita-3.svg'],
        ];

        $pengumuman = [
            ['Pendaftaran Peserta Didik Baru Dibuka', 'ppdb', 'img/pengumuman-1.svg'],
            ['Jadwal Uji Kompetensi Keahlian', 'pengumuman', 'img/pengumuman-2.svg'],
            ['Pembaruan Jam Layanan Sekolah', 'pengumuman', 'img/pengumuman-1.svg'],
            ['Pemberitahuan Libur Semester', 'pengumuman', 'img/pengumuman-2.svg'],
        ];

        $agenda = [
            ['Masa Pengenalan Lingkungan Sekolah', 'agenda-sekolah', 'img/agenda-1.svg'],
            ['Uji Kompetensi Keahlian', 'agenda-sekolah', 'img/agenda-2.svg'],
            ['Class Meeting Semester Genap', 'agenda-sekolah', 'img/agenda-3.svg'],
            ['Parenting Day Orang Tua Siswa', 'agenda-sekolah', 'img/agenda-4.svg'],
            ['Kunjungan Industri Jurusan TKJ', 'agenda-sekolah', 'img/agenda-1.svg'],
            ['Bimbingan Karier Bersama BKK', 'agenda-sekolah', 'img/agenda-2.svg'],
        ];

        $tulisan = [
            ['Belajar dari Praktik Nyata', 'inspirasi-edukasi', 'img/tulisan-1.svg'],
            ['Menumbuhkan Disiplin Kerja Sejak Sekolah', 'inspirasi-edukasi', 'img/tulisan-2.svg'],
            ['Membangun Budaya Sekolah yang Positif', 'inspirasi-edukasi', 'img/tulisan-3.svg'],
            ['Pendidikan Vokasi dan Masa Depan Kerja', 'inspirasi-edukasi', 'img/tulisan-4.svg'],
            ['Kiat Menjaga Semangat Belajar di Semester Akhir', 'inspirasi-edukasi', 'img/tulisan-1.svg'],
            ['Kolaborasi Sekolah dan Orang Tua', 'inspirasi-edukasi', 'img/tulisan-2.svg'],
        ];

        $media = [
            ['Semangat Belajar di Laboratorium', 'media-siswa', 'img/media-1.svg'],
            ['Karya Digital Siswa RPL', 'media-siswa', 'img/media-2.svg'],
            ['Dokumentasi Kegiatan Sekolah', 'media-siswa', 'img/media-3.svg'],
            ['Video Profil Kompetensi Keahlian', 'media-siswa', 'img/media-4.svg'],
            ['Cerita Praktik Kerja Lapangan', 'media-siswa', 'img/media-1.svg'],
        ];

        $majalah = [
            ['Majalah Edisi PPDB', 'img/majalah-1.svg'],
            ['Majalah Edisi Uji Kompetensi', 'img/majalah-2.svg'],
            ['Majalah Edisi Praktik Industri', 'img/majalah-3.svg'],
            ['Majalah Edisi Hari Besar', 'img/majalah-4.svg'],
        ];

        $paragraf = [
            'Kegiatan berlangsung di lingkungan sekolah dengan pendampingan guru produktif. Siswa terlibat langsung dalam setiap tahapan praktik.',
            'Pembelajaran menekankan keselamatan kerja, ketelitian, dan kerja sama tim sesuai budaya industri.',
            'Guru memastikan setiap siswa memperoleh kesempatan mencoba peralatan dan prosedur yang dipelajari.',
            'Sekolah juga menghadirkan praktisi dari mitra industri untuk berbagi pengalaman kerja nyata.',
            'Orang tua memberikan dukungan dengan mengikuti komunikasi rutin bersama wali kelas.',
            'Kegiatan ditutup dengan refleksi bersama sebagai bahan perbaikan pembelajaran berikutnya.',
        ];

        $buat = function (
            string $type,
            string $title,
            ?string $categorySlug,
            ?string $jurusan,
            ?string $cover,
            array $opsi = [],
        ) use ($admin, $editor, $kategoriByType, $paragraf, &$noSerial): Post {
            $noSerial ??= 0;

            $kategori = $categorySlug === null
                ? null
                : ($kategoriByType->get($type)?->firstWhere('slug', $categorySlug)?->id);

            $opening = 'Kegiatan "'.$title.'" berjalan dengan antusias di lingkungan sekolah. Pembelajaran berlangsung aktif, kreatif, dan menyenangkan sejak sesi pertama.';

            $isi = collect([$opening, ...$paragraf])
                ->shuffle()
                ->take(3)
                ->implode("\n\n");

            $noSerial++;

            return Post::query()->create([
                'category_id' => $kategori,
                'type' => $type,
                'jurusan' => $jurusan,
                'title' => $title,
                'slug' => str($title)->slug().'-'.(1000 + $noSerial),
                'excerpt' => str($isi)->limit(180),
                'content' => $isi,
                'cover_image' => $cover,
                'user_id' => $noSerial % 2 === 0 ? $editor->id : $admin->id,
                'status' => 'published',
                'published_at' => now()->subDays(($noSerial * 5) % 90),
                'is_featured' => $opsi['featured'] ?? false,
                'starts_at' => $opsi['starts_at'] ?? null,
                'ends_at' => $opsi['ends_at'] ?? null,
                'views' => rand(40, 900),
            ]);
        };

        foreach ($berita as $i => [$title, $categorySlug, $jurusan, $cover]) {
            $buat(Post::TYPE_BERITA, $title, $categorySlug, $jurusan, $cover, ['featured' => $i < 4]);
        }

        foreach ($pengumuman as $i => [$title, $categorySlug, $cover]) {
            $buat(Post::TYPE_PENGUMUMAN, $title, $categorySlug, null, $cover, ['featured' => $i === 0]);
        }

        foreach ($agenda as $i => [$title, $categorySlug, $cover]) {
            $mulai = CarbonImmutable::today()->addDays(($i + 2) * 4)->setTime(8, 0);

            $buat(Post::TYPE_AGENDA, $title, $categorySlug, null, $cover, [
                'starts_at' => $mulai,
                'ends_at' => $mulai->addHours(3),
            ]);
        }

        foreach ($tulisan as [$title, $categorySlug, $cover]) {
            $buat(Post::TYPE_TULISAN, $title, $categorySlug, null, $cover);
        }

        foreach ($media as [$title, $categorySlug, $cover]) {
            $buat(Post::TYPE_MEDIA, $title, $categorySlug, null, $cover);
        }

        foreach ($majalah as $i => [$title, $cover]) {
            $buat(Post::TYPE_MAJALAH, $title, null, null, $cover, ['featured' => $i === 0]);
        }

        // Tokoh = testimoni alumni dan mitra industri untuk bagian "Kata Mereka"
        $testi = [
            ['Alumni TKJ — kini bekerja sebagai teknisi jaringan', 'Bekal praktik di laboratorium membuat saya lebih percaya diri saat masuk dunia kerja.', 'img/avatar-1.svg'],
            ['Alumni RPL — mahasiswa informatika', 'Kebiasaan membuat proyek di sekolah sangat membantu saya melanjutkan studi.', 'img/avatar-2.svg'],
            ['Mitra industri — supervisor produksi', 'Siswa PKL dari sekolah ini disiplin, cepat belajar, dan mampu bekerja dalam tim.', 'img/avatar-3.svg'],
            ['Orang tua siswa DKV', 'Anak saya jadi lebih terarah dan bangga dengan karya desainnya sendiri.', 'img/avatar-4.svg'],
            ['Alumni AKL — staf keuangan', 'Sekolah membiasakan ketelitian dan tanggung jawab dalam setiap tugas.', 'img/avatar-1.svg'],
            ['Alumni MPLB — customer service', 'Latihan komunikasi dan pelayanan membuat saya siap menghadapi pelanggan.', 'img/avatar-2.svg'],
        ];

        foreach ($testi as $i => [$judul, $konten, $cover]) {
            Post::query()->create([
                'category_id' => null,
                'type' => Post::TYPE_TOKOH,
                'jurusan' => null,
                'title' => $judul,
                'slug' => 'testimoni-'.($i + 1),
                'excerpt' => $konten,
                'content' => $konten,
                'cover_image' => $cover,
                'user_id' => $admin->id,
                'status' => 'published',
                'published_at' => now()->subDays($i + 2),
            ]);
        }

        /* ------------------------------------------------------------------
         | Album galeri
         | ------------------------------------------------------------------ */
        $album = [
            ['Kegiatan Siswa', 'Momen keseruan kegiatan siswa di sekolah.', 'foto', 'img/album-1.svg'],
            ['Ekstrakurikuler', 'Galeri aktivitas ekstrakurikuler siswa.', 'foto', 'img/album-2.svg'],
            ['Praktik Industri', 'Dokumentasi praktik kerja lapangan siswa.', 'foto', 'img/album-3.svg'],
            ['Video Profil Sekolah', 'Kumpulan video profil dan kegiatan sekolah.', 'video', 'img/album-4.svg'],
            ['Dokumentasi Kegiatan', 'Video dokumentasi acara dan pembelajaran.', 'video', 'img/album-5.svg'],
        ];

        foreach ($album as [$title, $description, $kind, $cover]) {
            $newAlbum = Album::query()->create([
                'title' => $title,
                'slug' => str($title)->slug(),
                'kind' => $kind,
                'description' => $description,
                'cover_image' => $cover,
            ]);

            for ($j = 0; $j < 6; $j++) {
                if ($kind === 'foto') {
                    AlbumItem::query()->create([
                        'album_id' => $newAlbum->id,
                        'media_path' => sprintf('img/berita-%d.svg', ($j % 8) + 1),
                        'caption' => 'Dokumentasi kegiatan #'.($j + 1),
                        'sort_order' => $j,
                    ]);
                } else {
                    AlbumItem::query()->create([
                        'album_id' => $newAlbum->id,
                        'media_url' => '#',
                        'caption' => 'Video dokumentasi #'.($j + 1),
                        'sort_order' => $j,
                    ]);
                }
            }
        }

        /* ------------------------------------------------------------------
         | Statistik pengunjung (dummy 120 hari terakhir)
         | ------------------------------------------------------------------ */
        $mulai = CarbonImmutable::today()->subDays(119);

        foreach (range(0, 119) as $no) {
            VisitorStat::query()->create([
                'date' => $mulai->copy()->addDays($no)->toDateString(),
                'views' => rand(60, 460),
            ]);
        }

        $this->command->info('DemoContentSeeder: pengguna, pos, album, dan statistik pengunjung tersimpan.');
    }
}
