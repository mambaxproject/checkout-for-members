<?php

namespace App\Http\Resources\Api\Data\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoProducerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'email'                 => $this->email,
            'allow_producer_sales'  => (bool) $this->allow_producer_sales,
            'allow_affiliate_sales' => (bool) $this->allow_affiliate_sales,
            'percentage_commission' => $this->percentage_commission,
            'valid_until_at'        => $this->valid_until_at,
            'situation'             => $this->situation,
        ];
    }
}
