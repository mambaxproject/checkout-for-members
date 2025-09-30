<?php

namespace App\Listeners\Apps;

use App\Actions\SendDataToReportana;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Response;
use Illuminate\Queue\Middleware\Skip;

class SendDataOrderReportana implements ShouldQueue, ShouldBeUnique
{

    const APP_SLUG = 'reportana';

    public function handle($event): void
    {
        try {
            $responseDataSentReportana = (new SendDataToReportana($event->order))->handle();

            $event->shop->logRequests()->create([
                'url'         => $responseDataSentReportana['url'],
                'content'     => $responseDataSentReportana['data'],
                'response'    => $responseDataSentReportana['response'],
                'status_code' => Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            $event->shop->logRequests()->create([
                'url'     => self::APP_SLUG,
                'content' => [],
                'response'    => json_encode([
                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                    'file'    => $e->getFile(),
                ]),
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }

    public function middleware(): array
    {
        $hasAppActive = $this->event->shop
            ->apps()
            ->active()
            ->hasApp(self::APP_SLUG)
            ->exists();

        return [
            Skip::unless($hasAppActive)
        ];
    }

    public function uniqueId($event): int
    {
        return $event->order->id;
    }

}
