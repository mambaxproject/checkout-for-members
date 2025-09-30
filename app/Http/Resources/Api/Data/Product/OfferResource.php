<?php

namespace App\Http\Resources\Api\Data\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'price'             => $this->price,
            'priceFirstPayment' => $this->when($this->isRecurring, fn () => $this->priceFirstPayment),
            'numberPayments'    => $this->when($this->isRecurring, fn () => $this->numberPaymentsRecurringPayment),
            'cyclePayment'      => $this->when($this->isRecurring, fn () => $this->cyclePayment),
        ];
    }
}
