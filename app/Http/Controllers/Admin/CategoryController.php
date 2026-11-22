<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Dukungan tipe kategori.
     */
    public static array $types = ['berita', 'pengumuman', 'agenda', 'tulisan', 'media', 'majalah', 'tokoh'];

    /**
     * Daftar kategori.
     */
    public function index(): View
    {
        return view('admin.categories.index', [
            'kategori' => Category::query()->orderBy('type')->orderBy('name')->get(),
            'types' => self::$types,
        ]);
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:'.implode(',', self::$types)],
        ]);

        $validated['slug'] = str($validated['name'])->slug()->toString();

        Category::query()->create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Form ubah kategori.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.form', [
            'category' => $category,
            'types' => self::$types,
        ]);
    }

    /**
     * Perbarui kategori.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:'.implode(',', self::$types)],
        ]);

        $validated['slug'] = str($validated['name'])->slug()->toString();

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
