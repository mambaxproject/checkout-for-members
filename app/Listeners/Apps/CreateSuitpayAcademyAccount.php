<?php

namespace App\Listeners\Apps;

use App\Events\OrderApproved;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class CreateSuitpayAcademyAccount implements ShouldBeUnique, ShouldQueue
{
    public function handle(OrderApproved $event): void
    {
        $order    = $event->order;
        $username = $order->getValueSchemalessAttributes('customField.nomeDeUsuarioSuitAcademy') ?? $order->user->email;
        $url      = config('services.suitpay.base_url') . '/api/v1/academy/create-account';

        if (! $username) {
            return;
        }

        $payload = [
            'username'   => $username,
            'name'       => $order->user->name,
            'email'      => $order->user->email,
            'purchaseId' => $order->id,
        ];

        try {
            $response = Http::post($url, $payload);

            $order->shop->logRequests()->create([
                'url'         => $url,
                'content'     => $payload,
                'response'    => $response->body(),
                'status_code' => $response->status(),
            ]);

        } catch (\Exception $e) {
            $order->shop->logRequests()->create([
                'url'      => $url,
                'content'  => $payload,
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
        $productIdsSuitAcademy = [123, 271];

        return $event->order->items->whereIn('product.parentProduct.id', $productIdsSuitAcademy)->isNotEmpty();
    }

    public function uniqueId($event): int
    {
        return $event->order->id;
    }
}
