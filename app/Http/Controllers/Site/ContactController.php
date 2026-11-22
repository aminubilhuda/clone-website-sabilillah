<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Partner;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    /**
     * Halaman hubungi kami / alamat kantor.
     */
    public function kantor(): View
    {
        page_meta('Hubungi Kami', 'Alamat, peta, dan kanal komunikasi resmi sekolah.', route('contact.kantor'));

        return view('contact.kantor', [
            'gmapsEmbed' => setting('gmaps_embed'),
        ]);
    }

    /**
     * Halaman kerja sama.
     */
    public function kerjaSama(): View
    {
        page_meta('Kerja Sama', 'Bentuk kolaborasi dan kerja sama dengan SMK Abdi Negara Tuban.', route('contact.kerja-sama'));

        return view('contact.kerja-sama', [
            'klien' => Partner::query()
                ->active()
                ->where('group', Partner::GROUP_KLIEN)
                ->get(),
        ]);
    }

    /**
     * Halaman tanya jawab (FAQ).
     */
    public function faq(): View
    {
        $faqs = Faq::query()->active()->get()->groupBy('category');

        page_meta('FAQ', 'Pertanyaan yang sering diajukan tentang SMK Abdi Negara Tuban.', route('contact.faq'));

        return view('contact.faq', ['faqs' => $faqs]);
    }
}
