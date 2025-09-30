<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Data\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderBumpResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'description'       => $this->description ?? "",
            'title_cta'         => $this->title_cta ?? "",
            'promotional_price' => $this->promotional_price,
            'payment_methods'   => $this->payment_methods,
            'product'           => $this->whenLoaded('product', fn() => new ProductResource(($this->product))),
            'productOffer'      => $this->whenLoaded('productOffer', fn() => new ProductResource(($this->productOffer))),
            'brazilianPrice'    => $this->brazilianPrice
        ];
    }

}
