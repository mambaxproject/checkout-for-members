<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'amount'         => $this->amount,
            'payment_method' => $this->payment_method,
            'payment_status' => strip_tags($this->payment_status),
            'user'           => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'items'          => $this->whenLoaded('items', fn() => ItemOrderResource::collection($this->items)),
            'payments'       => $this->whenLoaded('payments', fn() => OrderPaymentResource::collection($this->payments)),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }

}
