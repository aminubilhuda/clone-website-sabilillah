<?php

namespace App\Models;

use Database\Factories\PpdbFeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['label', 'category', 'description', 'nominal', 'sort_order', 'is_active'])]
class PpdbFee extends Model
{
    /** @use HasFactory<PpdbFeeFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'nominal' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
