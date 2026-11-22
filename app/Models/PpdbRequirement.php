<?php

namespace App\Models;

use Database\Factories\PpdbRequirementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['type', 'content', 'sort_order'])]
class PpdbRequirement extends Model
{
    /** @use HasFactory<PpdbRequirementFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type)->orderBy('sort_order')->orderBy('id');
    }
}
