<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class MitraSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['Fave Hotel', 'img/partner-1.svg'],
            ['Caris', 'img/partner-2.svg'],
            ['Bank Jatim', 'img/partner-3.svg'],
            ['Apotek Tunah Farma', 'img/partner-4.svg'],
            ['Rena Factory Outlet', 'img/partner-5.svg'],
            ['Samudra', 'img/partner-6.svg'],
        ];

        Partner::query()->where('group', Partner::GROUP_KLIEN)->delete();

        foreach ($partners as $order => [$name, $logo]) {
            Partner::query()->create([
                'name' => $name,
                'group' => Partner::GROUP_KLIEN,
                'logo_path' => $logo,
                'website_url' => '#',
                'sort_order' => $order + 1,
                'is_active' => true,
            ]);
        }

        $this->command->info('MitraSeeder: 6 mitra industri siap.');
    }
}
