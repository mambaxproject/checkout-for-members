<?php

namespace App\Http\Resources\Api\Data\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'phone_number'    => $this->phone_number ?? '',
            'document_number' => $this->document_number,
        ];
    }

}
