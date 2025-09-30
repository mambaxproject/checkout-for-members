<?php

namespace App\Listeners;

use App\Events\OrderFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderFailed implements  ShouldQueue
{
    public function handle(OrderFailed $event): void
    {
        Mail::to($event->order->user->email)
            ->send(new \App\Mail\Orders\Customer\OrderFailed($event->order));
    }
}
