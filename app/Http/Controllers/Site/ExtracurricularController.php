<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Contracts\View\View;

class ExtracurricularController extends Controller
{
    /**
     * Halaman daftar ekstrakurikuler.
     */
    public function index(): View
    {
        page_meta(
            'Ekstrakurikuler — '.setting('nama_sekolah'),
            'Kegiatan ekstrakurikuler di '.setting('nama_sekolah').' untuk mengembangkan minat, bakat, dan karakter siswa.',
            null,
            route('ekstrakurikuler.index'),
        );

        return view('ekstrakurikuler.index', [
            'items' => Extracurricular::query()->active()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
