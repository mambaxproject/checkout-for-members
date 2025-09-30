<?php

namespace App\Services\Checkout\Cart;

use App\Enums\PaymentMethodEnum;
use App\Http\Requests\Checkout\PaymentRequest;
use App\Models\{CouponDiscount, Customer, OrderBump, Product, Shop};
use App\Services\DiscountService;
use App\Services\SuitPay\Endpoints\SuitpayCreditCardService;

class CheckoutCartService
{
    private float $total = 0;

    private float $subtotal = 0;

    private float $firstAmount = 0;

    private float $discount = 0;

    private ?CouponDiscount $coupon = null;

    private float $tax = 0;

    /* @var $items CartItem[] */
    private array $items = [];

    private ?int $installments = null;

    private Shop $shop;

    private Customer $user;

    private array $attributes = [];

    public function __construct(Shop $shop, Customer $user)
    {
        $this->shop = $shop;
        $this->user = $user;
    }

    public function createCart(PaymentRequest $request): self
    {
        $this->setProducts($request->items);

        if ($request->payment['paymentMethod'] == PaymentMethodEnum::CREDIT_CARD->name) {
            $this->installments = $request->payment['installments'] ?? null;
        }

        if ($request->coupon_code) {
            $this->setCoupon($request);
        }

        if ($request->affiliate_code) {
            $this->setAffiliateByCode($request->affiliate_code);
        }

        if ($request->has('attributes')) {
            $this->attributes = $request->get('attributes');
        }

        $this->calculate();

        return $this;
    }

    private function setAffiliateByCode(string $code): void
    {
        $affiliate = $this->shop->affiliates()->where('affiliates.code', $code)->first(['affiliates.id', 'affiliates.code']);

        if (! $affiliate) {
            throw new \Exception('Affiliate not found', 404);
        }

        session()->put('affiliate_id', $affiliate->id);
    }

    private function setProducts(array $items): void
    {
        foreach ($items as $item) {

            if (isset($item['order_bump_id'])) {
                $order_bump    = OrderBump::findOrFail($item['order_bump_id'], ['id', 'product_id', 'product_offer_id', 'promotional_price']);
                $this->items[] = new CartItem(
                    $order_bump->product_offer,
                    $order_bump->promotional_price,
                    $item['quantity'],
                    true
                );
            } else {
                $product = Product::findOrFail($item['product_id'], ['id', 'price', 'parent_id', 'priceFirstPayment']);
                $price   = $product->price;

                $this->items[] = new CartItem(
                    $product,
                    $price,
                    $item['quantity']
                );
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function setCoupon($data): void
    {
        $coupon = $this->items[0]->product()
            ->parentProduct
            ->couponsDiscount()
            ->with(['offers', 'usage'])
            ->where('code', $data->coupon_code)
            ->firstOrFail();

        $result = (new DiscountService)
            ->validateCoupon(
                $coupon,
                $this->items[0]->price(),
                $this->user,
                isset($data->affiliate_code),
                $this->items[0]->product()->id
            );

        if (! $result['success']) {
            throw new \Exception($result['message'], 500);
        }

        $this->coupon = $coupon;
    }

    public function calculateDiscount(CouponDiscount $coupon, float $value): float
    {
        return $coupon->isTypeValue ? $coupon->amount : $coupon->amount * ($value / 100);
    }

    private function calcRecurring(array $items): void
    {
        $firstAmount = 0;
        $recurringAmount = 0;
        $amountForDiscountCalc = 0;

        foreach ($items as $item) {
            $productPrice =  $item->product()->hasFirstPayment ? $item->product()->priceFirstPayment : $item->price();

            $firstAmount += $productPrice;

            if (!$item->isOrderBump()) {
                $recurringAmount += $item->price();
                $amountForDiscountCalc += $productPrice;
            }
        }

        $this->discount = $this->coupon
            ? $this->calculateDiscount($this->coupon, $amountForDiscountCalc)
            : 0;

        $this->subtotal = $firstAmount;

        $firstAmount -= $this->discount;

        if ($this->installments) {
            $firstAmount = $this->getCardFee($firstAmount);
            $this->tax         = $firstAmount - $this->subtotal;
        }

        $this->total       = $recurringAmount;
        $this->firstAmount = $firstAmount;
    }

    public function calcUnique(array $items): void
    {
        $amount                = 0;
        $amountForDiscountCalc = 0;

        foreach ($items as $item) {
            $amount += $item->price();

            if (!$item->isOrderBump()) {
                $amountForDiscountCalc += $item->price();
            }
        }

        $this->discount = $this->coupon
            ? $this->calculateDiscount($this->coupon, $amountForDiscountCalc)
            : 0;

        $this->subtotal = $amount;
        $amount -= $this->discount;

        if ($this->installments) {
            $amount     = $this->getCardFee($amount);
            $this->tax  = $amount - $this->subtotal;
        }

        $this->total       = $amount;
        $this->firstAmount = $amount;
    }

    public function calculate(): void
    {
        $this->total           = 0;
        $this->tax             = 0;
        $this->discount        = 0;

        $product = $this->items[0]->product();

        if ($product->parentProduct->isRecurring) {
            $this->calcRecurring($this->items);
            return;
        }

        $this->calcUnique($this->items);
    }

    private function getCardFee(float $total): float
    {
        $ci = $this->shop->client_id_banking;
        $cs = $this->shop->client_secret_banking;

        $data = (new SuitpayCreditCardService($ci, $cs))->cardFees($total);

        return $data['values']['value' . $this->installments . 'x'];
    }

    public function total(): float
    {
        return $this->total;
    }

    public function firstAmount(): float
    {
        return $this->firstAmount;
    }

    public function subtotal(): float
    {
        return $this->subtotal;
    }

    public function discount(): float
    {
        return $this->discount;
    }

    public function getPrincipalProduct(): Product
    {
        return $this->items[0]->product();
    }

    public function items(): array
    {
        return $this->items;
    }
    public function shop(): Shop
    {
        return $this->shop;
    }

    public function coupon(): ?CouponDiscount
    {
        return $this->coupon;
    }

    public function installments(): ?int
    {
        return $this->installments;
    }

    public function tax(): float
    {
        return $this->tax;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }
}
