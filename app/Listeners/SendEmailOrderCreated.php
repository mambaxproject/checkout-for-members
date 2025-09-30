<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderCreated implements ShouldQueue
{

    public function handle(OrderCreated $event): void
    {
        Mail::to($event->order->user->email)
            ->send(new \App\Mail\Orders\Customer\OrderCreated($event->order));
    }
}
