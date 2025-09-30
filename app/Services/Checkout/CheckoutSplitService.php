<?php

namespace App\Services\Checkout;

use App\Models\Order;
use App\Services\Checkout\Cart\CheckoutCartService;

class CheckoutSplitService
{
    const APP_TAX = 0.06;

    private Order $order;

    private CheckoutCartService $cart;

    private float $amount;

    private array $splitData = [];

    public function __construct(Order $order, CheckoutCartService $cart)
    {
        $this->order  = $order;
        $this->cart   = $cart;
        $this->amount = $order->amount - $this->cart->tax();
    }

    public function split(): array
    {
        $this->removeAppTax();
        $this->affiliateHandler();
        $this->coproducersHandler();

        return $this->splitData;
    }

    private function removeAppTax(): void
    {
        $this->amount -= $this->amount * self::APP_TAX;
    }

    private function affiliateHandler(): void
    {
        $affiliate = $this->order->affiliate;

        if ($this->hasAffiliate()) {

            $commission = $affiliate->type === 'percentage' ? ($this->amount / 100) * $affiliate->value : ($affiliate->value ?? 0);
            $this->amount -= $commission;

            $data = [
                'username'        => $affiliate->user->shop()->username_banking,
                'splitTypePerson' => 'AFFILIATE',
            ];

            if ($affiliate->type === 'percentage') {
                $data['percentageSplit'] = $affiliate->value;
            } else {
                $data['valueSplit'] = $affiliate->value;
            }

            $this->splitData[] = $data;
        }
    }

    private function hasAffiliate(): bool
    {
        $affiliate = $this->order->affiliate;

        return $affiliate and $affiliate->isActive;
    }

    private function coproducersHandler(): void
    {
        $coproducers = $this->order->item->product->parentProduct
            ->coproducers()
            ->active()
            ->validPeriod()
            ->get();

        foreach ($coproducers as $coproducer) {
            if (($coproducer->allow_affiliate_sales && $this->hasAffiliate()) ||
                ($coproducer->allow_producer_sales && ! $this->hasAffiliate())) {

                $this->splitData[] = [
                    'username'        => $coproducer->user->shop()->username_banking,
                    'percentageSplit' => $coproducer->percentage_commission,
                    'splitTypePerson' => 'CO_PRODUCER',
                ];
            }
        }
    }
}
