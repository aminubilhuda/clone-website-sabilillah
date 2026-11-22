<?php

namespace App\Models;

use Database\Factories\VisitorStatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['date', 'views'])]
class VisitorStat extends Model
{
    /** @use HasFactory<VisitorStatFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'views' => 'integer',
        ];
    }
}
