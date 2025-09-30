<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponDiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'code'                  => $this->code,
            'name'                  => $this->name,
            'description'           => $this->description ?? '',
            'amount'                => $this->amount,
            'type'                  => $this->type,
            'payment_methods'       => $this->payment_methods,
            'auto_application'      => boolval($this->automatic_application),
            'only_first_order'      => boolval($this->only_first_order),
            'once_per_customer'     => boolval($this->once_per_customer),
            'allow_affiliate_links' => boolval($this->allow_affiliate_links),
            'start_at'              => $this->start_at->format('Y-m-d H:i:s'),
            'end_at'                => $this->end_at->format('Y-m-d H:i:s'),
        ];
    }

}
