<?php

namespace App\Http\Middleware;

use App\Models\Affiliate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLinkOfferAffiliate
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($codeAffiliate = $request->query('afflt')) {
                $affiliate = Affiliate::where('code', $codeAffiliate)->active()->first(['id', 'product_id']);
                $product   = $request->product;

                if (! $affiliate->id or $product->parent_id != $affiliate->product_id) {
                    session()->forget('affiliate_id');

                    return redirect()->to($request->url());
                }

                session(['affiliate_id' => $affiliate->id]);
            } else {
                session()->forget('affiliate_id');
            }

        } catch (\Exception $e) {
            session()->forget('affiliate_id');
        }

        return $next($request);
    }
}
