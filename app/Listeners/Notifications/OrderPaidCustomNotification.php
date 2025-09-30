<?php

namespace App\Listeners\Notifications;

use App\Enums\CustomNotificationEventEnum;
use App\Events\OrderApproved;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaidCustomNotification implements ShouldQueue
{

    use SendCustomNotificationHelper;
    
    public $connection = 'redis';
    public $queue = 'notifications';
    private int $eventTypeId;
    private int $orderId;

    public function handle(OrderApproved $event): void
    {
        $order = $event->order;

        if ($order->payment->isCreditCard) {
            return;
        }

        try {
            $this->orderId = $order->id;
            $this->eventTypeId = CustomNotificationEventEnum::BOLETOPIXPAYMENT->value;
            $this->generalProcess();
        } catch (\Throwable $th) {
            Log::channel('notification')->error(
                'Erro ao criar notificação.',
                [
                    'error' => $th->getMessage(),
                    'function' => 'OrderPaidCustomNotification.handle',
                    'body' => ['order' => !is_null($this->orderId) ? $this->orderId : null]
                ]
            );
        }
    }
}
