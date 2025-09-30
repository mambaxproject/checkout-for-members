<?php

namespace App\Http\Resources\Api\Data\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'currency'   => 'BRL',
            'amount'     => $this->amount,
            'net_amount' => $this->net_amount,
            'created_at' => $this->created_at,
            'payment'    => [
                'external_identification' => $this->payments->first()->external_identification,
                'method'                  => $this->paymentMethod,
                'status'                  => $this->paymentStatus,
                'codePix'                 => $this->when(! is_null($this->payments?->first()?->isPix), fn () => ($this->payments->first()?->external_content)),
                'paid_at'                 => $this->when(! is_null($this->payments->first()?->paid_at), fn () => ($this->payments->first()?->paid_at?->format('Y-m-d H:i:s'))),
            ],
            'customer' => $this->whenLoaded('user', fn () => new CustomerResource($this->user)),
            'items'    => $this->whenLoaded('items', fn () => ProductResource::collection($this->items)),
        ];
    }

}
