<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemOrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'item_quantity'    => $this->quantity,
        ];
    }

}
