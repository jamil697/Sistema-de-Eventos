<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminByEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // si no está autenticado -> 403
        if (! auth()->check()) {
            abort(403, 'Acción no autorizada.');
        }

        // compara email del usuario con el ADMIN_EMAIL del .env
        $adminEmail = env('ADMIN_EMAIL');

        if (! $adminEmail || auth()->user()->email !== $adminEmail) {
            abort(403, 'Acción no autorizada.');
        }

        return $next($request);
    }
}