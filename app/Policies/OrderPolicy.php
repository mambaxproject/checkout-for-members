<?php

namespace App\Policies;

use App\Models\{Order, User};
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    public function show(User $user, Order $order): Response
    {
        $order->loadMissing('shop', 'items.product.parentProduct');

        $userId      = $user->id;
        $shopOwnerId = $order->shop->owner_id;
        $product     = optional($order->items->first())->product?->parentProduct;

        $hasPermission = $userId === $shopOwnerId
            || optional($product)->coproducers()->active()->where('user_id', $userId)->exists()
            || optional($product)->affiliates()->active()->where('user_id', $userId)->exists();

        return $hasPermission
            ? Response::allow()
            : Response::deny('Você não tem permissão para visualizar este pedido.');
    }

}
