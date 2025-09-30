<?php

namespace App\Events;

use App\Models\AbandonedCart;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AbandonedCartStatusChange
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public AbandonedCart $cart
    ) { }    
}
