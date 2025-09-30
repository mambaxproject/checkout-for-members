<?php

namespace App\Livewire;

use App\Enums\PaymentMethodEnum;
use App\Http\Resources\Api\OrderBumpResource;
use App\Models\Product;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Application;
use Livewire\Attributes\On;
use Livewire\Component;

class CheckoutOrderBumps extends Component
{
    public Product $product;

    public string $paymentMethod = PaymentMethodEnum::PIX->name;

    public function mount()
    {
        $this->paymentMethod = $this->product->parentProduct->getValueSchemalessAttributes('paymentMethods')[0] ?? '';
    }

    #[On('set-payment-credit-card')]
    public function setPaymentMethodCreditCard(): void
    {
        $this->paymentMethod = PaymentMethodEnum::CREDIT_CARD->name;
    }

    #[On('set-payment-pix')]
    public function setPaymentMethodPix(): void
    {
        $this->paymentMethod = PaymentMethodEnum::PIX->name;
    }

    #[On('set-payment-billet')]
    public function setPaymentMethodBillet(): void
    {
        $this->paymentMethod = PaymentMethodEnum::BILLET->name;
    }

    public function render(): View|Factory|Application
    {
        $orderBumps = $this->product
            ->parentProduct
            ->orderBumps()
            ->whereJsonContains('payment_methods', $this->paymentMethod)
            ->with([
                'product:id,name,code',
                'product.media',
                'product_offer:id,name,price,code',
                'product_offer.media',
            ])
            ->active()
            ->get();

        $orderBumps = OrderBumpResource::collection($orderBumps);

        return view('livewire.checkout-order-bumps', compact('orderBumps'));
    }
}
