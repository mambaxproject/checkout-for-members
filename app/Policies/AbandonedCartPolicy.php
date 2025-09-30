<?php

namespace App\Policies;

use App\Models\AbandonedCart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AbandonedCartPolicy
{
    public function show(User $user, AbandonedCart $abandonedCart) {

        $userId      = $user->id;
        $shopOwnerId = $abandonedCart->shop->owner_id;
        $product     = $abandonedCart->product;

        $hasPermission = $userId === $shopOwnerId
            || optional($product)->parentProduct->coproducers()->active()->where('user_id', $userId)->exists()
            || optional($product)->parentProduct->affiliates()->active()->where('user_id', $userId)->exists();

        return $hasPermission
            ? Response::allow()
            : Response::deny('Você não tem permissão para visualizar este carrinho abandonado.');
    }
}
