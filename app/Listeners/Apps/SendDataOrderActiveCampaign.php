<?php

namespace App\Listeners\Apps;

use App\Actions\SendDataToActiveCampaign;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Http\Response;

class SendDataOrderActiveCampaign implements ShouldBeUnique, ShouldQueue
{
    const APP_SLUG = 'active-campaign';

    public function handle($event): void
    {
        try {
            $responseDataSentActiveCampaign = (new SendDataToActiveCampaign($event->order))->handle();

            $event->order->shop->logRequests()->create([
                'url'         => $responseDataSentActiveCampaign['url'],
                'content'     => $responseDataSentActiveCampaign['data'],
                'response'    => $responseDataSentActiveCampaign['response'],
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
