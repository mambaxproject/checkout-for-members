<?php

namespace App\Listeners;

use App\Events\OrderApproved;
use App\Models\Order;
use App\Services\SuitPay\Endpoints\SuitpaySubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderApproved implements ShouldQueue
{

    public function handle(OrderApproved $event): void
    {
        try {
            $this->sendEmailSteps($event->order);
        } catch (\Throwable $th) {
            Log::channel('database')->error(
                'Erro ao enviar email de venda aprovada.',
                [
                    'function' => 'SendEmailOrderApproved.handle',
                    'body' => $event->order->toArray(),
                    'error' => $th->getMessage()
                ]
            );
            throw $th;
        }
    }

    private function sendEmailSteps(Order $order): void
    {
        $order->loadMissing('items.product.parentProduct');

        $check = $this->shouldNotifyOrderApproved($order);

        if (!$check) {
            return;
        }

        Mail::to($order->shop->owner->email)
            ->send(new \App\Mail\Orders\Shop\OrderApproved($order));

        foreach ($order->items as $item) {
            Mail::to($order->user->email)
                ->send(new \App\Mail\Orders\Customer\OrderApproved($order, $item->product));
        }
    }

    private function shouldNotifyOrderApproved(Order $order): bool
    {
        if (!$order->payment->isCreditCard) {
            return true;
        }

        if (!$order->isSubscriptionCharge) {
            return true;
        }

        $quantityTransactions = (new SuitpaySubscriptionService(
            $order->shop->client_id_banking,
            $order->shop->client_secret_banking
        ))->getQuantityPaidTransactionsRecurrency($order->payment->getRawOriginal('recurrency_id'));

        return $quantityTransactions == 1;
    }
}
