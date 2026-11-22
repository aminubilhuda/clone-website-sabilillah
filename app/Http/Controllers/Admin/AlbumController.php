<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\AlbumItem;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlbumController extends Controller
{
    /**
     * Daftar album galeri.
     */
    public function index(): View
    {
        return view('admin.albums.index', [
            'albums' => Album::query()->withCount('items')->orderByDesc('created_at')->get(),
        ]);
    }

    /**
     * Form album baru.
     */
    public function create(): View
    {
        return view('admin.albums.form', [
            'album' => new Album(['kind' => 'foto']),
        ]);
    }

    /**
     * Simpan album.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['slug'] = $validated['slug'] ?? str($validated['title'])->slug()->toString();

        $album = Album::query()->create($validated);

        $album->update(['cover_image' => Uploads::file($request, 'cover', $album->cover_image)]);

        return redirect()->route('admin.albums.index')->with('success', 'Album berhasil ditambahkan.');
    }

    /**
     * Detail album + kelola item.
     */
    public function edit(Album $album): View
    {
        return view('admin.albums.form', [
            'album' => $album->load('items'),
        ]);
    }

    /**
     * Perbarui album.
     */
    public function update(Request $request, Album $album): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['slug'] = $validated['slug'] ?? $album->slug ?? str($validated['title'])->slug()->toString();

        $validated['cover_image'] = Uploads::file($request, 'cover', $album->cover_image);

        $album->update($validated);

        return redirect()->route('admin.albums.index')->with('success', 'Album berhasil diperbarui.');
    }

    /**
     * Hapus album (ikut isi).
     */
    public function destroy(Album $album): RedirectResponse
    {
        Uploads::delete($album->cover_image);

        $album->items()->get()->each(fn (AlbumItem $item) => Uploads::delete($item->media_path));

        $album->delete();

        return back()->with('success', 'Album berhasil dihapus.');
    }

    /**
     * Tambah item album.
     */
    public function storeItem(Request $request, Album $album): RedirectResponse
    {
        $request->validate([
            'media_file' => ['nullable', 'image', 'max:10240'],
            'media_url' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $mediaPath = Uploads::file($request, 'media_file', null);

        if (blank($mediaPath) && blank($request->string('media_url')->toString())) {
            return back()->withErrors(['media_file' => 'Unggah gambar atau isi tautan media terlebih dahulu.']);
        }

        $album->items()->create([
            'media_path' => $mediaPath,
            'media_url' => $request->string('media_url')->toString(),
            'caption' => $request->string('caption')->toString(),
            'sort_order' => ($album->items()->max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'Item album berhasil ditambahkan.');
    }

    /**
     * Perbarui satu item album.
     */
    public function updateItem(Request $request, AlbumItem $item): RedirectResponse
    {
        $request->validate([
            'caption' => ['required', 'string', 'max:255'],
        ]);

        $item->update(['caption' => $request->string('caption')->toString()]);

        return back()->with('success', 'Keterangan item diperbarui.');
    }

    /**
     * Hapus satu item album.
     */
    public function destroyItem(AlbumItem $item): RedirectResponse
    {
        Uploads::delete($item->media_path);

        $item->delete();

        return back()->with('success', 'Item album berhasil dihapus.');
    }

    /**
     * Validasi data album.
     */
    private function validateData(Request $request): array
    {
        $album = $request->route('album');
        $abaikanSlug = $album instanceof Album ? ','.$album->id : '';

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/', 'unique:albums,slug'.$abaikanSlug],
            'kind' => ['required', 'string', 'in:foto,video'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cover' => ['nullable', 'image', 'max:10240'],
        ]);
    }
}
