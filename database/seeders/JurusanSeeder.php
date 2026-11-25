<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusan = [
            [
                'akuntansi',
                'Akuntansi',
                'Kompetensi Keahlian Akuntansi',
                [
                    'deskripsi' => 'Mempelajari siklus akuntansi perusahaan jasa, dagang, dan manufaktur, pengelolaan keuangan, perpajakan, serta penyusunan laporan keuangan berbasis manual maupun aplikasi akuntansi.',
                    'keunggulan' => [
                        'Laboratorium akuntansi dan komputerisasi keuangan',
                        'Praktik penyusunan laporan keuangan dan perpajakan',
                        'Pembinaan ketelitian dan etika profesi keuangan',
                    ],
                    'prospek' => [
                        'Staf Akuntansi',
                        'Staf Pajak & Administrasi Keuangan',
                        'Teller / Layanan Perbankan',
                        'Wirausaha Jasa Pembukuan',
                    ],
                    'gambar' => 'img/jurusan-akl.svg',
                ],
            ],
            [
                'bisnis-digital',
                'Bisnis Digital',
                'Kompetensi Keahlian Bisnis Digital',
                [
                    'deskripsi' => 'Mempelajari strategi pemasaran digital, pengelolaan e-commerce, copywriting, analisis pasar, pengelolaan media sosial bisnis, serta manajemen operasional usaha berbasis teknologi.',
                    'keunggulan' => [
                        'Praktik langsung pemasaran digital dan pengelolaan toko online',
                        'Produksi konten promosi dan analisis performa kampanye',
                        'Pembinaan jiwa kewirausahaan dan bisnis kreatif',
                    ],
                    'prospek' => [
                        'Digital Marketer',
                        'Social Media Specialist',
                        'E-Commerce Administrator',
                        'Wirausaha Bisnis Online',
                    ],
                    'gambar' => 'img/jurusan-mplb.svg',
                ],
            ],
            [
                'perhotelan',
                'Perhotelan',
                'Kompetensi Keahlian Perhotelan',
                [
                    'deskripsi' => 'Mempelajari layanan tamu (front office), tata graha (housekeeping), pelayanan makanan dan minuman, komunikasi hospitality, serta standar operasional industri perhotelan dan pariwisata.',
                    'keunggulan' => [
                        'Praktik layanan front office dan housekeeping berstandar industri',
                        'Pembinaan hospitality, grooming, dan komunikasi profesional',
                        'Kesempatan magang di hotel dan industri pariwisata mitra',
                    ],
                    'prospek' => [
                        'Front Office Staff / Resepsionis Hotel',
                        'Housekeeping Supervisor',
                        'Guest Relations Officer',
                        'Staf Industri Pariwisata & Event',
                    ],
                    'gambar' => 'img/jurusan-dkv.svg',
                ],
            ],
            [
                'rekayasa-perangkat-lunak',
                'Rekayasa Perangkat Lunak',
                'Kompetensi Keahlian Rekayasa Perangkat Lunak',
                [
                    'deskripsi' => 'Mempelajari logika pemrograman, perancangan basis data, pengembangan aplikasi web dan mobile, antarmuka pengguna, serta pengujian dan pemeliharaan perangkat lunak.',
                    'keunggulan' => [
                        'Pembelajaran berbasis proyek pembuatan aplikasi nyata',
                        'Laboratorium komputer dengan perangkat pengembangan modern',
                        'Penyusunan portofolio produk digital siswa',
                    ],
                    'prospek' => [
                        'Web Developer',
                        'Mobile App Developer',
                        'Software Quality Assurance',
                        'Junior Programmer / Startup Digital',
                    ],
                    'gambar' => 'img/jurusan-rpl.svg',
                ],
            ],
            [
                'layanan-penunjang-kefarmasian',
                'Layanan Penunjang Kefarmasian',
                'Kompetensi Keahlian Layanan Penunjang Kefarmasian',
                [
                    'deskripsi' => 'Mempelajari dasar-dasar kefarmasian, pengelolaan perbekalan farmasi, pelayanan resep di bawah pengawasan apoteker, peracikan sediaan farmasi dasar, serta komunikasi pelayanan kesehatan.',
                    'keunggulan' => [
                        'Laboratorium praktik farmasi dan simulasi pelayanan apotek',
                        'Pembinaan kedisiplinan, ketelitian, dan standar keselamatan kerja',
                        'Kemitraan praktik kerja lapangan bersama apotek dan fasilitas kesehatan',
                    ],
                    'prospek' => [
                        'Asisten Tenaga Kefarmasian',
                        'Staf Administrasi & Gudang Farmasi',
                        'Pelayanan Apotek & Klinik',
                        'Melanjutkan Studi Farmasi / Kesehatan',
                    ],
                    'gambar' => 'img/jurusan-tkj.svg',
                ],
            ],
        ];

        Page::query()->where('group', 'jurusan')->delete();

        foreach ($jurusan as $order => [$slug, $title, $subtitle, $blocks]) {
            Page::query()->create([
                'slug' => $slug,
                'title' => $title,
                'subtitle' => $subtitle,
                'group' => 'jurusan',
                'blocks' => $blocks,
                'is_published' => true,
                'sort_order' => $order + 1,
            ]);
        }

        // Sinkronkan submenu header "Jurusan" agar tautan mengarah ke jurusan terbaru
        $jurusanMenu = Menu::query()
            ->whereNull('parent_id')
            ->where('position', 'header')
            ->where('label', 'Jurusan')
            ->first();

        if ($jurusanMenu) {
            $jurusanMenu->update(['url' => 'jurusan/'.$jurusan[0][0]]);
            Menu::query()->where('parent_id', $jurusanMenu->id)->delete();

            foreach ($jurusan as $order => [$slug, $title]) {
                Menu::query()->create([
                    'parent_id' => $jurusanMenu->id,
                    'label' => $title,
                    'url' => 'jurusan/'.$slug,
                    'position' => 'header',
                    'sort_order' => $order,
                ]);
            }
        }

        $this->command->info('JurusanSeeder: 5 jurusan beserta deskripsinya siap.');
    }
}
