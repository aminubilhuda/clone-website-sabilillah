<?php

namespace App\Models;

use Database\Factories\AlbumItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['album_id', 'media_path', 'media_url', 'caption', 'sort_order'])]
class AlbumItem extends Model
{
    /** @use HasFactory<AlbumItemFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function isVideo(): bool
    {
        return filled($this->media_url);
    }
}
