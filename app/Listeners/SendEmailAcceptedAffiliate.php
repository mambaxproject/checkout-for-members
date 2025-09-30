<?php

namespace App\Listeners;

use App\Events\AcceptedAffiliate as AcceptedAffiliateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailAcceptedAffiliate implements ShouldQueue
{

    public function handle(AcceptedAffiliateEvent $event): void
    {
        Mail::to($event->affiliate->email)->send(new \App\Mail\Affiliates\AcceptedAffiliate($event->affiliate));
        Mail::to($event->affiliate->product->shop->owner->email)->send(new \App\Mail\Affiliates\AcceptedAffiliateShop($event->affiliate));
    }
}
