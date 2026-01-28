<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user()) {
            abort(403, 'Acceso denegado.');
        }

        // Si el rol del usuario NO está dentro de los permitidos
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
