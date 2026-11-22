<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopupController extends Controller
{
    /**
     * Daftar popup PPDB.
     */
    public function index(): View
    {
        return view('admin.popups.index', [
            'popups' => Popup::query()->latest('id')->get(),
        ]);
    }

    /**
     * Form popup baru.
     */
    public function create(): View
    {
        return view('admin.popups.form', ['popup' => new Popup]);
    }

    /**
     * Simpan popup.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $popup = Popup::query()->create($validated);

        $popup->update(['image' => Uploads::file($request, 'image', $popup->image ?? 'img/popup-ppdb.svg')]);

        return redirect()->route('admin.popups.index')->with('success', 'Popup PPDB berhasil ditambahkan.');
    }

    /**
     * Form ubah popup.
     */
    public function edit(Popup $popup): View
    {
        return view('admin.popups.form', ['popup' => $popup]);
    }

    /**
     * Perbarui popup.
     */
    public function update(Request $request, Popup $popup): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['image'] = Uploads::file($request, 'image', $popup->image);

        $popup->update($validated);

        if ($validated['is_active'] ?? false) {
            Popup::query()->whereKeyNot($popup->getKey())->update(['is_active' => false]);
        }

        return redirect()->route('admin.popups.index')->with('success', 'Popup PPDB berhasil diperbarui.');
    }

    /**
     * Hapus popup.
     */
    public function destroy(Popup $popup): RedirectResponse
    {
        Uploads::delete($popup->image);

        $popup->delete();

        return back()->with('success', 'Popup PPDB berhasil dihapus.');
    }

    /**
     * Validasi data popup.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'link' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);
    }
}
