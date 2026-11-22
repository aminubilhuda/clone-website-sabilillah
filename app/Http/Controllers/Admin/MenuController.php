<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Daftar menu keseluruhan posisi.
     */
    public function index(): View
    {
        return view('admin.menus.index', [
            'menus' => Menu::query()
                ->with('parent')
                ->orderBy('position')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'posisiOptions' => ['header' => 'Menu Utama (header)', 'footer' => 'Footer'],
            'parents' => Menu::query()->whereNull('parent_id')->orderBy('sort_order')->orderBy('id')->get(),
            'halaman' => Page::query()->published()->orderBy('group')->orderBy('title')->get(),
        ]);
    }

    /**
     * Form menu baru.
     */
    public function create(): View
    {
        return view('admin.menus.form', [
            'menu' => new Menu(['position' => 'header']),
            'posisiOptions' => ['header' => 'Menu Utama (header)', 'footer' => 'Footer'],
            'parents' => Menu::query()->whereNull('parent_id')->orderBy('sort_order')->orderBy('id')->get(),
            'halaman' => Page::query()->published()->orderBy('group')->orderBy('title')->get(),
        ]);
    }

    /**
     * Simpan menu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        if (($validated['parent_id'] ?? null) === '') {
            $validated['parent_id'] = null;
        }

        Menu::query()->create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Form ubah menu.
     */
    public function edit(Menu $menu): View
    {
        return view('admin.menus.form', [
            'menu' => $menu,
            'posisiOptions' => ['header' => 'Menu Utama (header)', 'footer' => 'Footer'],
            'parents' => Menu::query()->whereNull('parent_id')->whereKeyNot($menu->getKey())->orderBy('sort_order')->orderBy('id')->get(),
            'halaman' => Page::query()->published()->orderBy('group')->orderBy('title')->get(),
        ]);
    }

    /**
     * Perbarui menu.
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $this->validateData($request);

        if (($validated['parent_id'] ?? null) === '') {
            $validated['parent_id'] = null;
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Hapus menu (anak ikut lepas via nullOnDelete).
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Validasi data menu.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'parent_id' => ['nullable'],
            'label' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'in:header,footer'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
