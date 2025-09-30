<?php

namespace App\Observers;

use App\Models\Checkout;

class CheckoutObserver
{
    public function created(Checkout $checkout): void
    {
        $this->updateDefaultCheckout($checkout);
    }

    public function saved(Checkout $checkout): void
    {
        $this->updateDefaultCheckout($checkout);
    }

    private function updateDefaultCheckout(Checkout $checkout): void
    {
        if ($checkout->default && $checkout->product_id) {
            Checkout::where('shop_id', $checkout->shop_id)
                ->where('product_id', $checkout->product_id)
                ->where('id', '!=', $checkout->id)
                ->update(['default' => false]);
        }
    }
}
