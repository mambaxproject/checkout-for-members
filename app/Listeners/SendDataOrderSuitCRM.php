<?php

namespace App\Listeners;

use App\Actions\SendOrderDataToSuitpayCRM;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Response;

class SendDataOrderSuitCRM implements ShouldBeUnique, ShouldQueue
{
    public function handle($event): void
    {
        if (!$event->order->shop->isCRMActive) return;

        try {
            $response = (new SendOrderDataToSuitpayCRM($event->order))->handle();

            foreach ($response as $log) {
                $event->order->shop->logRequests()->create([
                    'url'         => $log['url'],
                    'content'     => $log['content'],
                    'response'    => $log['response'],
                    'status_code' => $log['status_code'],
                ]);
            }

        } catch (\Exception $e) {
            $event->order->shop->logRequests()->create([
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
        return $event->order->shop->isCRMActive;
    }

    public function uniqueId($event): int
    {
        return $event->order->id;
    }
}
