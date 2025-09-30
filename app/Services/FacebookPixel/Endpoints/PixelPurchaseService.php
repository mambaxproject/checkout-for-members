<?php

namespace App\Services\FacebookPixel\Endpoints;


use App\Models\Order;
use App\Models\Pixel;
use Carbon\Carbon;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

class PixelPurchaseService extends BaseEndpoint
{
    public function send(Pixel $pixel, Order $order): PromiseInterface|Response
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                "id"         => ''.$item->product->id,
                "quantity"   => $item->quantity,
                "item_price" => $item->product->price,
            ];
        }

        $response = $this->service->init()->withQueryParameters([
            'access_token'    => $pixel->getValueSchemalessAttributes('access_token'),
            //'test_event_code' => 'TEST62603'
        ])->post("/v22.0/{$pixel->pixel_id}/events", [
            "data" => [
                [
                    "action_source" => "website",
                    "event_id"      => $order->id,
                    "event_name"    => 'Purchase',
                    "event_time"    => Carbon::now()->timestamp,
                    "user_data"     => [
                        "em" => hash('sha256', $order->user->email)
                    ],
                    "custom_data" => [
                        "currency" => "BRL",
                        "value" => $order->amount,
                        "contents" => $items,
                        "content_type" => "product",
                        "num_items" => count($items),
                        "content_ids" => array_column($items, 'id'),
                        "order_id" => $order->id,
                    ]
                ]
            ]
        ]);

        return $response;
    }
}