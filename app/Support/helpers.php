<?php

use App\Models\Setting;
use App\Support\Media;
use App\Support\Seo;

/**
 * Reads a cached site setting value.
 */
function setting(string $key, mixed $default = null): mixed
{
    return Setting::value($key, $default);
}

/**
 * Resolves a media path to its public URL.
 */
function media_url(?string $path, string $fallback = 'img/berita-1.svg'): string
{
    return Media::url($path, $fallback);
}

/**
 * Declares per-page SEO meta values, read by the layout composer.
 */
function page_meta(string $title, string $description = '', ?string $image = null, ?string $url = null): void
{
    Seo::set($title, $description, $image, $url);
}

/**
 * Returns a theme color setting, falling back to the default when the
 * stored value is not a valid six-digit hex color.
 */
function theme_color(string $key, string $default): string
{
    $value = setting($key, $default);

    return is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value) === 1
        ? $value
        : $default;
}

/**
 * Picks black or white text for readable contrast on a hex background.
 */
function contrast_text(string $hex): string
{
    $hex = ltrim($hex, '#');
    $r = (int) hexdec(substr($hex, 0, 2));
    $g = (int) hexdec(substr($hex, 2, 2));
    $b = (int) hexdec(substr($hex, 4, 2));

    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

    return $luminance > 0.62 ? '#10231f' : '#ffffff';
}

/**
 * Converts a hex color to the "r, g, b" form used by Bootstrap CSS variables.
 */
function hex_rgb(string $hex): string
{
    $hex = ltrim($hex, '#');

    return hexdec(substr($hex, 0, 2)).', '.hexdec(substr($hex, 2, 2)).', '.hexdec(substr($hex, 4, 2));
}
