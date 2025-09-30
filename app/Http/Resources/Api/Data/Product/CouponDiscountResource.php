<?php

namespace App\Http\Resources\Api\Data\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponDiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                         => $this->id,
            'code'                       => $this->code,
            'description'                => $this->description ?? '',
            'minimum_price_order'        => $this->minimum_price_order,
            'quantity'                   => $this->quantity,
            'type'                       => $this->type,
            'amount'                     => $this->amount,
            'start_at'                   => $this->start_at,
            'end_at'                     => $this->end_at,
            'automatic_application'      => $this->automatic_application,
            'once_per_customer'          => $this->once_per_customer,
            'newsletter_abandoned_carts' => $this->newsletter_abandoned_carts,
            'only_first_order'           => $this->only_first_order,
            'allow_affiliate_links'      => $this->allow_affiliate_links,
            'payment_methods'            => $this->payment_methods,
        ];
    }
}
