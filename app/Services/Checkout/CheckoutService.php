<?php

namespace App\Services\Checkout;

use App\Http\Requests\Checkout\PaymentRequest;
use App\Http\Resources\Api\OrderPaymentResource;
use App\Models\Shop;
use App\Services\Checkout\Cart\CheckoutCartService;

class CheckoutService
{
    public function pay(PaymentRequest $request): OrderPaymentResource
    {
        $shop = Shop::findOrFail($request->shop_id, ['id', 'client_id_banking', 'client_secret_banking']);

        $customer = (new CheckoutUserService)->createCustomer($request);

        $cart = (new CheckoutCartService($shop, $customer))->createCart($request);

        $order = (new CheckoutOrderService($cart))->create($customer);

        (new CheckoutAbandonedCartTrackingService)->createTracking($request);

        return new OrderPaymentResource((new CheckoutPaymentService($order, $cart))
            ->execute($request));
    }
}
