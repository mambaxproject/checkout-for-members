<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'name'        => $this->name,
            'description' => $this->description ?? "",
            'link'        => $this->link ?? "",
            'image'       => $this->whenLoaded('media', fn() => new MediaResource($this->image)),
        ];
    }

}
