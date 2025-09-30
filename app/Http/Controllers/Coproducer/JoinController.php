<?php

namespace App\Http\Controllers\Coproducer;

use App\Enums\SituationCoproducerEnum;
use App\Models\Coproducer;
use Illuminate\Http\{RedirectResponse, Request, Response};
use Illuminate\View\View;

class JoinController
{
    public function join(Coproducer $coproducer): View
    {
        abort_if($coproducer->isCanceled, Response::HTTP_FORBIDDEN, 'Este convite não está disponível');

        $product      = $coproducer->product;
        $activeOffers = $product->activeOffers($product->paymentType)->get(['id', 'name', 'price']);

        return view('coproducer.join', compact('coproducer', 'product', 'activeOffers'));
    }

    public function register(Coproducer $coproducer, Request $request): RedirectResponse
    {
        abort_if($coproducer->email != auth()->user()->email, Response::HTTP_FORBIDDEN, 'Este convite não está disponível');

        $product = $coproducer->product;

        if ($product->coproducers()->active()->count()) {
            return back()->with('error', 'Já existe um coprodutor ativo neste produto.');
        }

        if ($product->coproducers()->where('email', auth()->user()->email)->exists() && $request->routeIs('dashboard.coproducers.store')) {
            return back()->with('error', 'Este e-mail já está cadastrado como coprodutor deste produto.');
        }

        if ($product->shop->owner->email === $coproducer->email) {
            return back()->with('error', 'Você não pode ser coprodutor do seu próprio produto.');
        }

        $product->coproducers()->find($coproducer->id)->update([
            'situation' => SituationCoproducerEnum::ACTIVE,
            'user_id'   => auth()->id(),
        ]);

        if ($product->affiliates()->where('email', auth()->user()->email)->exists()) {
            $product->affiliates()->where('email', auth()->user()->email)->delete();
        }

        event(new \App\Events\AcceptedCoproducer($coproducer));

        return to_route('dashboard.home.index')->with('success', 'Convite aceito com sucesso');
    }

}
