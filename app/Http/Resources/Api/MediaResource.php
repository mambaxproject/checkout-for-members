<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'thumbnail'       => $this->thumbnail,
            'url'             => $this->url,
            'collection_name' => $this->collection_name,
        ];
    }

}
