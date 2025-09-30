<?php

namespace App\Traits;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasScopeActive
{

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('status', StatusEnum::ACTIVE->name);
    }

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === StatusEnum::ACTIVE->name,
        )->shouldCache();
    }

}
