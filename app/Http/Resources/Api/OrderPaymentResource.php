<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class OrderPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'order_hash'              => Crypt::encryptString($this->order_id),
            'payment_method'          => $this->payment_method,
            'payment_status'          => $this->payment_status,
            'payment_code'            => $this->external_content ?? '',
            'external_identification' => $this->external_identification,
            'external_url'            => $this->external_url,
            'installments'            => $this->installments,
            'due_date'                => $this->due_date,
            'paid_at'                 => $this->paid_at,
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
            'thanks_page'             => $this->order->thanksPage(),
        ];
    }

}
