<?php

namespace App\Services\Checkout;

use App\Enums\TypeItemOrderEnum;
use App\Models\{Customer, Order};
use App\Services\Checkout\Cart\CheckoutCartService;
use Illuminate\Support\Str;

class CheckoutOrderService
{
    private CheckoutCartService $cart;

    public function __construct(CheckoutCartService $cart)
    {
        $this->cart = $cart;
    }

    public function create(Customer $customer): Order
    {
        $shop = $this->cart->shop();
        $this->cart->calculate();

        $order = $shop->orders()->create([
            'user_id'       => $customer->id,
            'affiliate_id'  => session()->pull('affiliate_id'),
            'coproducer_id' => session()->pull('coproducer_id'),
            'amount'        => $this->cart->total(),
            'subtotal'      => $this->cart->subtotal(),
            'first_amount'  => $this->cart->firstAmount(),
            'client_orders_uuid' => Str::uuid()->toString()
        ]);

        if ($attributes = $this->cart->attributes()) {
            $order->attributes->set($attributes);
            $order->save();
        }

        if ($order->affiliate_id) {
            $order->update([
                'affiliate_amount' => $order->affiliateAmount,
            ]);
        }

        foreach ($this->cart->items() as $item) {
            $order->items()->create([
                'product_id' => $item->product()->id,
                'quantity'   => $item->quantity(),
                'amount'     => $item->price(),
                'type'       => $item->isOrderBump() ? TypeItemOrderEnum::ORDER_BUMP->name : TypeItemOrderEnum::CART->name
            ]);
        }

        $coupon = $this->cart->coupon();

        if ($coupon) {
            $order->discounts()->create([
                'coupon_discount_id' => $coupon->id,
            ]);
        }

        $abandonedCart = (new CheckoutAbandonedCartService)->findActiveCartByOrderData($order);

        if ($utmLink = $this->getUTMLink($order)) {
            $order->update(['utm_link_id' => $utmLink->id]);
        }

        if ($abandonedCart) {
            $abandonedCart->update([
                'order_id'     => $order->id,
                'affiliate_id' => session()->pull('affiliate_id'),
            ]);
        }

        return $order;
    }

    private function getUTMLink(Order $order)
    {
        $utmData = $order->attributes->get('utm') ?? [];

        $utmLink = $order->item->product->utmLinks()
            ->where('utm_source', $utmData['source'] ?? null)
            ->where('utm_medium', $utmData['medium'] ?? null)
            ->where('utm_campaign', $utmData['campaign'] ?? null)
            ->where('utm_term', $utmData['term'] ?? null)
            ->where('utm_content', $utmData['content'] ?? null)
            ->first();

        return $utmLink;
    }
}
