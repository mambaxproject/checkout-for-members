<?php

namespace App\Observers;

use App\Events\AbandonedCartCreated;
use App\Events\AbandonedCartStatusChange;
use App\Models\AbandonedCart;

class AbandonedCartObserver
{
    public function created(AbandonedCart $cart): void
    {
        event(new AbandonedCartCreated($cart));
    }

    public function updated(AbandonedCart $cart): void
    {
        if ($cart->wasChanged('status')) {
            event(new AbandonedCartStatusChange($cart));
        }
    }

}
