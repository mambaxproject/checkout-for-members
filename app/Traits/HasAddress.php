<?php

namespace App\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAddress
{

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')
            ->withDefault([
                'zip_code'       => '74815710',
                'street_address' => "Quadra76 Lote 02/80 Edificio Goiania Corporate",
                'neighborhood'   => "Set Central",
                'number'         => "N",
                'complement'     => "",
                'city'           => "GOIANIA",
                'state'          => "GO",
            ]);
    }

}
