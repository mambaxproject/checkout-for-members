<?php

namespace App\Listeners;

use App\Enums\StatusAbandonedCartEnum;
use App\Events\OrderApproved;
use App\Events\OrderCreated;
use App\Models\AbandonedCart;

class UpdateAbandonedCard
{

    public function handle(OrderCreated|OrderApproved $event): void
    {
        $order = $event->order->load('user', 'items', 'payments');

        $abandonedCart = AbandonedCart::query()
            ->where([
                'email' => $order->user->email,
                'payment_method' => $order->payments?->last()?->payment_method,
                'product_id' => $order->items->first()->product_id,
                'amount' => $order->amount
            ])
            ->first();

        if ($abandonedCart && !$abandonedCart->order_id) {
            $abandonedCart->update(['order_id' => $order->id]);

            if ($order->isPaid() && $abandonedCart->status === StatusAbandonedCartEnum::PENDING) {
                $abandonedCart->update(['status' => StatusAbandonedCartEnum::CONVERTED]);
            }
        }
    }

}
