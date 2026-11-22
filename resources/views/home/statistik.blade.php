<section class="statistik-si">
    <div class="container">
        <div class="row g-4">
            @php
                $statistik = [
                    ['icon' => 'fa-user-graduate', 'angka' => setting('stat_1_angka', '900+'), 'judul' => setting('stat_1_label', 'Siswa Aktif'), 'warna' => '#8ec5ff'],
                    ['icon' => 'fa-chalkboard-teacher', 'angka' => setting('stat_2_angka', '60+'), 'judul' => setting('stat_2_label', 'Guru & Staf'), 'warna' => '#b7d7ff'],
                    ['icon' => 'fa-laptop-code', 'angka' => setting('stat_3_angka', '5'), 'judul' => setting('stat_3_label', 'Kompetensi Keahlian'), 'warna' => '#d6e8ff'],
                    ['icon' => 'fa-handshake', 'angka' => setting('stat_4_angka', '40+'), 'judul' => setting('stat_4_label', 'Mitra Industri'), 'warna' => '#ffffff'],
                ];
            @endphp

            @foreach ($statistik as $item)
                <div class="col-md-3 col-sm-6 wow fadeIn" data-wow-delay="0.2s">
                    <div class="counter text-center p-4 rounded-3 bg-white bg-opacity-10">
                        <i class="fa fa-3x {{ $item['icon'] }} mb-2" style="color: {{ $item['warna'] }}"></i>
                        <h2 class="counter-angka m-0 text-white">{{ $item['angka'] }}</h2>
                        <p class="text-white-50 mb-0">{{ $item['judul'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
