<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Uploads
{
    /**
     * Stores an uploaded file on the public disk, replacing the previous
     * upload when one exists. Returns the relative stored path.
     */
    public static function file(Request $request, string $field, ?string $existing = null): ?string
    {
        if (! $request->hasFile($field) || ! $request->file($field)->isValid()) {
            return $existing;
        }

        if (is_string($existing) && str_starts_with($existing, 'uploads/')) {
            Storage::disk('public')->delete($existing);
        }

        return $request->file($field)->store('uploads/'.now()->format('Ym'), 'public');
    }

    /**
     * Menghapus berkas unggahan dari disk publik (hanya path uploads/).
     */
    public static function delete(?string $path): void
    {
        if (is_string($path) && str_starts_with($path, 'uploads/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
