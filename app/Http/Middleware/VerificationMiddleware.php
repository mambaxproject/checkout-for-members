<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Gate;

class VerificationMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (! auth()->user()->verified) {
                toastr()->info(trans('global.verifyYourEmail'));

                auth()->logout();

                return to_route('login');
            }
        }

        return $next($request);
    }
}
