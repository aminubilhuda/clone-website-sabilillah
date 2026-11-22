<?php

namespace App\Support;

/**
 * Registry halaman untuk meta judul/deskripsi SEO per halaman.
 */
class Seo
{
    /** @var array{title: string, description: string, image: string|null, url: string|null} */
    public static array $meta = [
        'title' => '',
        'description' => '',
        'image' => null,
        'url' => null,
    ];

    public static function set(
        string $title,
        string $description = '',
        ?string $image = null,
        ?string $url = null,
    ): void {
        self::$meta = [
            'title' => $title,
            'description' => $description && $description !== '' ? $description : str($title)->limit(160),
            'image' => $image,
            'url' => $url,
        ];
    }
}
