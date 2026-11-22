<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['group', 'key', 'value'])]
class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * Reads a setting value by key and caches the whole settings table.
     * A settings row whose JSON "value" holds a single scalar stores it as ["v" => scalar], keep helper simple.
     */
    public static function value(string $key, mixed $default = null): mixed
    {
        $all = Cache::rememberForever('settings.all', fn (): array => static::query()
            ->pluck('value', 'key')
            ->all());

        $value = $all[$key] ?? null;

        if (is_array($value) && array_keys($value) === ['v']) {
            return $value['v'];
        }

        return $value ?? $default;
    }

    public static function flushCache(): void
    {
        Cache::forget('settings.all');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }
}
