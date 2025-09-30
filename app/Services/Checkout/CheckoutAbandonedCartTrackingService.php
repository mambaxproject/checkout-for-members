<?php

namespace App\Services\Checkout;

use App\Enums\StatusAbandonedCartEnum;
use App\Http\Requests\Checkout\PaymentRequest;
use App\Models\AbandonedCart;
use App\Models\AbandonedCartsTracking;
use App\Models\Order;
use Illuminate\Support\Arr;

class CheckoutAbandonedCartTrackingService
{
    public function createTracking(PaymentRequest $request): void
    {
        $requestData = $request->toArray();

        if (!array_key_exists('utm', $requestData)) {
            return;
        }

        $utmData = $requestData['utm'];

        if (!$this->checkHasAllItemsParamsToCreate($utmData)) {
            return;
        };

        $exists = AbandonedCartsTracking::where('abandoned_cart_id', Arr::get($utmData, 'abId'))->exists();

        if ($exists) {
            return;
        }

        $body = $this->treatUtmTrackingToInsert($utmData);
        AbandonedCartsTracking::create($body);
    }

    private function checkHasAllItemsParamsToCreate(array $utmData): bool
    {
        $utmKeys = ['abId', 'source', 'campaign', 'medium'];
        $utmParamsFiltered = array_intersect_key($utmData, array_flip($utmKeys));
        return count($utmParamsFiltered) > 3;
    }

    private function treatUtmTrackingToInsert(array $utmData): array
    {
        return [
            'abandoned_cart_id' => $utmData['abId'],
            'utm_source' => $utmData['source'],
            'utm_campaign' => $utmData['campaign'],
            'utm_medium' => $utmData['medium']
        ];
    }
}
