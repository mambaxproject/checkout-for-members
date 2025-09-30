<div class="grid grid-cols-12 gap-6">

    <div class="col-span-12">

        <label for="">Selecione uma opção</label>

        <div class="space-y-2">
            @if (!$product->parentProduct->getValueSchemalessAttributes('paymentMethods') or $product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name))
                <label
                    class="selectPaymentMethod mb-0 w-full cursor-pointer"
                    for="selectCreditCard"
                    wire:click="$dispatch('set-payment-credit-card')"
                >

                    <input
                        class="peer hidden"
                        id="selectCreditCard"
                        name="payment[paymentMethod]"
                        value="{{\App\Enums\PaymentMethodEnum::CREDIT_CARD->name}}"
                        type="radio"
                        onchange="showPaymentMethod('contentCreditCard');checkout.cleanOrderBumps();checkout.setCurrentPaymentMethod()"
                        checked
                    >

                    <div class="content rounded-lg border p-6">

                        <div class="flex items-center gap-4">

                            <div class="radio flex h-5 w-5 items-center justify-center rounded-full border">
                                @include('components.icon', ['icon' => 'check', 'custom' => 'text-xl text-white'])
                            </div>
                            <p>Cartão de crédito</p>

                        </div>

                    </div>

                </label>
            @endif

            @if (!$product->isRecurring and (!$product->parentProduct->getValueSchemalessAttributes('paymentMethods') or $product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::BILLET->name)))
                <label
                    class="selectPaymentMethod mb-0 w-full cursor-pointer"
                    for="selectBankSlip"
                    wire:click="$dispatch('set-payment-billet')"
                >

                    <input
                        class="peer hidden"
                        id="selectBankSlip"
                        name="payment[paymentMethod]"
                        value="{{\App\Enums\PaymentMethodEnum::BILLET->name}}"
                        type="radio"
                        onchange="showPaymentMethod();checkout.cleanOrderBumps();checkout.setCurrentPaymentMethod()"
                        @if($product->parentProduct->getValueSchemalessAttributes('paymentMethods') and !$product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name))
                            checked
                        @endif
                    >

                    <div class="content rounded-lg border p-6">

                        <div class="flex items-center gap-4">

                            <div class="radio flex h-5 w-5 items-center justify-center rounded-full border">
                                @include('components.icon', ['icon' => 'check', 'custom' => 'text-xl text-white'])
                            </div>
                            <p>Boleto bancário</p>

                        </div>

                    </div>

                </label>
            @endif

            @if (!$product->isRecurring and (!$product->parentProduct->getValueSchemalessAttributes('paymentMethods') or $product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::PIX->name)))
                <label
                    class="selectPaymentMethod mb-0 w-full cursor-pointer"
                    for="selectPix"
                    wire:click="$dispatch('set-payment-pix')"
                >

                    <input
                        class="peer hidden"
                        id="selectPix"
                        name="payment[paymentMethod]"
                        value="{{\App\Enums\PaymentMethodEnum::PIX}}"
                        type="radio"
                        onchange="showPaymentMethod();checkout.cleanOrderBumps();checkout.setCurrentPaymentMethod()"

                        @if($product->parentProduct->getValueSchemalessAttributes('paymentMethods') and (!$product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name) and !$product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::BILLET->name)))
                            checked
                        @endif
                    >

                    <div class="content rounded-lg border p-6">

                        <div class="flex items-center gap-4">

                            <div class="radio flex h-5 w-5 items-center justify-center rounded-full border">
                                @include('components.icon', ['icon' => 'check', 'custom' => 'text-xl text-white'])
                            </div>
                            <p>Pix</p>

                        </div>

                    </div>

                </label>
            @endif
        </div>

    </div>

</div>
