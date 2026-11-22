<?php

namespace App\Models;

use Database\Factories\HeroSliderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['image', 'title', 'link', 'sort_order', 'is_active'])]
class HeroSlider extends Model
{
    /** @use HasFactory<HeroSliderFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
