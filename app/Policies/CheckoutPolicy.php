<?php

namespace App\Policies;

use App\Models\{Checkout, User};
use Illuminate\Auth\Access\Response;

class CheckoutPolicy
{
    public function edit(User $user, Checkout $checkout): Response
    {
        return $user->id === $checkout->shop->owner_id
            ? Response::allow()
            : Response::deny('Você não tem permissão para editar este modelo de checkout.');
    }
}
