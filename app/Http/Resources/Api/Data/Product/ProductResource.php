<?php

namespace App\Http\Resources\Api\Data\Product;

use App\Http\Resources\Api\Data\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'description'       => $this->description,
            'category'          => $this->whenLoaded('category', fn () => new CategoryProductResource(($this->category))),
            'offers'            => $this->whenLoaded('offers', fn () => OfferResource::collection($this->offers)),
            'couponsDiscount'   => $this->whenLoaded('couponsDiscount', fn () => CouponDiscountResource::collection($this->couponsDiscount)),
            'coproducers'       => $this->whenLoaded('coproducers', fn () => CoProducerResource::collection($this->coproducers)),
            'affiliates'        => $this->whenLoaded('affiliates', fn () => AffiliateResource::collection($this->affiliates)),
            'externalSalesLink' => $this->getValueSchemalessAttributes('externalSalesLink'),
            'emailSupport'      => $this->getValueSchemalessAttributes('emailSupport'),
            'nameShop'          => $this->getValueSchemalessAttributes('nameShop'),
            'payment'           => [
                'methods'           => $this->getValueSchemalessAttributes('paymentMethods'),
                'daysDueDateBillet' => $this->when($this->getValueSchemalessAttributes('daysDueDateBillet'),
                    fn () => $this->getValueSchemalessAttributes('daysDueDateBillet')
                ),
                'maxInstallmentsCreditCard' => $this->when($this->getValueSchemalessAttributes('maxInstallments'),
                    fn () => $this->getValueSchemalessAttributes('maxInstallments')
                ),
            ],
            'affiliation' => [
                'enabled'                    => (bool) $this->getValueSchemalessAttributes('affiliate.enabled'),
                'approveRequestsManually'    => (bool) $this->getValueSchemalessAttributes('affiliate.approveRequestsManually'),
                'allowAccessToCustomersData' => (bool) $this->getValueSchemalessAttributes('affiliate.allowAccessToCustomersData'),
                'showProductInMarketplace'   => (bool) $this->getValueSchemalessAttributes('affiliate.showProductInMarketplace'),
                'emailSupport'               => $this->getValueSchemalessAttributes('affiliate.emailSupport') ?? '',
                'descriptionProduct'         => $this->getValueSchemalessAttributes('affiliate.descriptionProduct') ?? '',
                'defaultTypeValue'           => $this->getValueSchemalessAttributes('affiliate.defaultTypeValue') ?? '',
                'defaultValue'               => $this->getValueSchemalessAttributes('affiliate.defaultValue') ?? '',
                'link'                       => $this->linkJoinAffiliate ?? '',
            ],
            'linkThanksForOrder' => [
                'linkThanksForOrderInPIX'         => $this->getValueSchemalessAttributes('linkThanksForOrderInPIX') ?? '',
                'linkThanksForOrderInBILLET'      => $this->getValueSchemalessAttributes('linkThanksForOrderInBILLET') ?? '',
                'linkThanksForOrderInCREDIT_CARD' => $this->getValueSchemalessAttributes('linkThanksForOrderInCREDIT_CARD') ?? '',
            ],
            'guaranteeInDays'  => $this->guarantee,
            'paymentType'      => $this->paymentType,
            'featuredImageUrl' => $this->featuredImageUrl,
            'attachment'       => $this->whenLoaded('media', fn () => new MediaResource($this->getMedia('attachment')->last())),
            'situation'        => $this->situation,
        ];
    }
}
