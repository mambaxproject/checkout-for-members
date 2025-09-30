<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\SituationAffiliateEnum;
use App\Http\Controllers\Controller;
use App\Models\{Affiliate, CategoryProduct, PixelService, Product};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class AffiliateController extends Controller
{
    public function index(): View
    {
        $affiliates = QueryBuilder::for(auth()->user()->shop()->affiliates())
            ->withWhereHas('user', fn ($query) => $query->select('id', 'name', 'email'))
            ->with([
                'product:id,parent_id,name',
                'product.offers:id,parent_id,name,price',
            ])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::partial('user', 'user.name'),
                AllowedFilter::partial('product', 'product.name'),
                AllowedFilter::exact('situation'),
            ])
            ->get()
            ->each(function ($affiliate) {
                if ($affiliate->product) {
                    $affiliate->product->max_price_offers = $affiliate->product->maxPriceOffers;
                }
            });

        return view('dashboard.affiliates.index', compact('affiliates'));
    }

    public function update(Affiliate $affiliate, Request $request): RedirectResponse
    {
        $affiliate->update($request->validate([
            'type'  => 'required|in:VALUE,PERCENTAGE',
            'value' => 'required',
        ]));

        return back()->with('success', 'Dados afiliação atualizados com sucesso.');
    }

    public function destroy(Affiliate $affiliate): RedirectResponse
    {
        auth()->user()->shop()
            ->affiliates()
            ->where('affiliates.id', $affiliate->id)
            ->delete();

        return back()->with('success', 'Afiliado excluído com sucesso.');
    }

    public function approve(Affiliate $affiliate): RedirectResponse
    {
        auth()->user()->shop()->affiliates()
            ->where('affiliates.id', $affiliate->id)
            ->update(['affiliates.situation' => SituationAffiliateEnum::ACTIVE]);

        $affiliate->refresh();

        if ($affiliate->isActive) {
            event(new \App\Events\AcceptedAffiliate($affiliate));
        }

        return back()->with('success', 'Afiliado aprovada com sucesso.');

    }

    public function cancel(Affiliate $affiliate): RedirectResponse
    {
        auth()->user()->shop()->affiliates()
            ->where('affiliates.id', $affiliate->id)
            ->update(['affiliates.situation' => SituationAffiliateEnum::CANCELED]);

        return back()->with('success', 'Afiliação cancelada com sucesso.');
    }

    public function reactive(Affiliate $affiliate): RedirectResponse
    {
        auth()->user()->shop()->affiliates()
            ->where('affiliates.id', $affiliate->id)
            ->update(['affiliates.situation' => SituationAffiliateEnum::ACTIVE]);

        return back()->with('success', 'Afiliação reativada com sucesso.');
    }

    public function productsAffiliate(): View
    {
        $productsAffiliate = QueryBuilder::for(user()->affiliates()
            ->withWhereHas('product.category:id,name'))
            ->with([
                'product.shop',
                'product.pixels' => function ($query) {
                    $query->with('pixelService')->where('user_id', Auth::id());
                },
            ])
            ->allowedFilters([
                AllowedFilter::partial('name', 'product.name'),
                AllowedFilter::callback('category_id', fn ($query, $value) => $query->whereHas('products', fn ($q) => $q->where('category_id', $value))),
            ])
            ->latest('id')
            ->paginate()
            ->withQueryString();

        $categories = CategoryProduct::active()->toBase()->get(['id', 'name']);

        $pixelServices = PixelService::toBase()->get(['id', 'name', 'image_url']);

        return view('dashboard.affiliates.productAffiliate', compact(
            'productsAffiliate',
            'categories',
            'pixelServices',
        ));
    }

    public function updateProductPixelAffiliate(Product $product, Request $request): RedirectResponse
    {
        $user_id = Auth::id();
        $existingPixelsIds  = $product->pixels()->where('user_id',$user_id)->pluck('pixels.id')->toArray();
        $submittedPixelsIds = array_column($request->input('product.pixels', []), 'id');
        $pixelsToRemove     = array_diff($existingPixelsIds, array_filter($submittedPixelsIds));

        $product->pixels()
            ->where('user_id',$user_id)
            ->whereIntegerInRaw('pixels.id', $pixelsToRemove)
            ->delete();

        if ($request->filled('product.pixels')) {
            foreach ($request->input('product.pixels') as $pixelData) {
                $pixelData['user_id'] = $user_id;
                $pixel = $product->pixels()->updateOrCreate(['pixels.id' => $pixelData['id'] ?? null, 'user_id' => $user_id], $pixelData);

                if (! empty($pixelData['attributes'])) {
                    $pixel->attributes->set($pixelData['attributes'] ?? []);
                    $pixel->save();
                }
            }
        }

        return back()->with('success', 'Pixel atualizados com sucesso.');
    }

    public function linksProductToAffiliate(Affiliate $affiliate, Product $product): View|RedirectResponse
    {
        if (! $affiliate->isActive) {
            return to_route('dashboard.affiliates.productsAffiliate')
                ->with('info', 'Sua afiliação não está ativa e você não pode acessar os links.');
        }

        $activeOffers = $product->activeOffers($product->paymentType ?? '')->get();

        return view('dashboard.affiliates.linksProductAffiliate', compact('affiliate', 'product', 'activeOffers'));
    }

}
