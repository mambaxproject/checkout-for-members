<?php

namespace App\Http\Resources\Api\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'description'     => $this->getCustomProperty('description', ''),
            'url'             => $this->getUrl() ?? '',
            'mime_type'       => $this->mime_type ?? '',
            'collection_name' => $this->collection_name,
        ];
    }

}
