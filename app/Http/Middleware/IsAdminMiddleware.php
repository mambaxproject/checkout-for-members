<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! user()->is_admin) {
            return to_route('dashboard.home.index')->with('error', 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}
