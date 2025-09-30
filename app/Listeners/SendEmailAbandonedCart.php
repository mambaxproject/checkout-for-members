<?php

namespace App\Listeners;

use App\Events\AbandonedCartNotification;
use App\Mail\AbandonedCarts\Customer\AbandonedCartCreated;
use App\Mail\AbandonedCarts\Customer\AbandonedCartWithCoupon;
use App\Models\AbandonedCart;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailAbandonedCart implements ShouldQueue
{
    public $connection = 'redis';
    public $queue = 'notifications';

    public function handle(AbandonedCartNotification $event): void
    {
        $abandonedCart = $event->abandonedCart;
        $abandonedCart->link_checkout = $this->setParamsAbandonedCartEmail($abandonedCart);
        if ($this->checkAlreadySend($abandonedCart)) {
            return;
        }

        $this->sendEmailNotification($abandonedCart);
        AbandonedCart::where('id', $abandonedCart->id)->update(
            [
                'email_notification_sent' => true,
                'updated_at' => now()
            ]
        );
    }

    private function setParamsAbandonedCartEmail(object $abandonedCart): string
    {
        return $abandonedCart->link_checkout . '?abId=' . $abandonedCart->id .
            '&utm_source=abandoned_cart&utm_campaign=abandoned_cart&utm_medium=email';
    }

    private function sendEmailNotification(Object $abandonedCart): void
    {
        $originalProduct = (new ProductRepository(new Product()))
            ->getById($abandonedCart->parent_id);

        $validCupom = !is_null($abandonedCart->cupom_id) && $abandonedCart->end_at > date("Y-m-d H:i:s");

        if ($validCupom) {
            Mail::to($abandonedCart->email)
                ->send(new AbandonedCartWithCoupon($abandonedCart, $originalProduct));
            return;
        }
        Mail::to($abandonedCart->email)
            ->send(new AbandonedCartCreated($abandonedCart, $originalProduct));
    }


    private function checkAlreadySend(object $abandonedCart): bool
    {
        $abandonedCartNotification = AbandonedCart::where('id', $abandonedCart->id)
            ->select('email_notification_sent')->first();

        return $abandonedCartNotification->email_notification_sent;
    }
}
