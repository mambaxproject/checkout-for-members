<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    public function created(Product $product): void
    {
        $product->update(
            [
                'code' => str()->random(8),
                'client_product_uuid' => Str::uuid()->toString()
            ]
        );
    }

    public function updated(Product $product): void
    {
        if ($product->wasChanged('situation')) {
            $product->offers()->update(['situation' => $product->situation]);
        }
    }

    public function saved(Product $product): void
    {
        if (request()->filled('product.attributes.affiliate.defaultTypeValue') && request()->filled('product.attributes.affiliate.defaultValue')) {
            $product->affiliates()->update([
                'type'  => $product->getValueSchemalessAttributes('affiliate.defaultTypeValue'),
                'value' => $product->getValueSchemalessAttributes('affiliate.defaultValue'),
            ]);
        }
    }
}
