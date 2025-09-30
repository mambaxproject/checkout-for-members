<?php

namespace App\Listeners;

use App\Enums\StatusEnum;
use App\Events\OrderApproved;
use App\Models\Order;
use App\Services\FacebookPixel\Endpoints\PixelPurchaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class DispatchFacebookPurchasePixel implements ShouldQueue
{
    public function handle(OrderApproved $event): void
    {
        foreach ($this->getProductPixels($event->order) as $pixel) {
            $response = (new PixelPurchaseService)->send($pixel, $event->order);

            if ($response->failed()) {
                Log::error('send-facebook-purchase-pixel-error', [
                    'body' => $response->body(),
                ]);
            }
        }
    }

    private function getProductPixels(Order $order)
    {
        $product = $order->item->product->parentProduct;

        return $product->pixels()->with(['pixelService'])
            ->whereNotNull('attributes->access_token')
            ->where('attributes->access_token', '<>', '')
            ->where('attributes->backend_purchase', true)
            ->whereHas('pixelService', function ($query) {
                $query->whereName('Facebook')
                    ->where('status', StatusEnum::ACTIVE->name);
            })
            ->whereNull('user_id')
            ->when($order->affiliate, function ($query) use ($order) {
                $query->orWhere('user_id', $order->affiliate->user_id);
            })
            ->when($order->payment->isPix, function ($query) {
                $query->where('mark_pix', true);
            })
            ->when($order->payment->isBillet, function ($query) {
                $query->where('mark_billet', true);
            })->get();
    }

    public function shouldQueue($event): bool
    {
        $order   = $event->order;
        $product = $order->item->product->parentProduct;

        return $product->pixels()->with(['pixelService'])
            ->whereNotNull('attributes->access_token')
            ->where('attributes->access_token', '<>', '')
            ->where('attributes->backend_purchase', true)
            ->whereHas('pixelService', function ($query) {
                $query->whereName('Facebook')
                    ->where('status', StatusEnum::ACTIVE->name);
            })
            ->where(function ($query) use ($order) {
                if ($order->payment->isPix) {
                    $query->where('mark_pix', true);
                } else if ($order->payment->isBillet) {
                    $query->where('mark_billet', true);
                }
            })->exists();
    }
}
