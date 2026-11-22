<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['category_id', 'type', 'jurusan', 'title', 'slug', 'excerpt', 'content', 'cover_image', 'document_path', 'starts_at', 'ends_at', 'location', 'user_id', 'status', 'published_at', 'is_featured'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes;

    public const TYPE_BERITA = 'berita';

    public const TYPE_PENGUMUMAN = 'pengumuman';

    public const TYPE_AGENDA = 'agenda';

    public const TYPE_TULISAN = 'tulisan';

    public const TYPE_MEDIA = 'media';

    public const TYPE_MAJALAH = 'majalah';

    public const TYPE_TOKOH = 'tokoh';

    public function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'views' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function detailUrl(): string
    {
        return match ($this->type) {
            self::TYPE_PENGUMUMAN => route('pengumuman.show', $this->slug),
            self::TYPE_AGENDA => route('agenda.show', $this->slug),
            self::TYPE_TULISAN => route('tulisan.show', $this->slug),
            self::TYPE_MEDIA => route('media.show', $this->slug),
            self::TYPE_MAJALAH => route('majalah.detail', $this->slug),
            default => route('berita.show', $this->slug),
        };
    }
}
