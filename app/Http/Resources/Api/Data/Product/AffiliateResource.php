<?php

namespace App\Http\Resources\Api\Data\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'code'      => $this->code,
            'type'      => $this->type,
            'value'     => $this->value,
            'situation' => $this->situation,
        ];
    }
}
