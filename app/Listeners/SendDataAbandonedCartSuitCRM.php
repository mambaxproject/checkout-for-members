<?php

namespace App\Listeners;

use App\Actions\SendAbandonedCartDataToSuitpayCRM;
use App\Events\AbandonedCartNotification;
use App\Models\AbandonedCart;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Response;

class SendDataAbandonedCartSuitCRM implements ShouldBeUnique, ShouldQueue
{
    private AbandonedCart $cart;

    public function handle($event): void
    {
        $cart = $this->getAbandonedCart($event);

        if (!$cart->shop->isCRMActive) return;

        try {
            $response = (new SendAbandonedCartDataToSuitpayCRM(
                $cart,
                $event instanceof AbandonedCartNotification)
            )->handle();

            foreach ($response as $log) {
                $cart->shop->logRequests()->create([
                    'url'         => $log['url'],
                    'content'     => $log['content'],
                    'response'    => $log['response'],
                    'status_code' => $log['status_code'],
                ]);
            }

        } catch (\Exception $e) {
            $cart->shop->logRequests()->create([
                'url'      => config('services.suitpay.base_url') . '/api/v1/crm/sales/createOpportunitySales',
                'content'  => [],
                'response' => json_encode([
                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                    'file'    => $e->getFile(),
                ]),
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }

    public function shouldQueue($event): bool
    {
        return $this->getAbandonedCart($event)->shop->isCRMActive;
    }

    public function uniqueId($event): int
    {
        return $this->getAbandonedCart($event)->id;
    }

    private function getAbandonedCart($event): AbandonedCart
    {
        $cart = $event->cart ?? $event->abandonedCart;

        if (isset($this->cart)) return $this->cart;

        $this->cart = AbandonedCart::with(['shop'])->find($cart->id);

        return $this->cart;
    }
}
