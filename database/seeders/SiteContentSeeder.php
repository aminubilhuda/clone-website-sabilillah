<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use App\Models\HeroSlider;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Popup;
use App\Models\PpdbFee;
use App\Models\PpdbRequirement;
use App\Models\PpdbWave;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Menyiapkan konten inti situs SMK Abdi Negara Tuban: pengaturan umum,
 * tema warna, menu, halaman profil/PPDB/jurusan, slider hero, popup PPDB,
 * kategori, mitra industri & lembaga sertifikasi, serta FAQ.
 */
class SiteContentSeeder extends Seeder
{
    /**
     * Stores a scalar setting value in the wrapped JSON form.
     */
    private function set(string $group, string $key, mixed $value): void
    {
        Setting::query()->create(['group' => $group, 'key' => $key, 'value' => ['v' => $value]]);
    }

    public function run(): void
    {
        /* ------------------------------------------------------------------
         | Pengaturan identitas situs
         | ------------------------------------------------------------------ */
        $umum = [
            'nama_sekolah' => 'SMK Abdi Negara Tuban',
            'logo' => 'img/logo-sekolah.svg',
            'slogan' => 'Unggul, Berkarakter, Siap Kerja',
            'sub_slogan' => 'Sekolah Vokasi Masa Depan',
            'tentang' => 'SMK Abdi Negara Tuban adalah sekolah menengah kejuruan yang memadukan pembelajaran akademik, praktik kejuruan, dan pembinaan karakter. Lima kompetensi keahlian disiapkan agar lulusan mampu bekerja, melanjutkan studi, dan berwirausaha.',
            'alamat' => 'Jl. Contoh Raya No. 123, Tuban, Jawa Timur 62300',
            'telfon' => '(0356) 123-456 / 0812-3456-7890',
            'email' => 'info@smkabdinegara.sch.id',
            'whatsapp' => '6281234567890',
            'jam_layanan' => 'Layanan administrasi Senin s.d. Jumat pukul 07.00 WIB hingga 15.30 WIB',
            'facebook' => '#',
            'instagram' => '#',
            'tiktok' => '#',
            'youtube' => '#',
            'gmaps_embed' => 'https://www.google.com/maps?q=Tuban,Jawa+Timur&output=embed',
            'meta_description' => 'SMK Abdi Negara Tuban — sekolah menengah kejuruan dengan kompetensi keahlian TKJ, RPL, DKV, AKL, dan MPLB. Unggul, berkarakter, siap kerja.',
            'meta_keywords' => 'smk abdi negara tuban, smk tuban, ppdb smk, tkj, rpl, dkv, akl, mplb',
            'meta_author' => 'SMK Abdi Negara Tuban',
            'google_analytics' => '',
        ];

        foreach ($umum as $key => $value) {
            $this->set('umum', $key, $value);
        }

        $this->set('tampilan', 'warna_primer', '#1b5faa');
        $this->set('tampilan', 'warna_gelap', '#0e3d73');
        $this->set('tampilan', 'warna_navy', '#072a52');
        $this->set('tampilan', 'warna_lembut', '#e7f0fa');

        $this->set('ppdb', 'banner_text', 'Penerimaan Peserta Didik Baru 2026/2027');
        $this->set('ppdb', 'banner_link', 'ppdb');
        $this->set('ppdb', 'banner_image', 'img/banner-ppdb.svg');
        $this->set('ppdb', 'banner_active', true);

        /* ------------------------------------------------------------------
         | Menu navigasi header & footer
         | ------------------------------------------------------------------ */
        $header = [
            ['Beranda', '/', []],
            ['Profil', 'halaman/sejarah', [
                ['Sejarah', 'halaman/sejarah'],
                ['Visi & Misi', 'halaman/visi-misi'],
                ['Struktur Organisasi', 'halaman/struktur-organisasi'],
                ['Sambutan Kepala Sekolah', 'halaman/sambutan-kepala-sekolah'],
                ['Guru & Staf', 'halaman/guru-staf'],
                ['Fasilitas', 'halaman/fasilitas'],
            ]],
            ['Jurusan', 'jurusan/tkj', [
                ['Teknik Komputer dan Jaringan', 'jurusan/tkj'],
                ['Rekayasa Perangkat Lunak', 'jurusan/rpl'],
                ['Desain Komunikasi Visual', 'jurusan/dkv'],
                ['Akuntansi dan Keuangan Lembaga', 'jurusan/akl'],
                ['Manajemen Perkantoran dan Layanan Bisnis', 'jurusan/mplb'],
            ]],
            ['PPDB', 'ppdb', [
                ['Informasi PPDB', 'ppdb'],
                ['Formulir Pendaftaran', 'ppdb#formulir'],
            ]],
            ['Publikasi', 'berita', [
                ['Berita', 'berita'],
                ['Pengumuman', 'pengumuman'],
                ['Agenda', 'agenda'],
                ['Tulisan Inspirasi', 'tulisan-inspirasi'],
                ['Media Siswa', 'media-siswa'],
                ['Foto & Album', 'foto'],
                ['Video', 'video'],
                ['Majalah', 'majalah'],
            ]],
            ['Layanan', 'halaman/bkk-pkl', [
                ['BKK & PKL', 'halaman/bkk-pkl'],
                ['Ekstrakurikuler', 'ekstrakurikuler'],
                ['Kerja Sama Industri', 'kerja-sama'],
            ]],
            ['Kontak', 'hubungi-kami', [
                ['Hubungi Kami', 'hubungi-kami'],
                ['FAQ', 'faq'],
            ]],
        ];

        foreach ($header as $order => [$label, $url, $children]) {
            $parent = Menu::query()->create([
                'label' => $label,
                'url' => $url,
                'position' => 'header',
                'sort_order' => $order,
            ]);

            foreach ($children as $childOrder => [$childLabel, $childUrl]) {
                Menu::query()->create([
                    'parent_id' => $parent->id,
                    'label' => $childLabel,
                    'url' => $childUrl,
                    'position' => 'header',
                    'sort_order' => $childOrder,
                ]);
            }
        }

        $footer = [
            ['Tautan', [
                ['Berita', 'berita'],
                ['Agenda', 'agenda'],
                ['Foto & Album', 'foto'],
                ['Majalah', 'majalah'],
            ]],
            ['Layanan', [
                ['BKK & PKL', 'halaman/bkk-pkl'],
                ['Kerja Sama Industri', 'kerja-sama'],
                ['FAQ', 'faq'],
                ['Sitemap', 'sitemap.xml'],
            ]],
        ];

        foreach ($footer as $columnIndex => [$label, $children]) {
            $parent = Menu::query()->create([
                'label' => $label,
                'url' => '#',
                'position' => 'footer',
                'sort_order' => $columnIndex,
            ]);

            foreach ($children as $childOrder => [$childLabel, $childUrl]) {
                Menu::query()->create([
                    'parent_id' => $parent->id,
                    'label' => $childLabel,
                    'url' => $childUrl,
                    'position' => 'footer',
                    'sort_order' => $childOrder,
                ]);
            }
        }

        /* ------------------------------------------------------------------
         | Halaman profil, PPDB, dan jurusan
         | ------------------------------------------------------------------ */
        $pages = [
            // Profil
            ['sejarah', 'Sejarah Sekolah', 'Perjalanan berdirinya SMK Abdi Negara Tuban hingga saat ini.', 'profil', [
                'intro' => 'SMK Abdi Negara Tuban tumbuh dari kepedulian masyarakat Tuban terhadap pendidikan vokasi yang bermutu dan terjangkau.',
                'isi' => [
                    'Pada awal berdirinya, sekolah membuka dua rombongan belajar dengan fokus pada keterampilan teknik dan bisnis yang dibutuhkan dunia kerja setempat.',
                    'Seiring berjalannya waktu, laboratorium praktik dilengkapi dan kerja sama dengan dunia usaha serta dunia industri terus diperluas.',
                    'Hingga kini sekolah mengelola lima kompetensi keahlian dengan pendampingan karier melalui Bursa Kerja Khusus (BKK).',
                ],
            ]],
            ['visi-misi', 'Visi & Misi', 'Arah dan komitmen penyelenggaraan pendidikan vokasi.', 'profil', [
                'visi' => 'Menjadi sekolah menengah kejuruan unggul yang menghasilkan lulusan berkarakter, kompeten, dan siap bersaing di dunia kerja.',
                'misi' => [
                    'Menyelenggarakan pembelajaran kejuruan yang selaras dengan kebutuhan dunia usaha dan dunia industri.',
                    'Membina karakter, kedisiplinan, dan etika kerja seluruh peserta didik.',
                    'Mengembangkan kompetensi guru dan sarana praktik secara berkelanjutan.',
                    'Memperluas kemitraan industri untuk praktik kerja, sertifikasi, dan penyaluran lulusan.',
                    'Menumbuhkan jiwa wirausaha melalui pembelajaran berbasis proyek.',
                ],
            ]],
            ['struktur-organisasi', 'Struktur Organisasi', 'Susunan pengelola SMK Abdi Negara Tuban.', 'profil', [
                'intro' => 'Pengelolaan sekolah dijalankan secara kolaboratif oleh pimpinan, guru, dan tenaga kependidikan.',
                'struktur' => [
                    ['Kepala Sekolah', 'Pimpinan satuan pendidikan'],
                    ['Wakil Kepala Bidang Kurikulum', 'Pengelolaan pembelajaran dan penilaian'],
                    ['Wakil Kepala Bidang Kesiswaan', 'Pembinaan siswa dan ekstrakurikuler'],
                    ['Wakil Kepala Bidang Sarana Prasarana', 'Laboratorium dan fasilitas praktik'],
                    ['Koordinator BKK', 'Praktik kerja lapangan dan penyaluran kerja'],
                    ['Kepala Tata Usaha', 'Administrasi dan keuangan sekolah'],
                ],
            ]],
            ['sambutan-kepala-sekolah', 'Sambutan Kepala Sekolah', 'Pesan pimpinan sekolah kepada siswa, orang tua, dan mitra industri.', 'profil', [
                'intro' => 'Assalamualaikum warahmatullahi wabarakatuh. Salam sejahtera bagi kita semua.',
                'sambutan' => 'Selamat datang di SMK Abdi Negara Tuban. Kami berkomitmen menyiapkan generasi yang kompeten di bidangnya, berkarakter kuat, dan siap menghadapi perubahan dunia kerja. Dukungan orang tua serta mitra industri menjadi kunci keberhasilan pendidikan vokasi. Mari bersama membangun masa depan siswa melalui pembelajaran yang bermakna dan praktik yang nyata.',
            ]],
            ['guru-staf', 'Guru & Staf', 'Tenaga pendidik dan kependidikan SMK Abdi Negara Tuban.', 'profil', [
                'intro' => 'Guru produktif dan tenaga kependidikan mendampingi siswa di kelas, laboratorium, dan kegiatan praktik.',
                'guru' => [
                    ['Tim Kurikulum', 'Koordinator pembelajaran'],
                    ['Guru Produktif TKJ', 'Jaringan dan infrastruktur digital'],
                    ['Guru Produktif RPL', 'Pemrograman dan pengembangan aplikasi'],
                    ['Guru Produktif DKV', 'Desain grafis dan multimedia'],
                    ['Guru Produktif AKL', 'Akuntansi dan keuangan'],
                    ['Guru Produktif MPLB', 'Manajemen perkantoran dan bisnis'],
                ],
            ]],
            ['fasilitas', 'Fasilitas Sekolah', 'Sarana pendukung pembelajaran dan praktik kejuruan.', 'profil', [
                'intro' => 'Sarana praktik disiapkan agar siswa terbiasa bekerja dengan peralatan dan prosedur industri.',
                'fasilitas' => [
                    'Laboratorium Komputer',
                    'Laboratorium Jaringan',
                    'Studio Desain & Multimedia',
                    'Laboratorium Akuntansi',
                    'Ruang Simulasi Perkantoran',
                    'Teaching Factory',
                    'Perpustakaan Sekolah',
                    'Aula Serbaguna',
                ],
            ]],
            ['bkk-pkl', 'BKK & PKL', 'Bursa Kerja Khusus dan Praktik Kerja Lapangan siswa.', 'profil', [
                'intro' => 'BKK membantu siswa memperoleh pengalaman kerja melalui PKL serta mendampingi penyaluran lulusan ke mitra industri.',
                'isi' => [
                    'Praktik Kerja Lapangan dilaksanakan pada semester akhir sesuai jadwal masing-masing kompetensi keahlian.',
                    'Sekolah menjalin kerja sama dengan perusahaan dan lembaga mitra untuk tempat praktik, uji kompetensi, dan rekrutmen lulusan.',
                    'Siswa mendapat bimbingan penyusunan laporan PKL, etika kerja, dan persiapan wawancara kerja.',
                ],
                'keunggulan' => [
                    'Pendampingan guru pembimbing PKL',
                    'Mitra industri di wilayah Tuban dan sekitarnya',
                    'Penyaluran kerja dan informasi lowongan',
                ],
                'gambar' => 'img/bkk-pkl.svg',
            ]],

        ];

        // Jurusan
        $jurusan = [
            ['tkj', 'Teknik Komputer dan Jaringan', 'Kompetensi Keahlian TKJ', [
                'deskripsi' => 'Mempelajari instalasi jaringan, konfigurasi perangkat, keamanan siber, dan layanan infrastruktur digital.',
                'keunggulan' => ['Laboratorium jaringan dan server', 'Praktik konfigurasi perangkat industri', 'Sertifikasi kompetensi jaringan'],
                'prospek' => ['Teknisi jaringan', 'Administrator server', 'Helpdesk IT', 'Wirausaha jasa IT'],
                'gambar' => 'img/jurusan-tkj.svg',
            ]],
            ['rpl', 'Rekayasa Perangkat Lunak', 'Kompetensi Keahlian RPL', [
                'deskripsi' => 'Mempelajari pemrograman, basis data, pengembangan aplikasi web dan mobile, serta pengujian perangkat lunak.',
                'keunggulan' => ['Pembelajaran berbasis proyek aplikasi', 'Laboratorium komputer modern', 'Portofolio produk digital siswa'],
                'prospek' => ['Programmer junior', 'Web developer', 'Quality assurance', 'Startup digital'],
                'gambar' => 'img/jurusan-rpl.svg',
            ]],
            ['dkv', 'Desain Komunikasi Visual', 'Kompetensi Keahlian DKV', [
                'deskripsi' => 'Mempelajari desain grafis, fotografi, videografi, animasi, dan produksi konten visual untuk berbagai media.',
                'keunggulan' => ['Studio desain dan multimedia', 'Praktik produksi konten digital', 'Pameran karya siswa'],
                'prospek' => ['Desainer grafis', 'Editor video', 'Content creator', 'Studio kreatif'],
                'gambar' => 'img/jurusan-dkv.svg',
            ]],
            ['akl', 'Akuntansi dan Keuangan Lembaga', 'Kompetensi Keahlian AKL', [
                'deskripsi' => 'Mempelajari siklus akuntansi, perpajakan, pengelolaan keuangan, dan penyusunan laporan keuangan.',
                'keunggulan' => ['Laboratorium akuntansi', 'Praktik aplikasi keuangan', 'Kerja sama kantor akuntan dan koperasi'],
                'prospek' => ['Staf akuntansi', 'Staf pajak', 'Teller bank', 'Wirausaha bidang keuangan'],
                'gambar' => 'img/jurusan-akl.svg',
            ]],
            ['mplb', 'Manajemen Perkantoran dan Layanan Bisnis', 'Kompetensi Keahlian MPLB', [
                'deskripsi' => 'Mempelajari administrasi perkantoran, layanan pelanggan, korespondensi, dan pengelolaan bisnis.',
                'keunggulan' => ['Ruang simulasi perkantoran', 'Praktik layanan bisnis', 'Pembinaan komunikasi profesional'],
                'prospek' => ['Staf administrasi', 'Customer service', 'Resepsionis', 'Wirausaha layanan bisnis'],
                'gambar' => 'img/jurusan-mplb.svg',
            ]],
        ];

        foreach ($jurusan as $order => [$slug, $title, $subtitle, $blocks]) {
            Page::query()->create([
                'slug' => $slug,
                'title' => $title,
                'subtitle' => $subtitle,
                'group' => 'jurusan',
                'blocks' => $blocks,
                'sort_order' => $order + 1,
            ]);
        }

        foreach ($pages as [$slug, $title, $subtitle, $group, $blocks]) {
            Page::query()->create([
                'slug' => $slug,
                'title' => $title,
                'subtitle' => $subtitle,
                'group' => $group,
                'blocks' => $blocks,
            ]);
        }

        /* ------------------------------------------------------------------
         | Slider hero, popup PPDB, kategori, mitra, FAQ
         | ------------------------------------------------------------------ */
        $sliders = [
            ['img/hero-1.svg', 'SMK Abdi Negara Tuban', 'halaman/sejarah'],
            ['img/hero-2.svg', 'Belajar Praktik di Laboratorium', 'jurusan/tkj'],
            ['img/hero-3.svg', 'Lima Kompetensi Keahlian', 'jurusan/rpl'],
            ['img/hero-4.svg', 'PKL Bersama Mitra Industri', 'halaman/bkk-pkl'],
            ['img/hero-5.svg', 'Prestasi & Sertifikasi Siswa', 'berita'],
        ];

        foreach ($sliders as $order => [$image, $title, $link]) {
            HeroSlider::query()->create([
                'image' => $image,
                'title' => $title,
                'link' => $link,
                'sort_order' => $order,
            ]);
        }

        Popup::query()->create([
            'image' => 'img/popup-ppdb.svg',
            'link' => 'ppdb',
            'is_active' => true,
        ]);

        $categories = [
            ['Kegiatan Siswa', 'kegiatan-siswa', 'berita'],
            ['Prestasi', 'prestasi', 'berita'],
            ['PKL & Industri', 'pkl-industri', 'berita'],
            ['Akademik', 'akademik', 'berita'],
            ['Umum', 'umum', 'berita'],
            ['Pengumuman', 'pengumuman', 'pengumuman'],
            ['PPDB', 'ppdb', 'pengumuman'],
            ['Agenda Sekolah', 'agenda-sekolah', 'agenda'],
            ['Inspirasi Edukasi', 'inspirasi-edukasi', 'tulisan'],
            ['Media Siswa', 'media-siswa', 'media'],
        ];

        foreach ($categories as [$name, $slug, $type]) {
            Category::query()->create(['name' => $name, 'slug' => $slug, 'type' => $type]);
        }

        $partners = [
            ['Mitra Industri 1', 'img/partner-1.svg', 'klien'],
            ['Mitra Industri 2', 'img/partner-2.svg', 'klien'],
            ['Mitra Industri 3', 'img/partner-3.svg', 'klien'],
            ['Mitra Industri 4', 'img/partner-4.svg', 'klien'],
            ['Mitra Industri 5', 'img/partner-5.svg', 'klien'],
            ['Mitra Industri 6', 'img/partner-6.svg', 'klien'],
            ['Mitra Industri 7', 'img/partner-7.svg', 'klien'],
            ['Mitra Industri 8', 'img/partner-8.svg', 'klien'],
            ['Lembaga Sertifikasi 1', 'img/lisensi-1.svg', 'lisensi'],
            ['Lembaga Sertifikasi 2', 'img/lisensi-2.svg', 'lisensi'],
            ['Lembaga Sertifikasi 3', 'img/lisensi-3.svg', 'lisensi'],
            ['Lembaga Sertifikasi 4', 'img/lisensi-4.svg', 'lisensi'],
        ];

        foreach ($partners as $order => [$name, $logo, $group]) {
            Partner::query()->create([
                'name' => $name,
                'group' => $group,
                'logo_path' => $logo,
                'website_url' => '#',
                'sort_order' => $order,
            ]);
        }

        $faqs = [
            ['Kapan pendaftaran peserta didik baru dibuka?', 'Pendaftaran dibuka sesuai jadwal panitia PPDB setiap tahun ajaran. Informasi terbaru dapat dilihat pada banner PPDB di beranda atau dengan menghubungi kantor sekolah.'],
            ['Apa saja syarat pendaftaran siswa baru?', 'Calon peserta didik menyiapkan fotokopi akta kelahiran, kartu keluarga, ijazah atau surat keterangan lulus, dan pas foto terbaru. Panitia akan membantu bila ada dokumen yang belum lengkap.'],
            ['Apakah bisa memilih jurusan saat mendaftar?', 'Ya. Calon peserta didik memilih satu kompetensi keahlian: TKJ, RPL, DKV, AKL, atau MPLB. Sekolah akan memberikan penjelasan mengenai kurikulum dan prospek masing-masing jurusan.'],
            ['Apakah ada program praktik kerja lapangan?', 'Ada. PKL dilaksanakan di mitra industri sesuai jadwal jurusan, dengan bimbingan guru pembimbing dan pembimbing lapangan.'],
            ['Apakah sekolah membantu penyaluran kerja?', 'BKK sekolah menyediakan informasi lowongan, pelatihan kesiapan kerja, dan pendampingan penyaluran lulusan ke mitra industri.'],
            ['Bagaimana prosedur pembayaran biaya pendidikan?', 'Pembayaran dilakukan melalui kantor administrasi sekolah sesuai petunjuk resmi panitia. Rincian biaya dapat dilihat pada halaman Biaya Pendidikan.'],
        ];

        foreach ($faqs as $order => [$question, $answer]) {
            Faq::query()->create([
                'question' => $question,
                'answer' => $answer,
                'category' => 'PPDB',
                'sort_order' => $order,
            ]);
        }

        $waves = [
            ['Gelombang 1', now()->addMonths(1)->startOfMonth(), now()->addMonths(1)->endOfMonth(), 0, 'Diskon Daftar Ulang Rp. 500.000'],
            ['Gelombang 2', now()->addMonths(3)->startOfMonth(), now()->addMonths(3)->endOfMonth(), 0, 'Diskon Daftar Ulang Rp. 200.000'],
            ['Gelombang 3', now()->addMonths(5)->startOfMonth(), now()->addMonths(5)->endOfMonth(), 0, null],
        ];

        foreach ($waves as $order => [$label, $mulai, $selesai, $biaya, $benefit]) {
            PpdbWave::query()->create([
                'label' => $label,
                'mulai' => $mulai,
                'selesai' => $selesai,
                'biaya' => $biaya,
                'benefit' => $benefit,
                'sort_order' => $order + 1,
                'is_active' => true,
            ]);
        }

        $syaratUmum = [
            'Telah lulus SMP/MTs/sederajat atau sedang duduk di kelas IX tahun pelajaran berjalan.',
            'Berusia maksimal 21 tahun pada tanggal 1 Juli tahun pendaftaran.',
            'Sehat jasmani dan rohani, tidak memiliki kendala fisik yang mengganggu keselamatan kerja praktik di bengkel/laboratorium.',
        ];

        foreach ($syaratUmum as $order => $content) {
            PpdbRequirement::query()->create([
                'type' => 'umum',
                'content' => $content,
                'sort_order' => $order + 1,
            ]);
        }

        $syaratBerkas = [
            'Formulir pendaftaran yang telah diisi lengkap.',
            'Fotokopi Ijazah / Surat Keterangan Lulus (SKL) yang dilegalisir (2 lembar).',
            'Fotokopi Kartu Keluarga (KK) dan KTP Orang Tua / Wali (2 lembar).',
            'Fotokopi Akta Kelahiran (2 lembar).',
            'Pas foto berwarna terbaru ukuran 3x4 (4 lembar) berlatar belakang biru/merah.',
            'Fotokopi sertifikat/piagam prestasi kejuaraan minimal tingkat kabupaten (jika mendaftar jalur prestasi).',
            'Fotokopi Kartu Indonesia Pintar (KIP) / PKH (jika memiliki).',
        ];

        foreach ($syaratBerkas as $order => $content) {
            PpdbRequirement::query()->create([
                'type' => 'berkas',
                'content' => $content,
                'sort_order' => $order + 1,
            ]);
        }

        $fees = [
            ['SPP Bulanan (Jurusan Farmasi)', 'BULANAN', 'Pembayaran SPP bulanan untuk jurusan dengan praktik laboratorium khusus.', 215000],
            ['SPP Bulanan (Selain Farmasi)', 'BULANAN', 'Pembayaran SPP bulanan untuk jurusan selain Farmasi.', 185000],
            ['Daftar Ulang', 'TAHUNAN', 'Pembayaran Daftar Ulang per tahun ajaran.', 1060000],
            ['Seragam Sekolah', 'SEKALI', 'Paket seragam sekolah (atribut harian, olahraga, dan praktik).', 850000],
            ['Kegiatan & Ekstrakurikuler', 'TAHUNAN', 'Iuran kegiatan sekolah dan ekstrakurikuler selama satu tahun.', 500000],
        ];

        foreach ($fees as $order => [$label, $category, $description, $nominal]) {
            PpdbFee::query()->create([
                'label' => $label,
                'category' => $category,
                'description' => $description,
                'nominal' => $nominal,
                'sort_order' => $order + 1,
                'is_active' => true,
            ]);
        }

        $this->command->info('SiteContentSeeder: pengaturan, tema, menu, halaman, jurusan, slider, popup, kategori, mitra, FAQ, PPDB (gelombang, persyaratan, biaya) siap.');
    }
}
