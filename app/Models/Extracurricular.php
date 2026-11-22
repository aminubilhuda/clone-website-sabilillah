<?php

namespace App\Models;

use Database\Factories\ExtracurricularFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'icon', 'sort_order', 'is_active'])]
class Extracurricular extends Model
{
    /** @use HasFactory<ExtracurricularFactory> */
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
        return $query->where('is_active', true);
    }

    /**
     * Kelas ikon Font Awesome yang aman dipakai di view.
     */
    public function iconClass(): string
    {
        $icon = trim((string) $this->icon);

        if ($icon === '') {
            return 'fa-star';
        }

        return str_starts_with($icon, 'fa-') ? $icon : 'fa-'.$icon;
    }
}
