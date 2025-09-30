<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\SituationAffiliateEnum;
use App\Http\Requests\Affiliate\StoreAffiliateRequest;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MarketplaceController
{
    public function index(): View
    {
        $productsMarketplace = QueryBuilder::for(Product::class)
            ->isProduct()
            ->showProductInMarketplace()
            ->isPublished()
            ->with([
                'media',
                'affiliates:id,product_id,user_id',
                'category:id,name',
                'shop:id,name,owner_id',
                'offers:id,parent_id,name,paymentType,price,attributes',
                'coproducers:id,id,user_id,situation',
            ])
            ->allowedFilters([
                'name',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('rangePrice'),
            ])
            ->latest('id')
            ->paginate()
            ->withQueryString();

        $categories = CategoryProduct::active()->toBase()->get(['id', 'name']);

        return view('dashboard.marketplace.index', compact('productsMarketplace', 'categories'));
    }

    public function joinAffiliate(Product $product, StoreAffiliateRequest $request): RedirectResponse
    {
        $manualApprovalAffiliate = boolval($product->getValueSchemalessAttributes('affiliate.approveRequestsManually'));

        $product->affiliates()->updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'email'     => auth()->user()->email,
            'code'      => str()->random(8),
            'type'      => $product->getValueSchemalessAttributes('affiliate.defaultTypeValue'),
            'value'     => $product->getValueSchemalessAttributes('affiliate.defaultValue'),
            'situation' => $manualApprovalAffiliate ? SituationAffiliateEnum::PENDING : SituationAffiliateEnum::ACTIVE,
        ]);

        $typeMessage = $manualApprovalAffiliate ? 'info' : 'success';

        $message = $manualApprovalAffiliate
            ? 'Afiliação realizada com sucesso. Aguarde a aprovação do vendedor.'
            : 'Afiliação realizada com sucesso.';

        return back()->with($typeMessage, $message);
    }

}
