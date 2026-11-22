<?php

namespace App\Http\Middleware;

use App\Models\VisitorStat;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Records one visit per unique visitor per day for public pages only.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET')
            || $request->expectsJson()
            || $request->is('admin') || $request->is('admin/*')) {
            return $response;
        }

        $date = CarbonImmutable::today();
        $key = 'visitor:'.sha1($request->ip().'|'.$date->toDateString());

        if (Cache::has($key)) {
            return $response;
        }

        Cache::put($key, true, $date->endOfDay()->diffInSeconds(now()) ?: 60);

        $statistik = VisitorStat::query()->firstOrCreate([
            'date' => $date->toDateString(),
        ]);

        $statistik->increment('views');

        return $response;
    }
}
