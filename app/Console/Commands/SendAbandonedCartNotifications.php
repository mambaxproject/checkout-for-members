<?php

namespace App\Console\Commands;

use App\Events\AbandonedCartNotification;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendAbandonedCartNotifications extends Command
{
    protected $signature = 'app:send-abandoned-cart-notifications';

    protected $description = 'Triggered when a customer adds items to the cart but doesn’t
     complete the purchase. Use it to send reminders and recover sales';

    public function handle()
    {
        $abandonedCarts = $this->getAbandonedCartNotificate();
        foreach ($abandonedCarts as $abandonedCart) {
            try {
                $this->checkNeedAppendCoupom($abandonedCart);
                event(new AbandonedCartNotification($abandonedCart));
            } catch (\Throwable $th) {
                Log::channel('notification')->error(
                    'Erro ao buscar dados para enviar notificação carrinho abandonado.',
                    [
                        'error' => $th->getMessage(),
                        'function' => 'SendAbandonedCartNotifications.handle',
                        'body' => ['Id do carrinho abandonado: ' => $abandonedCart->id]
                    ]
                );
                continue;
            }
        }
    }

    private function getAbandonedCartNotificate(): array
    {
        return  DB::select('
        SELECT DISTINCT
            abandoned_carts.id, 
            abandoned_carts.name, 
            abandoned_carts.email, 
            abandoned_carts.phone_number, 
            abandoned_carts.link_checkout, 
            products.parent_id, 
            custom_notifications.id AS custom_notification_id, 
            custom_notifications.url_embed, 
            custom_notifications.text_whatsapp, 
            abandoned_carts.whatsapp_notification_sent, 
            abandoned_carts.email_notification_sent
        FROM abandoned_carts
        INNER JOIN products 
            ON products.id = abandoned_carts.product_id
        LEFT JOIN notification_actions 
            ON notification_actions.product_id = products.parent_id
            AND notification_actions.status = 1
        LEFT JOIN custom_notifications 
            ON custom_notifications.action_id = notification_actions.id 
            AND custom_notifications.event_id = 1
            AND custom_notifications.status = 1
        WHERE abandoned_carts.status = "pending"
        AND "' . date('Y-m-d H:i:s') .  '" >= COALESCE(
            DATE_ADD(abandoned_carts.created_at, INTERVAL custom_notifications.dispatch_time MINUTE), 
            DATE_ADD(abandoned_carts.created_at, INTERVAL 5 MINUTE)
        )
        AND abandoned_carts.whatsapp_notification_sent = 0
        AND abandoned_carts.email_notification_sent = 0
        AND abandoned_carts.deleted_at IS NULL
    ');
    }

    private function checkNeedAppendCoupom(object $abandonedCart): void
    {
        foreach (['cupom_id', 'start_at', 'end_at', 'code'] as $property) {
            $abandonedCart->$property = null;
        }

        $product = Product::where('id', $abandonedCart->parent_id)->get()->first();

        if (!$product) {
            return;
        }

        $coupon = $product->couponsDiscount()
            ->where('newsletter_abandoned_carts', true)
            ->get()
            ->last();

        if (!$coupon) {
            return;
        }

        $abandonedCart->cupom_id = $coupon->id;
        $abandonedCart->start_at = $coupon->start_at;
        $abandonedCart->end_at = $coupon->end_at;
        $abandonedCart->code = $coupon->code;
    }
}
