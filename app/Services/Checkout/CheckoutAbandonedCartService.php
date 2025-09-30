<?php

namespace App\Services\Checkout;

use App\Enums\StatusAbandonedCartEnum;
use App\Models\AbandonedCart;
use App\Models\Order;

class CheckoutAbandonedCartService
{
    public function findActiveCartByOrderData(Order $order): AbandonedCart|null
    {
        $productId = $order->item->product_id;
        $user      = $order->user;

        $abandonedCart = AbandonedCart::query()
            ->where('product_id', $productId)
            ->where('status', StatusAbandonedCartEnum::PENDING->value)
            ->where(function ($query) use ($order, $user) {
                $query->where('email', $user->email);
            })
            ->first();

        return $abandonedCart;
    }
}