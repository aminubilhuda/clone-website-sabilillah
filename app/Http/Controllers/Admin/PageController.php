<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Grup halaman yang dikenal panel.
     */
    public const GROUPS = ['profil', 'jurusan', 'statik'];

    /**
     * Daftar halaman.
     */
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::query()->orderBy('group')->orderBy('sort_order')->orderBy('id')->get(),
            'groups' => self::GROUPS,
        ]);
    }

    /**
     * Form halaman baru.
     */
    public function create(): View
    {
        return view('admin.pages.form', [
            'page' => new Page(['group' => 'profil', 'is_published' => true]),
            'groups' => self::GROUPS,
        ]);
    }

    /**
     * Simpan halaman.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['slug'] = $validated['slug'] ?? str($validated['title'])->slug()->toString();

        Page::query()->create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil ditambahkan.');
    }

    /**
     * Form ubah halaman.
     */
    public function edit(Page $page): View
    {
        return view('admin.pages.form', [
            'page' => $page,
            'groups' => self::GROUPS,
        ]);
    }

    /**
     * Perbarui halaman.
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $this->validateData($request);

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Hapus halaman.
     */
    public function destroy(Page $page): RedirectResponse
    {
        Uploads::delete(is_string($page->block('gambar')) ? $page->block('gambar') : null);

        $page->delete();

        return back()->with('success', 'Halaman berhasil dihapus.');
    }

    /**
     * Validasi data halaman dan susun blok konten dari field terstruktur.
     * JSON mentah tetap tersedia sebagai opsi lanjutan.
     */
    private function validateData(Request $request): array
    {
        $page = $request->route('page');
        $abaikanSlug = $page instanceof Page ? ','.$page->id : '';

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/', 'unique:pages,slug'.$abaikanSlug],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'group' => ['required', 'string', 'in:'.implode(',', self::GROUPS)],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'intro' => ['nullable', 'string'],
            'isi' => ['nullable', 'string'],
            'sambutan' => ['nullable', 'string'],
            'visi' => ['nullable', 'string'],
            'misi' => ['nullable', 'string'],
            'langkah' => ['nullable', 'string'],
            'jalur' => ['nullable', 'string'],
            'tabel' => ['nullable', 'string'],
            'struktur' => ['nullable', 'string'],
            'guru' => ['nullable', 'string'],
            'fasilitas' => ['nullable', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'keunggulan' => ['nullable', 'string'],
            'prospek' => ['nullable', 'string'],
            'gambar' => ['nullable', 'string', 'max:255'],
            'gambar_file' => ['nullable', 'file', 'mimetypes:image/svg+xml,image/png,image/jpeg,image/webp', 'max:5120'],
            'blocks_text' => ['nullable', 'string'],
        ]);

        $blocks = $page instanceof Page ? ($page->blocks ?? []) : [];

        $setText = function (string $key) use ($request, &$blocks): void {
            if (! $request->has($key)) {
                return;
            }

            $value = trim((string) $request->input($key, ''));

            if ($value === '') {
                unset($blocks[$key]);
            } else {
                $blocks[$key] = $value;
            }
        };

        foreach (['intro', 'sambutan', 'visi', 'deskripsi'] as $key) {
            $setText($key);
        }

        $setList = function (string $key) use ($request, &$blocks): void {
            if (! $request->has($key)) {
                return;
            }

            $lines = collect(preg_split('/\R/u', (string) $request->input($key, '')))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all();

            if ($lines === []) {
                unset($blocks[$key]);
            } else {
                $blocks[$key] = $lines;
            }
        };

        foreach (['isi', 'misi', 'langkah', 'fasilitas', 'keunggulan', 'prospek'] as $key) {
            $setList($key);
        }

        $setPairs = function (string $key) use ($request, &$blocks): void {
            if (! $request->has($key)) {
                return;
            }

            $rows = collect(preg_split('/\R/u', (string) $request->input($key, '')))
                ->map(function ($line) {
                    $parts = array_map('trim', explode('|', $line));

                    return count($parts) >= 2 && $parts[0] !== '' ? [$parts[0], $parts[1]] : null;
                })
                ->filter()
                ->values()
                ->all();

            if ($rows === []) {
                unset($blocks[$key]);
            } else {
                $blocks[$key] = $rows;
            }
        };

        foreach (['struktur', 'guru', 'jalur'] as $key) {
            $setPairs($key);
        }

        if ($request->has('tabel')) {
            $rows = collect(preg_split('/\R/u', (string) $request->input('tabel', '')))
                ->map(fn ($line) => array_map('trim', explode('|', $line)))
                ->filter(fn ($row) => implode('', $row) !== '')
                ->values()
                ->all();

            if ($rows === []) {
                unset($blocks['tabel']);
            } else {
                $blocks['tabel'] = $rows;
            }
        }

        $gambarUpload = Uploads::file($request, 'gambar_file', $blocks['gambar'] ?? null);

        if ($gambarUpload) {
            $blocks['gambar'] = $gambarUpload;
        }

        if ($request->has('gambar')) {
            $path = trim((string) $request->input('gambar', ''));

            if ($path !== '') {
                $blocks['gambar'] = $path;
            } elseif (! $gambarUpload) {
                unset($blocks['gambar']);
            }
        }

        $blocksText = $validated['blocks_text'] ?? null;
        unset($validated['blocks_text']);

        if (filled($blocksText)) {
            $decoded = json_decode($blocksText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw ValidationException::withMessages([
                    'blocks_text' => 'Isi blok bukan JSON yang valid: '.json_last_error_msg(),
                ]);
            }

            if (is_array($decoded)) {
                $blocks = array_merge($blocks, $decoded);
            }
        }

        foreach ([
            'intro', 'isi', 'sambutan', 'visi', 'misi', 'langkah', 'jalur',
            'tabel', 'struktur', 'guru', 'fasilitas', 'deskripsi', 'keunggulan',
            'prospek', 'gambar', 'gambar_file',
        ] as $key) {
            unset($validated[$key]);
        }

        $validated['blocks'] = $blocks;
        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
