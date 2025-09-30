<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class CheckoutPaymentMethodSelector extends Component
{
    public Product $product;

    public function render()
    {
        return view('livewire.checkout-payment-method-selector');
    }
}
