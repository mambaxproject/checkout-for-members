<?php

namespace App\Http\Middleware;

use App\Models\Coproducer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLinkOfferCoproducer
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($coproducerId = $request->query('coprd')) {

                $coproducerIsValidAndActive = Coproducer::where('id', $coproducerId)
                    ->active()
                    ->exists();

                if (! $coproducerIsValidAndActive) {
                    session()->forget('coprd');

                    return redirect()->to($request->url());
                }

                session(['coproducer_id' => $request->query('coprd')]);
            }
        } catch (\Exception $e) {
            session()->forget('coprd');
        }

        return $next($request);
    }
}
