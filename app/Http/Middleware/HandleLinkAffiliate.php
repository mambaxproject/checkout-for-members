<?php

namespace App\Http\Middleware;

use App\Models\Affiliate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLinkAffiliate
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($affiliate = $request->query('affiliate')) {
                session(['affiliate' => optional(Affiliate::find(decrypt($affiliate)))]);
            }
        } catch (\Exception $e) {
            session()->forget('affiliate');
            return to_route('register');
        }

        return $next($request);
    }
}
