<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Uploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    /**
     * Mengembalikan daftar gambar yang pernah diunggah ke pustaka media (JSON untuk modal picker).
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'items' => Uploads::listImages(),
        ]);
    }

    /**
     * Halaman manajemen seluruh media gambar pada proyek (uploads/ dan public/img/).
     */
    public function manage(Request $request): View
    {
        $filter = $request->string('sumber')->toString();
        $search = trim($request->string('cari')->toString());

        $items = collect(Uploads::listImages(includeBundled: true, withRefs: true))
            ->when(in_array($filter, ['uploads', 'img'], true), fn ($c) => $c->where('source', $filter))
            ->when($search !== '', fn ($c) => $c->filter(
                fn (array $item): bool => str_contains(strtolower($item['name']), strtolower($search))
                    || str_contains(strtolower($item['path']), strtolower($search))
            ))
            ->values();

        return view('admin.media.index', [
            'items' => $items,
            'filter' => $filter,
            'search' => $search,
        ]);
    }

    /**
     * Mengunggah gambar baru langsung ke pustaka media.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimetypes:image/png,image/jpeg,image/webp,image/gif', 'max:10240'],
        ], [
            'file.required' => 'Pilih gambar terlebih dahulu.',
            'file.image' => 'Berkas harus berupa gambar (PNG, JPG, WebP, atau GIF).',
            'file.max' => 'Ukuran gambar maksimal 10 MB.',
        ]);

        Uploads::file($request, 'file');

        return redirect()->route('admin.media.manage')->with('success', 'Gambar berhasil diunggah ke pustaka media.');
    }

    /**
     * Menghapus gambar dari folder uploads/.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'path' => ['required', 'string', 'max:500'],
        ]);

        $path = $validated['path'];

        if (! Uploads::isValidExistingImage($path)) {
            return back()->with('error', 'Berkas media tidak ditemukan atau tidak dapat dihapus.');
        }

        if (Uploads::referenceCount($path) > 0 && ! $request->boolean('force')) {
            return back()->with('error', 'Gambar ini masih digunakan oleh konten/pengaturan. Hapus referensi terlebih dahulu atau gunakan hapus paksa.');
        }

        Uploads::forceDeleteUpload($path);

        return redirect()->route('admin.media.manage')->with('success', 'Media berhasil dihapus dari penyimpanan.');
    }
}
