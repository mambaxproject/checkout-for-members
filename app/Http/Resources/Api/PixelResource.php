<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PixelResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'pixel_id'    => $this->pixel_id,
            'mark_billet' => boolval($this->mark_billet),
            'mark_pix'    => boolval($this->mark_pix),
            'service'     => $this->whenLoaded('pixelService', fn() => new PixelServiceResource(($this->pixelService))),
            'attributes'  => $this->attributes,
        ];
    }

}
