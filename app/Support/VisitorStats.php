<?php

namespace App\Support;

use App\Models\VisitorStat;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

class VisitorStats
{
    /**
     * Returns the footer "statistik website" summary: today's views,
     * yesterday's views, this month's total, and the all-time total.
     *
     * @return array{hari_ini: int, kemarin: int, bulan_ini: int, total: int}
     */
    public static function summary(): array
    {
        $today = CarbonImmutable::today();
        $kemarin = $today->subDay();
        $monthStart = $today->startOfMonth()->toDateString();

        return [
            'hari_ini' => (int) VisitorStat::query()->where('date', $today->toDateString())->value('views'),
            'kemarin' => (int) VisitorStat::query()->where('date', $kemarin->toDateString())->value('views'),
            'bulan_ini' => (int) VisitorStat::query()->where('date', '>=', $monthStart)->sum('views'),
            'total' => (int) VisitorStat::query()->sum('views'),
        ];
    }

    /**
     * Narrows a visitor_stats query to the current month.
     */
    public static function scopeBulanIni(Builder $query): Builder
    {
        return $query->where('date', '>=', CarbonImmutable::today()->startOfMonth()->toDateString());
    }
}
