<?php

namespace App\Http\Controllers\Affiliate;

use App\Enums\SituationAffiliateEnum;
use App\Http\Requests\Affiliate\StoreAffiliateRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JoinController
{
    public function join(string $codeProduct): View
    {
        $product = Product::query()
            ->with([
                'media',
                'shop:id,name,owner_id',
                'affiliates:id,product_id,user_id',
                'coproducers:id,id,user_id,situation',
            ])
            ->where('code', $codeProduct)
            ->firstOrFail(['id', 'shop_id', 'name', 'description', 'paymentType', 'attributes']);

        $affiliateEnabled = boolval($product->getValueSchemalessAttributes('affiliate.enabled'));

        abort_if(! $affiliateEnabled, 404, 'Este produto não está disponível para afiliação.');

        $activeOffers = $product->activeOffers($product->paymentType)->get(['id', 'name', 'price']);

        return view('affiliate.join', compact('product', 'activeOffers'));
    }

    public function register(Product $product, StoreAffiliateRequest $request): RedirectResponse
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
            'verified'  => true,
        ]);

        $typeMessage = $manualApprovalAffiliate ? 'info' : 'success';

        $message = $manualApprovalAffiliate
            ? 'Afiliação realizada com sucesso. Aguarde a aprovação do vendedor.'
            : 'Afiliação realizada com sucesso.';

        return to_route('dashboard.affiliates.productsAffiliate')
            ->with($typeMessage, $message);
    }

}
