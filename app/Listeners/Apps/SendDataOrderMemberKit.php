<?php

namespace App\Listeners\Apps;

use App\Actions\SendDataToMemberKit;
use App\Events\OrderApproved;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Http\Response;

class SendDataOrderMemberKit implements ShouldBeUnique, ShouldQueue
{
    const APP_SLUG = 'member-kit';

    public function handle(OrderApproved $event): void
    {
        try {
            $shop = $event->order->shop;

            $responseDataSentMemberKit = (new SendDataToMemberKit($event->order))->handle();

            $shop->logRequests()->create([
                'url'         => $responseDataSentMemberKit['url'],
                'content'     => $responseDataSentMemberKit['data'],
                'response'    => $responseDataSentMemberKit['response'],
                'status_code' => Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            $shop->logRequests()->create([
                'url'      => self::APP_SLUG,
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
        $order         = $event->order;
        $parentProduct = $order?->items?->first()?->product?->parentProduct;

        if (! $parentProduct) {
            return false;
        }

        return $order->shop
            ->apps()
            ->active()
            ->hasApp(self::APP_SLUG)
            ->hasProductById($parentProduct->id)
            ->exists();
    }

    public function uniqueId($event): int
    {
        return $event->order->id;
    }

}
