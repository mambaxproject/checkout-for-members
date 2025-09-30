<?php

namespace App\Listeners;

use App\Events\AcceptedCoproducer as AcceptedCoproducerEvent;
use App\Mail\Coproducers\AcceptedCoproducer;
use App\Mail\Coproducers\AcceptedCoproducerShop;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailAcceptedCoproducer implements ShouldQueue
{

    public function handle(AcceptedCoproducerEvent $event): void
    {
        $coproducer = $event->coproducer;
        $product = $coproducer->product;

        Mail::to($coproducer->email)->send(new AcceptedCoproducer($coproducer, $product));
        Mail::to($product->shop->owner->email)->send(new AcceptedCoproducerShop($coproducer, $product));
    }
}
