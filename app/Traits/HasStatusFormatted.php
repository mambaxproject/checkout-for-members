<?php

namespace App\Traits;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasStatusFormatted
{

    public function statusFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => StatusEnum::getFromName($this->status)
        )->shouldCache();
    }

}
