<?php

namespace App\Models;

use Database\Factories\AlbumFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'kind', 'description', 'cover_image'])]
class Album extends Model
{
    /** @use HasFactory<AlbumFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'kind' => 'string',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(AlbumItem::class)->orderBy('sort_order')->orderBy('id');
    }
}
