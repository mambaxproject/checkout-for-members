<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Coproducer\{StoreCoproducerRequest, UpdateCoproducerRequest};
use App\Mail\Coproducers\NewCoproducer;
use App\Models\{CategoryProduct, Coproducer, Product};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class CoproducerController extends Controller
{
    public function store(Product $product, StoreCoproducerRequest $request): RedirectResponse
    {
        $coproducer = $product->coproducers()->create($request->all());

        Mail::to($request->email)
            ->queue(new NewCoproducer(
                coproducer: $coproducer,
                product: $product,
            ));

        return back()
            ->withFragment('tab=tab-participations')
            ->with('success', 'Coprodutor adicionado com sucesso. Foi enviado um e-mail para ele confirmar a parceria.');
    }

    public function update(Coproducer $coproducer, UpdateCoproducerRequest $request): RedirectResponse
    {
        $coproducer->update($request->all());

        return back()
            ->withFragment('tab=tab-participations')
            ->with('success', 'Coprodutor atualizado com sucesso');
    }

    public function destroy(Coproducer $coproducer): RedirectResponse
    {
        $coproducer->delete();

        return back()
            ->withFragment('tab=tab-participations')
            ->with('success', 'Coprodutor deletado com sucesso');
    }

    public function productsCoproducer(): View
    {
        $query = Coproducer::where('email', user()->email)
            ->with('products.category:id,name');

        $productsCoproducer = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::partial('name', 'product.name'),
                AllowedFilter::callback('category_id', fn ($query, $value) => $query->whereHas('product', fn ($q) => $q->where('category_id', $value))),
            ])
            ->paginate()
            ->withQueryString();

        $categories = CategoryProduct::active()->toBase()->get(['id', 'name']);

        return view('dashboard.coproducers.productCoproducer', compact('productsCoproducer', 'categories'));
    }

    public function linksProductToCoproducer(Coproducer $coproducer, Product $product): View|RedirectResponse
    {
        if (! $coproducer->isActive) {
            return to_route('dashboard.coproducers.productsCoproducer')
                ->with('info', 'Sua co-produção não está ativa e você não pode acessar os links.');
        }

        $activeOffers = $product->activeOffers($product->paymentType ?? '')->get();

        return view('dashboard.coproducers.linksProductCoproducer', compact('coproducer', 'product', 'activeOffers'));
    }

    public function updateSituation(Coproducer $coproducer, Request $request): RedirectResponse
    {
        $data = ['situation' => $request->input('situation')];

        if (is_null($coproducer->user_id)) {
            $data['user_id'] = auth()->id();
        }

        $coproducer->update($data);

        if ($coproducer->isActive) {
            $product = $coproducer->product;

            if ($product->affiliates()->where('email', auth()->user()->email)->exists()) {
                $product->affiliates()->where('email', auth()->user()->email)->delete();
            }
        }

        return back()->with('success', 'Situação atualizada com sucesso.');
    }

}
