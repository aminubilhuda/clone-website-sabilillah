<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerController extends Controller
{
    /**
     * Daftar mitra & lisensi.
     */
    public function index(): View
    {
        return view('admin.partners.index', [
            'partners' => Partner::query()->orderBy('group')->orderBy('sort_order')->get(),
        ]);
    }

    /**
     * Form mitra baru.
     */
    public function create(): View
    {
        return view('admin.partners.form', ['partner' => new Partner]);
    }

    /**
     * Simpan mitra.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $partner = Partner::query()->create($validated);

        $partner->update(['logo_path' => Uploads::file($request, 'logo', $partner->logo_path)]);

        return redirect()->route('admin.partners.index')->with('success', 'Mitra berhasil ditambahkan.');
    }

    /**
     * Form ubah mitra.
     */
    public function edit(Partner $partner): View
    {
        return view('admin.partners.form', ['partner' => $partner]);
    }

    /**
     * Perbarui mitra.
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['logo_path'] = Uploads::file($request, 'logo', $partner->logo_path);

        $partner->update($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Mitra berhasil diperbarui.');
    }

    /**
     * Hapus mitra.
     */
    public function destroy(Partner $partner): RedirectResponse
    {
        Uploads::delete($partner->logo_path);

        $partner->delete();

        return back()->with('success', 'Mitra berhasil dihapus.');
    }

    /**
     * Validasi data mitra.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string', 'in:klien,lisensi'],
            'website_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'image', 'max:10240'],
        ]);
    }
}
