<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            abort(401, 'Unauthorized.');
        }

        // Admin selalu memiliki akses penuh ke semua halaman
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($role === 'staff' && $request->user()->isStaff()) {
                return $next($request);
            }
            if ($role === 'manager' && $request->user()->isManager()) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki hak akses untuk mengakses halaman ini.');
    }
}
