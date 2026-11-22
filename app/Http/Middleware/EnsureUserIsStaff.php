<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStaff
{
    /**
     * Ensures the user is authenticated and has a staff role (admin or editor).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['intended' => $request->path()])
                ->with('error', 'Silakan masuk terlebih dahulu untuk mengakses panel admin.');
        }

        if (! in_array($request->user()->role, ['admin', 'editor'], true)) {
            abort(403, 'Anda tidak memiliki hak akses ke panel admin.');
        }

        return $next($request);
    }
}
