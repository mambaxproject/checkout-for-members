<?php

namespace App\Listeners;

use App\Events\OrderApproved;
use App\Mail\Orders\Customer\OrderRecurringApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Services\SuitPay\Endpoints\SuitpaySubscriptionService;

class SendEmailOrderRecurringAproved implements ShouldQueue
{
    public function handle(OrderApproved $event): void
    {
        try {
            $this->sendEmailSteps($event->order);
        } catch (\Throwable $th) {
            Log::channel('database')->error(
                'Erro ao enviar email de recorrência aprovada.',
                [
                    'function' => 'SendEmailOrderRecurringAproved.handle',
                    'body' => $event->order->toArray(),
                    'error' => $th->getMessage()
                ]
            );
            throw $th;
        }
    }

    private function sendEmailSteps(Order $order): void
    {
        $check = $this->shouldNotifyOrderRecurringApproved($order);

        if (!$check) {
            return;
        }

        $product = $order->items->first()->product;

        Mail::to($order->user->email)
            ->send(new OrderRecurringApproved($order, $product));
    }

    private function shouldNotifyOrderRecurringApproved(Order $order): bool
    {
        if (!$order->payment->isCreditCard) {
            return false;
        }

        if (!$order->isSubscriptionCharge) {
            return false;
        }

        $quantityTransactions = (new SuitpaySubscriptionService(
            $order->shop->client_id_banking,
            $order->shop->client_secret_banking
        ))->getQuantityPaidTransactionsRecurrency(
            $order->payment->getRawOriginal('recurrency_id')
        );
        return $quantityTransactions != 1;
    }
}
