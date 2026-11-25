<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Definisi field pengaturan per grup: key => [label, tipe]
     */
    private array $fields = [
        'umum' => [
            'nama_sekolah' => ['Nama Sekolah', 'text'],
            'slogan' => ['Slogan', 'text'],
            'sub_slogan' => ['Sub-slogan', 'text'],
            'tentang' => ['Tentang', 'text'],
            'alamat' => ['Alamat', 'text'],
            'telfon' => ['Telepon', 'text'],
            'email' => ['Email', 'email'],
            'whatsapp' => ['Nomor WhatsApp (format 62…)', 'text'],
            'jam_layanan' => ['Jam Layanan', 'text'],
            'facebook' => ['Facebook URL', 'text'],
            'instagram' => ['Instagram URL', 'text'],
            'tiktok' => ['TikTok URL', 'text'],
            'youtube' => ['YouTube URL', 'text'],
            'gmaps_embed' => ['Tautan Embed Google Maps', 'text'],
            'meta_description' => ['Meta Deskripsi', 'text'],
            'meta_keywords' => ['Meta Keywords', 'text'],
            'meta_author' => ['Meta Author', 'text'],
            'google_analytics' => ['Google Analytics ID (opsional)', 'text'],
        ],
        'beranda' => [
            'stat_1_angka' => ['Statistik 1 — Angka', 'text'],
            'stat_1_label' => ['Statistik 1 — Label', 'text'],
            'stat_2_angka' => ['Statistik 2 — Angka', 'text'],
            'stat_2_label' => ['Statistik 2 — Label', 'text'],
            'stat_3_angka' => ['Statistik 3 — Angka', 'text'],
            'stat_3_label' => ['Statistik 3 — Label', 'text'],
            'stat_4_angka' => ['Statistik 4 — Angka', 'text'],
            'stat_4_label' => ['Statistik 4 — Label', 'text'],
            'sub_publikasi' => ['Label Section Publikasi', 'text'],
            'judul_publikasi' => ['Judul Section Publikasi', 'text'],
            'judul_agenda' => ['Judul Sub-section Agenda', 'text'],
            'judul_tulisan' => ['Judul Sub-section Tulisan Inspirasi', 'text'],
            'sub_jurusan' => ['Label Section Jurusan', 'text'],
            'judul_jurusan' => ['Judul Section Jurusan', 'text'],
            'sub_sorotan' => ['Label Section Sorotan', 'text'],
            'judul_sorotan' => ['Judul Section Sorotan', 'text'],
            'sub_testimoni' => ['Label Section Testimoni', 'text'],
            'judul_testimoni' => ['Judul Section Testimoni', 'text'],
            'judul_mitra' => ['Judul Marquee Mitra', 'text'],
            'judul_lisensi' => ['Judul Marquee Lisensi', 'text'],
            'navbar_ppdb_text' => ['Teks Tombol PPDB di Navbar', 'text'],
            'banner_heading' => ['Judul Banner PPDB', 'text'],
            'wa_text' => ['Teks Tombol WhatsApp Mengambang', 'text'],
            'footer_kontak_judul' => ['Judul Footer — Kontak', 'text'],
            'footer_stats_judul' => ['Judul Footer — Statistik', 'text'],
            'kerja_sama_items' => ['Daftar Kerja Sama (satu per baris)', 'textarea'],
        ],
        'tampilan' => [
            'warna_primer' => ['Warna Utama (tombol & aksen)', 'color'],
            'warna_gelap' => ['Warna Gelap (navbar & judul)', 'color'],
            'warna_navy' => ['Warna Navy (footer & sidebar)', 'color'],
            'warna_lembut' => ['Warna Lembut (latar section)', 'color'],
        ],
        'halaman' => [
            'kontak_judul_info' => ['Judul Kontak — Alamat & Kanal', 'text'],
            'kontak_judul_peta' => ['Judul Kontak — Peta Lokasi', 'text'],
            'kerja_sama_judul' => ['Judul Kerja Sama — Bentuk Kerja Sama', 'text'],
            'kerja_sama_mitra_judul' => ['Judul Kerja Sama — Mitra Kami', 'text'],
            'kerja_sama_ajakan_judul' => ['Judul Ajakan Kerja Sama', 'text'],
            'kerja_sama_ajakan_teks' => ['Teks Ajakan Kerja Sama', 'textarea'],
            'agenda_judul_mendatang' => ['Judul Agenda Mendatang', 'text'],
            'agenda_judul_lampau' => ['Judul Agenda Lampau', 'text'],
            'faq_judul' => ['Judul Halaman FAQ', 'text'],
            'faq_deskripsi' => ['Deskripsi Halaman FAQ', 'text'],
            'majalah_judul' => ['Judul Halaman Majalah', 'text'],
            'majalah_deskripsi' => ['Deskripsi Halaman Majalah', 'text'],
        ],
        'ppdb' => [
            'banner_text' => ['Teks Banner PPDB', 'text'],
            'banner_link' => ['Tautan Banner PPDB', 'text'],
            'banner_image' => ['Gambar Banner PPDB (jalur publik)', 'text'],
            'ppdb_hero_title' => ['Judul Hero Halaman PPDB', 'text'],
            'ppdb_hero_subtitle' => ['Subjudul Hero Halaman PPDB', 'textarea'],
            'ppdb_judul_gelombang' => ['Judul Section Gelombang', 'text'],
            'ppdb_judul_persyaratan' => ['Judul Section Persyaratan', 'text'],
            'ppdb_judul_langkah' => ['Judul Section Langkah Pendaftaran', 'text'],
            'ppdb_judul_jalur' => ['Judul Section Jalur Penerimaan', 'text'],
            'ppdb_langkah' => ['Langkah Pendaftaran (satu per baris)', 'textarea'],
            'ppdb_jalur' => ['Jalur Penerimaan (Judul | Deskripsi per baris)', 'textarea'],
            'ppdb_judul_biaya' => ['Judul Section Biaya', 'text'],
            'ppdb_judul_formulir' => ['Judul Section Formulir', 'text'],
            'ppdb_judul_faq' => ['Judul Section FAQ', 'text'],
        ],
    ];

    /**
     * Form pengaturan.
     */
    public function edit(): View
    {
        return view('admin.settings', ['fields' => $this->fields]);
    }

    /**
     * Simpan seluruh nilai pengaturan.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'tampilan::warna_primer' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
            'tampilan::warna_gelap' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
            'tampilan::warna_navy' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
            'tampilan::warna_lembut' => ['nullable', 'regex:/^#?[0-9a-fA-F]{6}$/'],
            'umum::gmaps_embed' => ['nullable', 'url:https', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimetypes:image/png,image/jpeg,image/webp', 'max:5120'],
            'sekilas_gambar' => ['nullable', 'image', 'mimetypes:image/png,image/jpeg,image/webp', 'max:5120'],
            'banner_image_file' => ['nullable', 'image', 'mimetypes:image/png,image/jpeg,image/webp', 'max:5120'],
        ], [
            'tampilan::warna_primer.regex' => 'Warna harus dalam format hex, contoh #1B5FAA.',
            'tampilan::warna_gelap.regex' => 'Warna harus dalam format hex, contoh #0E3D73.',
            'tampilan::warna_navy.regex' => 'Warna harus dalam format hex, contoh #072A52.',
            'tampilan::warna_lembut.regex' => 'Warna harus dalam format hex, contoh #E7F0FA.',
            'umum::gmaps_embed.url' => 'URL Google Maps embed harus menggunakan skema https:// yang valid.',
            'logo.mimetypes' => 'Logo harus berupa PNG, JPG, atau WebP.',
            'logo.max' => 'Ukuran logo maksimal 5 MB.',
        ]);

        foreach (array_keys($this->fields) as $group) {
            foreach (array_keys($this->fields[$group]) as $key) {
                $input = $group.'::'.$key;

                if (! $request->has($input)) {
                    continue;
                }

                $value = strval($request->input($input, ''));

                if ($group === 'tampilan' && str_starts_with($key, 'warna_')) {
                    $value = trim($value);

                    if ($value !== '') {
                        $value = '#'.ltrim($value, '#');
                    }
                }

                Setting::query()->updateOrCreate(
                    ['group' => $group, 'key' => $key],
                    ['value' => ['v' => $value]],
                );
            }
        }

        if ($request->hasFile('logo') || $request->filled('logo_existing')) {
            $logo = Uploads::file($request, 'logo', strval(setting('logo', '')));

            Setting::query()->updateOrCreate(
                ['group' => 'umum', 'key' => 'logo'],
                ['value' => ['v' => $logo]],
            );
        }

        if ($request->hasFile('sekilas_gambar') || $request->filled('sekilas_gambar_existing')) {
            $gambar = Uploads::file($request, 'sekilas_gambar', strval(setting('sekilas_gambar', '')));

            Setting::query()->updateOrCreate(
                ['group' => 'beranda', 'key' => 'sekilas_gambar'],
                ['value' => ['v' => $gambar]],
            );
        }

        if ($request->hasFile('banner_image_file') || $request->filled('banner_image_file_existing')) {
            $banner = Uploads::file($request, 'banner_image_file', strval(setting('banner_image', '')));

            Setting::query()->updateOrCreate(
                ['group' => 'ppdb', 'key' => 'banner_image'],
                ['value' => ['v' => $banner]],
            );
        }

        $bannerActive = $request->boolean('ppdb::banner_active');

        Setting::query()->updateOrCreate(
            ['group' => 'ppdb', 'key' => 'banner_active'],
            ['value' => ['v' => $bannerActive]],
        );

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
