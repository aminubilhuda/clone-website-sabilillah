<?php

namespace App\Support;

use App\Models\Album;
use App\Models\AlbumItem;
use App\Models\HeroSlider;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Popup;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Uploads
{
    private const IMAGE_EXTENSIONS = ['png', 'jpg', 'jpeg', 'webp', 'gif'];

    /**
     * Stores an uploaded file on the public disk, or reuses an existing
     * uploaded image path from `{field}_existing`. Returns the stored path.
     */
    public static function file(Request $request, string $field, ?string $existing = null): ?string
    {
        if ($request->hasFile($field) && $request->file($field)->isValid()) {
            $stored = $request->file($field)->store('uploads/'.now()->format('Ym'), 'public');

            if ($existing !== $stored) {
                self::delete($existing);
            }

            return $stored;
        }

        $selected = $request->input($field.'_existing');

        if (is_string($selected) && self::isValidExistingImage($selected)) {
            if ($existing !== $selected) {
                self::delete($existing);
            }

            return $selected;
        }

        return $existing;
    }

    /**
     * Menghapus berkas unggahan dari disk publik jika tidak lagi dipakai entitas lain.
     */
    public static function delete(?string $path): void
    {
        if (! is_string($path) || ! str_starts_with($path, 'uploads/')) {
            return;
        }

        if (self::referenceCount($path) > 1) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    /**
     * Daftar gambar yang pernah diunggah pada disk publik (uploads/).
     *
     * @return list<array{path: string, url: string, name: string, size: int}>
     */
    /**
     * Daftar gambar yang pernah diunggah pada disk publik (uploads/),
     * dan opsional gambar bawaan di public/img/.
     *
     * @return list<array{path: string, url: string, name: string, size: int, source: string, refs?: int}>
     */
    public static function listImages(bool $includeBundled = false, bool $withRefs = false): array
    {
        $disk = Storage::disk('public');
        $items = [];

        foreach ($disk->allFiles('uploads') as $path) {
            if (! in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), self::IMAGE_EXTENSIONS, true)) {
                continue;
            }

            $items[$path] = [
                'path' => $path,
                'url' => '/storage/'.$path,
                'name' => basename($path),
                'size' => $disk->size($path),
                'source' => 'uploads',
            ];
        }

        $publicUploads = public_path('storage/uploads');

        if (is_dir($publicUploads)) {
            foreach (File::allFiles($publicUploads) as $file) {
                $ext = strtolower($file->getExtension());

                if (! in_array($ext, self::IMAGE_EXTENSIONS, true)) {
                    continue;
                }

                $relative = 'uploads/'.ltrim(str_replace('\\', '/', $file->getRelativePathname()), '/');

                $items[$relative] ??= [
                    'path' => $relative,
                    'url' => '/storage/'.$relative,
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'source' => 'uploads',
                ];
            }
        }

        krsort($items);

        if ($includeBundled) {
            $bundled = [];
            $imgDir = public_path('img');

            if (is_dir($imgDir)) {
                $bundledExts = [...self::IMAGE_EXTENSIONS, 'svg'];

                foreach (File::allFiles($imgDir) as $file) {
                    $ext = strtolower($file->getExtension());

                    if (! in_array($ext, $bundledExts, true)) {
                        continue;
                    }

                    $relative = 'img/'.ltrim(str_replace('\\', '/', $file->getRelativePathname()), '/');

                    $bundled[$relative] = [
                        'path' => $relative,
                        'url' => '/'.$relative,
                        'name' => $file->getFilename(),
                        'size' => $file->getSize(),
                        'source' => 'img',
                    ];
                }
            }

            ksort($bundled);
            $items = array_merge($items, $bundled);
        }

        $list = array_values($items);

        if ($withRefs) {
            foreach ($list as &$item) {
                $item['refs'] = self::referenceCount($item['path']);
            }
        }

        return $list;
    }

    /**
     * Menghapus file upload berdasarkan path relatifnya (hanya folder uploads/).
     */
    public static function forceDeleteUpload(string $path): bool
    {
        if (! self::isValidExistingImage($path)) {
            return false;
        }

        $deleted = false;

        if (Storage::disk('public')->exists($path)) {
            $deleted = Storage::disk('public')->delete($path) || $deleted;
        }

        $publicFile = public_path('storage/'.$path);

        if (is_file($publicFile)) {
            $deleted = @unlink($publicFile) || $deleted;
        }

        return $deleted;
    }

    /**
     * Memastikan path media yang dipilih ulang aman dan ada di disk publik.
     */
    public static function isValidExistingImage(string $path): bool
    {
        if ($path === '' || str_contains($path, '..') || str_contains($path, "\0")) {
            return false;
        }

        if (! str_starts_with($path, 'uploads/')) {
            return false;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($ext, self::IMAGE_EXTENSIONS, true)) {
            return false;
        }

        return Storage::disk('public')->exists($path) || is_file(public_path('storage/'.$path));
    }

    /**
     * Menghitung jumlah record di database yang masih merujuk path ini.
     */
    public static function referenceCount(string $path): int
    {
        $count = 0;
        $count += Post::withTrashed()->where('cover_image', $path)->orWhere('document_path', $path)->count();
        $count += HeroSlider::query()->where('image', $path)->count();
        $count += Popup::query()->where('image', $path)->count();
        $count += Partner::query()->where('logo_path', $path)->count();
        $count += Album::query()->where('cover_image', $path)->count();
        $count += AlbumItem::query()->where('media_path', $path)->count();
        $count += Page::query()->where('blocks->gambar', $path)->count();
        $count += Setting::query()->where('value->v', $path)->count();

        return $count;
    }
}
