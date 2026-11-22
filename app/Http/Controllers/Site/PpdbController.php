<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Models\PpdbApplicant;
use App\Models\PpdbFee;
use App\Models\PpdbRequirement;
use App\Models\PpdbWave;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PpdbController extends Controller
{
    /**
     * Halaman informasi SPMB/PPDB + formulir pendaftaran.
     */
    public function index(): View
    {
        page_meta(
            'Pusat Informasi PPDB — '.setting('nama_sekolah'),
            'Penerimaan Murid Baru '.setting('nama_sekolah').': jadwal gelombang, persyaratan, biaya, dan formulir pendaftaran online.',
            media_url('img/banner-ppdb.svg'),
            url('ppdb'),
        );

        return view('ppdb.index', [
            'waves' => PpdbWave::query()->where('is_active', true)->orderBy('sort_order')->orderBy('mulai')->get(),
            'syaratUmum' => PpdbRequirement::query()->ofType('umum')->get(),
            'syaratBerkas' => PpdbRequirement::query()->ofType('berkas')->get(),
            'jurusanOptions' => Page::query()->published()->where('group', 'jurusan')->orderBy('sort_order')->pluck('title', 'slug'),
            'faqs' => Faq::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'fees' => PpdbFee::query()->active()->orderBy('sort_order')->orderBy('id')->get(),
            'langkah' => collect(preg_split('/\R/u', strval(setting('ppdb_langkah', ''))))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all(),
            'jalur' => collect(preg_split('/\R/u', strval(setting('ppdb_jalur', ''))))
                ->map(function ($line) {
                    $parts = array_map('trim', explode('|', $line));

                    return count($parts) >= 2 && $parts[0] !== '' ? [$parts[0], $parts[1]] : null;
                })
                ->filter()
                ->values()
                ->all(),
        ]);
    }

    /**
     * Menyimpan pendaftaran baru dan mengarahkan ke WhatsApp panitia.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'wave_id' => ['nullable', 'exists:ppdb_waves,id'],
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'asal_sekolah' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:32'],
            'jurusan_2' => ['nullable', 'string', 'max:32'],
            'parent_name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:32'],
            'alamat' => ['required', 'string', 'max:1000'],
        ]);

        $wave = isset($validated['wave_id']) ? PpdbWave::query()->find($validated['wave_id']) : null;

        $applicant = PpdbApplicant::query()->create([
            'wave_id' => $wave?->id,
            'name' => $validated['name'],
            'nisn' => $validated['nisn'] ?? null,
            'asal_sekolah' => $validated['asal_sekolah'],
            'jurusan' => $validated['jurusan'],
            'jurusan_2' => $validated['jurusan_2'] ?? null,
            'parent_name' => $validated['parent_name'],
            'whatsapp' => $validated['whatsapp'],
            'alamat' => $validated['alamat'],
            'status' => 'baru',
        ]);

        $wa = (string) setting('whatsapp', '');
        $text = 'Halo Panitia PPDB '.setting('nama_sekolah').", saya ingin konfirmasi pendaftaran.\n\n"
            ."Nama: {$applicant->name}\n"
            .'NISN: '.($applicant->nisn ?: '-')."\n"
            ."Asal Sekolah: {$applicant->asal_sekolah}\n"
            ."Jurusan 1: {$applicant->jurusan}\n"
            .'Jurusan 2: '.($applicant->jurusan_2 ?: '-')."\n"
            ."Orang Tua/Wali: {$applicant->parent_name}\n"
            ."WhatsApp: {$applicant->whatsapp}\n"
            ."Alamat: {$applicant->alamat}\n"
            .'Gelombang: '.($wave?->label ?? '-')."\n"
            ."ID Pendaftaran: #{$applicant->id}";

        $url = $wa !== ''
            ? 'https://wa.me/'.$wa.'?text='.rawurlencode($text)
            : route('contact.kantor');

        return redirect()->away($url);
    }
}
