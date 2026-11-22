<?php

namespace App\Models;

use Database\Factories\PpdbWaveFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['label', 'mulai', 'selesai', 'biaya', 'benefit', 'sort_order', 'is_active'])]
class PpdbWave extends Model
{
    /** @use HasFactory<PpdbWaveFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'mulai' => 'date',
            'selesai' => 'date',
            'biaya' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Status otomatis berdasarkan rentang tanggal.
     */
    public function status(): string
    {
        $today = now()->startOfDay();

        if ($today->lt($this->mulai)) {
            return 'akan-dibuka';
        }

        if ($today->gt($this->selesai)) {
            return 'tutup';
        }

        return 'aktif';
    }

    public function statusLabel(): string
    {
        return match ($this->status()) {
            'aktif' => 'Aktif',
            'tutup' => 'Tutup',
            default => 'Akan Dibuka',
        };
    }

    public function statusClass(): string
    {
        return match ($this->status()) {
            'aktif' => 'bg-success',
            'tutup' => 'bg-secondary',
            default => 'bg-info text-dark',
        };
    }

    public function formattedPeriod(): string
    {
        return $this->mulai->format('d M Y').' — '.$this->selesai->format('d M Y');
    }
}
