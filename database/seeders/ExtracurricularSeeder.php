<?php

namespace Database\Seeders;

use App\Models\Extracurricular;
use Illuminate\Database\Seeder;

class ExtracurricularSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Musik', 'Pengembangan bakat seni musik, vokal, dan ansambel band sekolah.', 'fa-music'],
            ['Futsal', 'Latihan teknik dasar, strategi tim, dan kompetisi futsal antar sekolah.', 'fa-futbol'],
            ['Voli', 'Pembinaan teknik bermain bola voli serta kesiapan tim putra dan putri.', 'fa-volleyball-ball'],
            ['Badminton', 'Latihan kelincahan, teknik pukulan, dan turnamen bulu tangkis pelajar.', 'fa-table-tennis'],
            ['Tari', 'Pelestarian seni tari tradisional dan kreasi koreografi modern.', 'fa-theater-masks'],
            ['English Fun Club', 'Latihan percakapan bahasa Inggris, storytelling, dan public speaking secara interaktif.', 'fa-language'],
            ['PMR', 'Palang Merah Remaja: pembinaan kepedulian sosial, pertolongan pertama, dan kesehatan sekolah.', 'fa-first-aid'],
            ['Hadroh', 'Seni musik islami rebana dan sholawat untuk pembinaan karakter religius siswa.', 'fa-drum'],
            ['Kick Boxing', 'Latihan kebugaran, disiplin diri, dan teknik bela diri kick boxing.', 'fa-fist-raised'],
            ['Content Creative', 'Produksi konten digital, fotografi, videografi, dan pengelolaan media kreatif.', 'fa-camera-retro'],
            ['Pasukan Pengibar Bendera', 'Pembinaan baris-berbaris, kedisiplinan, kepemimpinan, dan tata upacara bendera.', 'fa-flag'],
            ['Pramuka', 'Kegiatan kepramukaan, kemandirian, kepemimpinan, dan perkemahan bersama.', 'fa-campground'],
        ];

        Extracurricular::query()->delete();

        foreach ($items as $order => [$name, $description, $icon]) {
            Extracurricular::query()->create([
                'name' => $name,
                'description' => $description,
                'icon' => $icon,
                'sort_order' => $order + 1,
                'is_active' => true,
            ]);
        }

        $this->command->info('ExtracurricularSeeder: 12 data ekstrakurikuler siap.');
    }
}
