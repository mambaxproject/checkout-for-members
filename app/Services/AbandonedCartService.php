<?php

namespace App\Services;

use App\Enums\StatusAbandonedCartEnum;
use App\Models\{AbandonedCartsTracking, Order};

class AbandonedCartService
{
    public function checkCanConvertCart(Order $order): void
    {
        $abandonedCart = $order->abandonedCarts()->first();

        if (! $abandonedCart) {
            return;
        }

        $existsTracking = AbandonedCartsTracking::where('abandoned_cart_id', $abandonedCart->id)->exists();

        if ($existsTracking) {
            $abandonedCart->update(['status' => StatusAbandonedCartEnum::CONVERTED->value]);

            return;
        }

        $abandonedCart->delete();
    }
}
