<?php

namespace App\Listeners\Apps;

use App\Actions\SendDataToUtmify;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Http\Response;

class SendDataOrderUtmify implements ShouldBeUnique, ShouldQueue
{
    const APP_SLUG = 'utmify';

    public function handle($event): void
    {
        try {
            $responseDataSentUtmify = (new SendDataToUtmify($event->order))->handle();

            $event->order->shop->logRequests()->create([
                'url'         => $responseDataSentUtmify['url'],
                'content'     => $responseDataSentUtmify['content'],
                'response'    => $responseDataSentUtmify['response'],
                'status_code' => Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            $event->order->shop->logRequests()->create([
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
        return $event->order->shop
            ->apps()
            ->active()
            ->hasApp(self::APP_SLUG)
            ->exists();
    }

    public function uniqueId($event): int
    {
        return $event->order->id;
    }

}
