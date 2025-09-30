<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\{Request,Response};

class AccessDashboardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user         = auth()->user();
        $isShop       = ! is_null($user->shop());
        $isAffiliate  = $user->affiliates()->exists();
        $isCoProducer = $user->coProducers()->exists();
        $isAdmin      = $user->hasRole('Admin');

        abort_unless(
            $isShop || $isAffiliate || $isCoProducer || $isAdmin,
            Response::HTTP_FORBIDDEN,
            'Você não tem permissão para acessar esta área.'
        );

        return $next($request);
    }
}
