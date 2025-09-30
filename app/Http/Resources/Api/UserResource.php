<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'phone_number'    => $this->phone_number ?? '',
            'document_number' => $this->document_number,
            'birthday'        => $this->birthday ? $this->birthday->format('Y-m-d') : '',
            'photo'           => $this->avatar_url,
            'roles'           => $this->whenLoaded('roles', fn () => RoleResource::collection($this->roles)),
            'shops'           => $this->whenLoaded('shops', fn () => ShopResource::collection($this->shops)),
            'address'         => $this->whenLoaded('address', fn () => new AddressResource($this->address)),
            'attributes'      => $this->attributes,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }

}
