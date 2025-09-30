<?php

namespace App\Listeners;

use App\Events\OrderApproved;
use App\Mail\Orders\Shop\AccessReleasedCustomer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailAccessReleasedCustomer implements ShouldQueue
{
    public function handle(OrderApproved $event): void
    {
        Mail::to($event->order->shop->owner->email)
            ->send(new AccessReleasedCustomer($event->order));
    }
}
