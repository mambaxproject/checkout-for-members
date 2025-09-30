<?php

namespace App\Http\Resources\Api\Data\AbandonedCart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbandonedCartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'phone_number'   => $this->phone_number,
            'payment_method' => $this->payment_method,
            'amount'         => $this->amount,
            'link_checkout'  => $this->link_checkout,
            'status'         => $this->status,
            'created_at'     => $this->created_at,
            'product'        => [
                'id'   => $this->product->parentProduct->id,
                'name' => $this->product->parentProduct->name,
            ],
        ];
    }

}
