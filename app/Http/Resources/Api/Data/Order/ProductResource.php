<?php

namespace App\Http\Resources\Api\Data\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->product->id,
            'name'             => $this->product->name,
            'url'              => $this->product->url,
            'description'      => $this->product->description ?? '',
            'featuredImageUrl' => $this->product?->getMedia('featuredImage')?->last()?->getUrl() ?? '',
        ];
    }

}
