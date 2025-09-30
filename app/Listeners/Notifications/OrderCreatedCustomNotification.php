<?php

namespace App\Listeners\Notifications;

use App\Enums\CustomNotificationEventEnum;
use App\Events\OrderCreated;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class OrderCreatedCustomNotification implements ShouldQueue
{

    use SendCustomNotificationHelper;
    
    public $connection = 'redis';
    public $queue = 'notifications';
    private int $eventTypeId;
    private int $orderId;

    public function handle(OrderCreated $event): void
    {
        try {
            $this->orderId = $event->order->id;
            $this->setEventIdByPaymentTypeCreated($event->order);
            $this->generalProcess();
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao criar notificação.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'OrderCreatedCustomNotification.handle',
                    'body' => ['order' => !is_null($this->orderId) ? $this->orderId : null]
                ]
            );
        }
    }

    protected function setEventIdByPaymentTypeCreated(Order $order): void
    {
        if ($order->payment->isCreditCard) {
            $this->eventTypeId = CustomNotificationEventEnum::CARDPAYMENT->value;
            return;
        };

        $this->eventTypeId = CustomNotificationEventEnum::BOLETOPIXCREATED->value;
    }
}
