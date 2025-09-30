<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function edit(User $user, Product $product) {
        return $user->id === $product->shop->owner_id
            ? Response::allow()
            : Response::deny('Você não tem permissão para editar este produto.');
    }

    public function show(User $user, Product $product) {
        return $user->id === $product->shop->owner_id
            ? Response::allow()
            : Response::deny('Você não tem visualizar para editar este produto.');
    }
}
