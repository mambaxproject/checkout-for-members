<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class AbandonedCartNotification
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public object $abandonedCart) {}
}
