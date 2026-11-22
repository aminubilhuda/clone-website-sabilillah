<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Pengaturan teks & statistik beranda. Idempoten (updateOrCreate),
 * aman dijalankan ulang dan bisa dipakai untuk instalasi baru.
 */
class HomepageSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            'stat_1_angka' => '900+',
            'stat_1_label' => 'Siswa Aktif',
            'stat_2_angka' => '60+',
            'stat_2_label' => 'Guru & Staf',
            'stat_3_angka' => '5',
            'stat_3_label' => 'Kompetensi Keahlian',
            'stat_4_angka' => '40+',
            'stat_4_label' => 'Mitra Industri',
            'sub_publikasi' => 'Zona Publikasi',
            'judul_publikasi' => 'Berita, Agenda, dan Tulisan Inspirasi',
            'judul_agenda' => 'Agenda',
            'judul_tulisan' => 'Tulisan Inspirasi',
            'sub_jurusan' => 'Kompetensi Keahlian',
            'judul_jurusan' => 'Jurusan di '.Setting::value('nama_sekolah', 'SMK Abdi Negara Tuban'),
            'sub_sorotan' => 'Sorotan',
            'judul_sorotan' => 'Menyorot Kegiatan Sekolah',
            'sub_testimoni' => 'Kata Mereka',
            'judul_testimoni' => 'Kata Alumni & Mitra Industri',
            'judul_mitra' => 'Mitra Industri',
            'judul_lisensi' => 'Lembaga Sertifikasi Profesi',
            'navbar_ppdb_text' => 'PPDB Dibuka',
            'banner_heading' => 'PPDB Saat Ini Dibuka',
            'wa_text' => 'Butuh info? Chat kami',
            'footer_kontak_judul' => 'Kontak Sekolah',
            'footer_stats_judul' => 'Statistik Website',
            'sekilas_gambar' => 'img/sekilas-sekolah.svg',
            'kerja_sama_items' => "Program pengembangan jurusan dan fasilitas praktik.\nPKL dan magang siswa.\nPelatihan, seminar industri, dan sertifikasi kompetensi.\nKemitraan CSR dan kegiatan sosial.",
        ];

        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(
                ['group' => 'beranda', 'key' => $key],
                ['value' => ['v' => $value]],
            );
        }

        $ppdb = [
            'ppdb_hero_title' => '',
            'ppdb_hero_subtitle' => '',
            'ppdb_judul_gelombang' => 'Gelombang Pendaftaran',
            'ppdb_judul_persyaratan' => 'Persyaratan Pendaftaran',
            'ppdb_judul_langkah' => 'Langkah Pendaftaran',
            'ppdb_judul_jalur' => 'Jalur Penerimaan',
            'ppdb_langkah' => "Mengisi formulir pendaftaran melalui laman PPDB atau datang langsung ke kantor sekolah.\nMelampirkan fotokopi akta kelahiran, kartu keluarga, ijazah atau surat keterangan lulus, dan pas foto terbaru.\nMengikuti tes minat dan bakat sesuai kompetensi keahlian yang dipilih.\nMelakukan pembayaran biaya pendaftaran sesuai petunjuk panitia.\nMemantau pengumuman hasil seleksi dan jadwal daftar ulang.",
            'ppdb_jalur' => "Jalur Reguler | Pendaftaran umum bagi seluruh calon peserta didik sesuai usia dan persyaratan.\nJalur Prestasi | Bagi calon peserta didik yang memiliki prestasi akademik atau non-akademik.\nJalur Afirmasi | Bagi calon peserta didik dari keluarga kurang mampu dengan dukungan program sekolah.",
            'ppdb_judul_biaya' => 'Biaya Pendidikan & SPP',
            'ppdb_judul_formulir' => 'Formulir Pendaftaran Online',
            'ppdb_judul_faq' => 'Pertanyaan yang Sering Diajukan (FAQ)',
        ];

        foreach ($ppdb as $key => $value) {
            Setting::query()->updateOrCreate(
                ['group' => 'ppdb', 'key' => $key],
                ['value' => ['v' => $value]],
            );
        }

        $halaman = [
            'kontak_judul_info' => 'Alamat & Kanal Resmi',
            'kontak_judul_peta' => 'Peta Lokasi',
            'kerja_sama_judul' => 'Bentuk Kerja Sama',
            'kerja_sama_mitra_judul' => 'Mitra Kami',
            'kerja_sama_ajakan_judul' => 'Ingin berkolaborasi?',
            'kerja_sama_ajakan_teks' => 'Silakan hubungi kantor melalui klik tombol berikut — tim kami siap menjawab.',
            'agenda_judul_mendatang' => 'Agenda Mendatang',
            'agenda_judul_lampau' => 'Agenda Lampau',
            'faq_judul' => 'Tanya Jawab',
            'faq_deskripsi' => 'Pertanyaan yang paling sering diajukan.',
            'majalah_judul' => 'Majalah',
            'majalah_deskripsi' => 'Arsip terbitan majalah sekolah.',
        ];

        foreach ($halaman as $key => $value) {
            Setting::query()->updateOrCreate(
                ['group' => 'halaman', 'key' => $key],
                ['value' => ['v' => $value]],
            );
        }

        Setting::flushCache();

        $this->command->info('HomepageSettingsSeeder: pengaturan beranda siap.');
    }
}
