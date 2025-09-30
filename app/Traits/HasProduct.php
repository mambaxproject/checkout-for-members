<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasProduct
{

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'productable');
    }

}
