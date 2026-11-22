<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    /**
     * Returns the public URL for stored media; falls back to a bundled image.
     *
     * Uploaded assets (documented via the admin panel, prefixed `uploads/`)
     * resolve through the public storage disk; seeded paths (`img/...`)
     * resolve as plain public assets.
     */
    public static function url(?string $path, string $fallback = 'img/berita-1.svg'): string
    {
        if (blank($path)) {
            return asset($fallback);
        }

        if (str_starts_with($path, 'uploads/')) {
            return Storage::disk('public')->url($path);
        }

        return asset($path);
    }
}
