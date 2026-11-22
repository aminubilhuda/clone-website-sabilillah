<?php

namespace Database\Seeders;

use App\Models\Extracurricular;
use Illuminate\Database\Seeder;

class ExtracurricularSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Robotik & Pemrograman', 'Belajar merancang robot dan dasar pemrograman.', 'fa-robot'],
            ['Desain Grafis', 'Latihan desain poster, ilustrasi, dan konten digital.', 'fa-palette'],
            ['Futsal', 'Latihan rutin dan kompetisi antar sekolah.', 'fa-futbol'],
            ['Bola Voli', 'Pembinaan tim putra dan putri.', 'fa-volleyball-ball'],
            ['Pramuka', 'Kegiatan kepramukaan dan kemah bersama.', 'fa-campground'],
            ['Seni Musik', 'Band sekolah dan latihan alat musik.', 'fa-music'],
            ['English Club', 'Latihan percakapan dan public speaking.', 'fa-language'],
            ['Kewirausahaan', 'Praktik usaha dan manajemen bisnis siswa.', 'fa-store'],
        ];

        foreach ($items as $order => [$name, $description, $icon]) {
            Extracurricular::query()->firstOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'icon' => $icon,
                    'sort_order' => $order + 1,
                    'is_active' => true,
                ],
            );
        }

        $this->command->info('ExtracurricularSeeder: data ekstrakurikuler siap.');
    }
}
