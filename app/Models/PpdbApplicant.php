<?php

namespace App\Models;

use Database\Factories\PpdbApplicantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['wave_id', 'name', 'nisn', 'asal_sekolah', 'jurusan', 'jurusan_2', 'parent_name', 'whatsapp', 'alamat', 'status', 'notes'])]
class PpdbApplicant extends Model
{
    /** @use HasFactory<PpdbApplicantFactory> */
    use HasFactory;

    public function wave(): BelongsTo
    {
        return $this->belongsTo(PpdbWave::class, 'wave_id');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            default => 'Baru',
        };
    }

    public function statusClass(): string
    {
        return match ($this->status) {
            'diterima' => 'bg-success',
            'ditolak' => 'bg-danger',
            default => 'bg-info text-dark',
        };
    }
}
