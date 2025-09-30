@extends('layouts.new-checkout')

@section('content')
    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics_checkout.tag_tracker') }}"
    ></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ config('services.google_analytics_checkout.tag_tracker') }}');
    </script>

    @php
        $hasCreditCard = $product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name);
    @endphp

    @if ($product->unvailableForSales)
        <div class="pointer-events-none fixed left-[calc(50%-55%)] top-[calc(50%-5%)] z-50 flex h-[80px] w-[110%] -rotate-[40deg] items-center justify-center rounded-full bg-black/30 text-xl font-semibold uppercase text-white">Produto não disponível para venda</div>
    @endif

    <div class="mx-auto max-w-4xl md:p-3">

        <div class="space-y-4 rounded-3xl bg-neutral-50 py-4 md:space-y-8 md:py-8">

            <header class="space-y-4 px-4 md:space-y-8 md:px-8">

                @if ($checkoutHorizontalBanner)
                    <figure class="setHorizontalBanner max-h-80 w-full overflow-hidden rounded-xl">
                        <img
                            class="setHorizontalBannerURL h-full w-full object-fill"
                            src="{{ $checkoutHorizontalBanner->original_url }}"
                            alt="{{ $parentProduct->name }}"
                            loading="lazy"
                        >
                    </figure>
                @endif

                <div class="flex items-center gap-4 md:gap-6">

                    <figure class="h-20 w-20 overflow-hidden rounded-xl md:h-28 md:w-28">
                        <img
                            class="h-full w-full object-cover"
                            src="{{ $parentProduct->featuredImageUrl }}"
                            alt="{{ $parentProduct->name }}"
                            loading="lazy"
                        >
                    </figure>

                    <div class="flex-1">

                        <h3 class="text-lg">{{ $parentProduct->name }}</h3>

                        @if ($product->isRecurring)
                            <p class="">
                                @if ($product->hasFirstPayment)
                                    <span class="text-xl font-bold md:text-2xl"> {{ $product->brazilianPriceFirstPayment }}</span>
                                    <span class="text-sm font-bold text-neutral-600">primeiro pagamento</span>
                                @else
                                    <span class="text-xl font-bold md:text-2xl">{{ $product->brazilianPrice }}</span>
                                @endif
                            </p>
                            <p class="">
                                <span class="text-sm text-neutral-600">
                                    <span class="font-bold">Próxima cobrança:</span>
                                    {{ $product->brazilianPrice }} em {{ $product->nextCharge()->format('d/m/Y') }}
                                </span>
                            </p>
                            <p class="">
                                <span class="text-sm text-neutral-600">{{ $product->cyclePaymentTranslated }}</span>
                            </p>
                        @else
                            @if ($hasCreditCard)
                                <p class="">
                                    <span class="max-installments-show text-sm text-neutral-600">{{ $product->parentProduct->maxInstallments }}x</span>
                                    <span class="max-installments-value-show text-xl font-bold md:text-2xl"></span>
                                </p>
                            @endif
                            <p class="text-sm text-neutral-600">{{ $hasCreditCard ? 'ou' : '' }} {{ $product->brazilianPrice }} à vista</p>
                        @endif

                    </div>

                </div>

            </header>

            <main class="space-y-4 px-4 md:space-y-8 md:px-8">

                @if ($checkoutSettings['allowTimer'] ?? false)
                    <div class="timer setTimer rounded-xl p-10">
                        <h3 class="timerTitle text-center text-base font-medium leading-tight">{{ $checkoutSettings['timer_title'] }}</h3>
                        <div class="timerText flex items-center justify-center gap-2">
                            @include('components.icon', [
                                'icon' => 'timer',
                                'custom' => 'text-2xl',
                            ])
                            <p class="text-center text-lg font-semibold">Oferta termina em <span class="showtime"></span></p>
                        </div>
                    </div>
                @endif

                <form
                    class="form form-payment space-y-4 md:space-y-8"
                    action="{{ route('api.public.checkout.pay') }}"
                    method="POST"
                >

                    <div class="flex md:gap-8">

                        @component('components.card', ['custom' => 'p-6 md:p-8 flex-1'])
                            <div class="space-y-6">

                                <h3>Dados pessoais</h3>

                                <div class="grid grid-cols-12 gap-6">

                                    <div class="col-span-12">
                                        <label for="user[name]">Nome completo</label>
                                        <input
                                            class="abandoned-cart-field"
                                            id="user[name]"
                                            name="user[name]"
                                            placeholder="Digite seu nome completo"
                                            minlength="3"
                                            type="text"
                                        />
                                    </div>

                                    <div class="relative col-span-12">

                                        <label for="user[email]">Seu e-mail</label>
                                        <input
                                            class="abandoned-cart-field"
                                            id="user[email]"
                                            placeholder="seuemail@dominio.com.br"
                                            name="user[email]"
                                            onblur="applyCoupon()"
                                            type="email"
                                        />
                                        <div
                                            id="suggestions"
                                            class="hidden"
                                        ></div>

                                    </div>

                                    <div class="col-span-12">
                                        <label for="user[phone_number]">Telefone</label>
                                        <input
                                            class="abandoned-cart-field"
                                            id="user[phone_number]"
                                            name="user[phone_number]"
                                            placeholder="(99) 99999-9999"
                                            maxlength="15"
                                            oninput="setInputMask(this, '(99) 99999-9999')"
                                            type="text"
                                        />
                                    </div>

                                    <div class="col-span-12">
                                        <label for="user[document_number]">CPF</label>
                                        <input
                                            id="user[document_number]"
                                            name="user[document_number]"
                                            placeholder="000.000.000-00"
                                            type="text"
                                            oninput="setCpfMask(this)"
                                        />
                                    </div>

                                    @if ($checkoutSettings['allowCustomField'] ?? 0)
                                        <div
                                            class="col-span-12"
                                            id="setCustomField"
                                        >
                                            <label for="user[customField]">{{ $checkoutSettings['nameCustomField'] ?? '' }}</label>
                                            <input
                                                class="customField"
                                                name="attributes[customField][{{ removeAccents(lcfirst(str_replace(' ', '', ucwords($checkoutSettings['nameCustomField'] ?? '')))) }}]"
                                                placeholder="{{ isset($checkoutSettings['maskCustomField']) ? $checkoutSettings['maskCustomField'] : '' }}"
                                                oninput="{{ isset($checkoutSettings['maskCustomField']) ? 'setInputMask(this, \'' . $checkoutSettings['maskCustomField'] . '\')' : '' }}"
                                                type="{{ $checkoutSettings['typeCustomField'] ?? 'text' }}"
                                                @required($checkoutSettings['requiredCustomField'] ?? 0)
                                            />
                                        </div>
                                    @endif

                                </div>

                            </div>
                        @endcomponent

                        @if ($checkoutVerticalBanner)
                            <div class="setVerticalBanner hidden md:block">

                                <figure class="h-full w-[320px] overflow-hidden rounded-xl lg:block">

                                    <img
                                        class="setVerticalBannerURL h-full w-full object-cover"
                                        src="{{ $checkoutVerticalBanner->original_url }}"
                                        alt="{{ $parentProduct->name }}"
                                        loading="lazy"
                                    >

                                </figure>

                            </div>
                        @endif

                    </div>

                    @component('components.card', ['custom' => 'p-6 md:p-8'])
                        <div class="space-y-6">

                            <h3>Pagamento</h3>

                            <livewire:checkout-payment-method-selector :product="$product"></livewire:checkout-payment-method-selector>

                            <div
                                id="contentCreditCard"
                                class="payment-method {{ !(!$product->parentProduct->getValueSchemalessAttributes('paymentMethods') or $product->parentProduct->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name)) ? 'hidden' : '' }}"
                            >
                                <div class="grid grid-cols-12 gap-6">

                                    <div class="col-span-12">

                                        <label for="payment[cardNumber]">Número do cartão</label>

                                        <input
                                            id="payment[cardNumber]"
                                            name="payment[cardNumber]"
                                            placeholder="0000 0000 0000 0000"
                                            oninput="setInputMask(this, '9999 9999 9999 9999')"
                                            type="text"
                                            inputmode="numeric"
                                        />
                                    </div>

                                    <div class="col-span-12">
                                        <label for="payment[cardHolderName]">Nome completo</label>
                                        <input
                                            id="payment[cardHolderName]"
                                            name="payment[cardHolderName]"
                                            placeholder="JOÃO PEDRO CARDOSO"
                                            type="text"
                                        />
                                    </div>

                                    <div class="col-span-6">
                                        <label for="payment[cardExpiration]">Data de Expiração</label>
                                        <input
                                            id="payment[cardExpiration]"
                                            name="payment[cardExpiration]"
                                            placeholder="00/00"
                                            type="text"
                                        />
                                    </div>

                                    <div class="col-span-6">
                                        <label for="payment[cardCvv]">CVV</label>
                                        <input
                                            id="payment[cardCvv]"
                                            name="payment[cardCvv]"
                                            placeholder="CVV"
                                            type="text"
                                            inputmode="numeric"
                                            onkeyup="this.value = this.value.replace(/[^0-9]/g, '')"
                                            maxlength="3"
                                        />
                                    </div>

                                    <div class="{{ $product->isRecurring ? 'hidden' : '' }} col-span-12">
                                        <label for="payment[installments]">Parcelas</label>
                                        <select
                                            class="installments"
                                            id="payment[installments]"
                                            name="payment[installments]"
                                        >
                                            <option
                                                selected
                                                value="1"
                                            >1 x 100</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

                        </div>
                    @endcomponent

                    <livewire:checkout-order-bumps :product="$product"></livewire:checkout-order-bumps>

                    @component('components.card', ['custom' => 'p-6 md:p-8'])
                        <div class="space-y-6">

                            <h3>Detalhes da compra</h3>

                            @if ($checkoutSettings['allowCouponsDiscounts'] ?? 0)
                                <div class="setCupom">

                                    <label for="">Insira seu cupom de desconto</label>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input
                                                class="coupon_field h-10 md:h-12"
                                                name="coupon_code"
                                                placeholder="Ex: CUPOM19"
                                                type="text"
                                            />
                                            <p class="coupon_field_feedback"></p>
                                        </div>

                                        <button
                                            class="button button-primary h-10 rounded-full md:h-12"
                                            type="button"
                                            onclick="applyCoupon()"
                                        >
                                            Aplicar cupom
                                        </button>
                                    </div>

                                </div>
                            @endif

                            <div class="">

                                <ul class="products-list"></ul>

                                <hr class="my-3 border-neutral-200/80">

                                <ul>
                                    <li class="flex items-center justify-between">
                                        <span>Subtotal:</span>
                                        <span class="subtotal currency"></span>
                                    </li>
                                    <li class="discount-line flex items-center justify-between">
                                        <span>Desconto:</span>
                                        <span class="discount currency text-primary"></span>
                                    </li>
                                </ul>

                                <hr class="my-3 border-neutral-400">

                                <ul>
                                    <li class="flex items-center justify-between">
                                        <span class="font-bold">Total:</span>
                                        <span class="total font-bold"></span>
                                    </li>
                                </ul>

                            </div>

                        </div>
                    @endcomponent

                    @unless ($product->unvailableForSales)
                        <button
                            class="paymentSubmitButton button setButtonColor setButtonTextColor h-12 w-full rounded-full"
                            type="submit"
                        >
                            Pagar e receber agora
                        </button>
                    @endunless

                </form>

                @if ($checkoutSettings['testimonial'] ?? false)
                    @component('components.card', ['custom' => 'setTestimonials p-6 md:p-8'])
                        <div class="space-y-6">

                            <div class="flex items-center justify-between">
                                <h3>Depoimentos</h3>
                            </div>

                            <ul
                                class="divide-y divide-neutral-100 md:p-4 lg:p-8"
                                id="list"
                            >
                                @foreach (json_decode($checkoutSettings['testimonials'] ?? '{}') as $testimonial)
                                    <li class="group flex items-center py-2 first:pt-0 last:pb-0 md:py-8">
                                        <div class="flex items-start gap-4">

                                            <figure class="relative h-16 w-16 overflow-hidden rounded-full bg-neutral-200">
                                                <img
                                                    class="absolute h-full w-full object-cover"
                                                    src="{{ $testimonial->img }}"
                                                    alt="{{ $testimonial->name }}"
                                                    loading="lazy"
                                                >
                                            </figure>

                                            <div class="flex-1">
                                                <p class="mb-2">{{ $testimonial->description }}</p>
                                                <h4 class="font-semibold">{{ $testimonial->name }}</h4>

                                                <ul class="flex items-center gap-px">
                                                    {!! generateStars($testimonial->starsCount) !!}
                                                </ul>
                                            </div>

                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    @endcomponent
                @endif

            </main>

            <footer class="space-y-4 px-4 md:space-y-8 md:px-8">

                @if ($checkoutSettings['hasSecurePurchaseSeal'] ?? 0 or $checkoutSettings['hasPrivacySeal'] ?? 0)
                    <div class="notSeal">
                        @component('components.card', ['custom' => 'p-6 md:p-8'])
                            <div class="flex items-center justify-center gap-8">

                                @if ($checkoutSettings['hasSecurePurchaseSeal'] ?? 0)
                                    <div class="setSecurePurchaseSeal">
                                        <div class="flex items-center gap-1">

                                            @include('components.icon', [
                                                'icon' => 'verified_user',
                                                'custom' => 'textPrimaryColor text-4xl md:text-5xl',
                                            ])
                                            <p class="flex flex-col">
                                                <span class="text-xs uppercase md:text-sm">Compra</span>
                                                <span class="text-xs font-semibold uppercase md:text-sm">100% Segura</span>
                                            </p>

                                        </div>
                                    </div>
                                @endif

                                @if ($checkoutSettings['hasPrivacySeal'] ?? 0)
                                    <div class="setPrivacySeal">

                                        <div class="flex items-center gap-1">
                                            @include('components.icon', [
                                                'icon' => 'encrypted',
                                                'custom' => 'textPrimaryColor text-4xl md:text-5xl',
                                            ])
                                            <p class="flex flex-col">
                                                <span class="text-xs uppercase md:text-sm">Privacidade</span>
                                                <span class="text-xs font-semibold uppercase md:text-sm">Protegida</span>
                                            </p>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        @endcomponent
                    </div>
                @endif

                <div class="">

                    <h4 class="mb-3">Precisa de ajuda?</h4>
                    <ul>
                        <li>
                            <div class="flex items-center gap-2">
                                @include('components.icon', [
                                    'icon' => 'account_circle',
                                    'custom' => 'text-lg text-gray-400',
                                ])
                                <span class="text-xs md:text-sm">{{ $product->parentProduct->getValueSchemalessAttributes('nameShop') ?? '-' }}</span>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center gap-2">
                                @include('components.icon', [
                                    'icon' => 'link',
                                    'custom' => 'text-lg text-gray-400',
                                ])
                                <a
                                    class="textPrimaryColor flex items-center gap-2 text-xs font-medium md:text-sm"
                                    href="{{ $product->parentProduct->getValueSchemalessAttributes('externalSalesLink') ?? '-' }}"
                                    target="blank"
                                >
                                    {{ $product->shop->name ?? '' }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center gap-2">
                                @include('components.icon', [
                                    'icon' => 'mail',
                                    'custom' => 'text-lg text-gray-400',
                                ])
                                <a
                                    class="textPrimaryColor text-xs font-medium md:text-sm"
                                    href="#"
                                >
                                    {{ $product->parentProduct->getValueSchemalessAttributes('emailSupport') ?? '-' }}
                                </a>
                            </div>
                        </li>
                    </ul>

                    <hr class="my-4 border-gray-200">

                    <p class="linkPrimaryColor text-xs md:text-sm [&_>_a]:font-medium">Ao clicar em “<strong>Pagar e receber agora</strong>”,
                        declaro que li e concordo que a SuitPay
                        está processando este pedido em nome de <strong>{{ $product->shop->name }}</strong>
                        e não possuí responsabilidade pelo conteúdo e/ou faz controle prévio deste, assim como está previsto nos
                        <a
                            href="https://web.suitpay.app/static/pdf/TERMOS_E_CONDICOES.pdf"
                            target="_blank"
                            rel="noopener noreferrer"
                        >Termos de Uso</a> e
                        <a
                            href="https://web.suitpay.app/static/pdf/POLITICA_DE_PRIVACIDADE_E_PROTECAO_DE_DADOS.pdf"
                            target="_blank"
                            rel="noopener noreferrer"
                        >Política de Privacidade</a> da SuitPay. – Em caso de dúvidas, visite nosso
                        <a
                            href="https://intercom.help/suitsales/pt-BR/"
                            target="_blank"
                            rel="noopener noreferrer"
                        >Help Center.</a>
                        <br><br> SuitPay © <?= date('Y') ?> – Todos os direitos reservados.
                    </p>

                </div>

            </footer>

        </div>

    </div>

    {{-- Messages --}}
    <div
        class="fixed left-0 top-0 hidden h-screen w-screen items-center justify-center bg-neutral-950/90"
        id="isProcessingPayment"
    >
        <div class="space-y-2 text-center">
            @include('components.icon', [
                'icon' => 'progress_activity',
                'custom' => 'animate-spin text-primary text-3xl',
            ])
            <h4 class="text-neutral-300"></h4>
        </div>
    </div>
    <div
        class="fixed left-0 top-0 hidden h-screen w-screen items-center justify-center bg-neutral-950/90"
        id="paymentErrorMessage"
    >
        <button
            class="closeButtonModalCheckout absolute right-8 top-6"
            type="button"
        >
            @include('components.icon', [
                'icon' => 'close',
                'custom' => 'text-neutral-300 text-3xl',
            ])
        </button>

        <div class="space-y-2 text-center">
            @include('components.icon', [
                'icon' => 'close',
                'custom' => 'text-danger-500 text-3xl',
            ])
            <h4 class="text-neutral-300">a</h4>
        </div>
    </div>
    <div
        class="fixed left-0 top-0 hidden h-screen w-screen items-center justify-center bg-neutral-950/90"
        id="serverErrorMessage"
    >
        <button
            class="closeButtonModalCheckout absolute right-8 top-6"
            type="button"
        >
            @include('components.icon', [
                'icon' => 'close',
                'custom' => 'text-neutral-300 text-3xl',
            ])
        </button>

        <div class="space-y-2 text-center">
            @include('components.icon', [
                'icon' => 'close',
                'custom' => 'text-danger-500 text-3xl',
            ])
            <h4 class="text-neutral-300"></h4>
        </div>
    </div>
    <div
        class="fixed left-0 top-0 hidden h-screen w-screen items-center justify-center bg-neutral-950/90"
        id="validationErrorStatus"
    >
        <button
            class="closeButtonModalCheckout absolute right-8 top-6"
            type="button"
        >
            @include('components.icon', [
                'icon' => 'close',
                'custom' => 'text-neutral-300 text-3xl',
            ])
        </button>

        <div class="space-y-2 text-center">
            @include('components.icon', [
                'icon' => 'close',
                'custom' => 'text-danger-500 text-3xl',
            ])
            <h4 class="text-neutral-300"></h4>
        </div>
    </div>
@endsection

@section('style')
    <style id="customCheckoutStyle">
        #backgroundColor {
            background-color: {{ $checkoutSettings['backgroundColor'] ?? '#fafafa' }};
        }

        .textPrimaryColor,
        .linkPrimaryColor a {
            color: {{ $checkoutSettings['primaryColor'] ?? '#33cc33' }};
        }

        .textDangerColor {
            color: #ff2a3e;
        }

        .setButtonColor {
            background-color: {{ $checkoutSettings['backgroundButtonColor'] ?? '#33cc33' }};
            ;
        }

        .setButtonTextColor {
            color: {{ $checkoutSettings['textButtonColor'] ?? '#ffffff' }};
            ;
        }

        .selectPaymentMethod input:checked~.content {
            border-color: {{ $checkoutSettings['primaryColor'] ?? '#33cc33' }};
        }

        .selectPaymentMethod input:checked~.content .radio {
            background-color: {{ $checkoutSettings['primaryColor'] ?? '#33cc33' }};
            border-color: {{ $checkoutSettings['primaryColor'] ?? '#33cc33' }};
        }
    </style>
    <style>
        #suggestions,
        #suggestions-confirmation {
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
            z-index: 10;
        }

        .suggestion {
            padding: 8px;
            cursor: pointer;
        }

        .suggestion:hover {
            background-color: #f0f0f0;
        }

        .timer {
            background-color: {{ $checkoutSettings['background_timer_color'] ?? '#ff4d4d' }};
        }

        .timerTitle {
            color: {{ $checkoutSettings['title_timer_color'] ?? '#ffffff' }};
            font-size: {{ $checkoutSettings['size_title_timer'] ?? '16px' }};
        }

        .timerText {
            color: {{ $checkoutSettings['text_timer_color'] ?? '#fff700' }};
        }
    </style>
@endsection

@section('script')
    <script>
        function detectCardBrand(cardNumber) {
            const cleanNumber = cardNumber.replace(/\D/g, '');

            if (/^3[47]/.test(cleanNumber)) { // American Express: começa com 34 ou 37
                return 'amex';
            }
        }

        function updateCvvMask(cardBrand) {
            const cvvInput = document.getElementById('payment[cardCvv]');

            if (!cvvInput)
                return;

            const placeholder = cardBrand === 'amex' ? 'CVVV' : 'CVV';
            const maxLength = cardBrand === 'amex' ? '4' : '3';

            cvvInput.setAttribute('placeholder', placeholder);
            cvvInput.setAttribute('maxlength', maxLength);

            cvvInput.value = '';
        }

        function updateCardNumberMask(cardNumber) {
            const cardNumberInput = document.getElementById('payment[cardNumber]');

            if (!cardNumberInput)
                return;

            const cardBrand = detectCardBrand(cardNumber);

            const placeholder = cardBrand === 'amex' ? '0000 000000 00000' : '9999 9999 9999 9999';
            const mask = cardBrand === 'amex' ? '9999 999999 99999' : '9999 9999 9999 9999';

            cardNumberInput.setAttribute('placeholder', placeholder);
            setInputMask(cardNumberInput, mask);
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cardExpirationInput = document.getElementById("payment[cardExpiration]");
            const cardNumberInput = document.getElementById('payment[cardNumber]');

            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function() {
                    const cardBrand = detectCardBrand(this.value);
                    updateCvvMask(cardBrand);

                    updateCardNumberMask(cardNumberInput.value);
                });
            }

            setInputMask(cardExpirationInput, '99/99');

            cardExpirationInput.addEventListener("blur", function() {
                const value = cardExpirationInput.value;

                if (!/^\d{2}\/\d{2}$/.test(value)) {
                    notyf.error("Data de expiração inválida!");
                    cardExpirationInput.classList.add("ring-danger-500");
                    cardExpirationInput.value = "";
                    return;
                }

                const [month, year] = value.split("/").map(Number);
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear() % 100;
                const currentMonth = currentDate.getMonth() + 1;

                if (month < 1 || month > 12) {
                    cardExpirationInput.value = "";
                    cardExpirationInput.classList.add("ring-danger-500");
                    notyf.error("Mês inválido!");
                    return;
                }

                if (year < currentYear || (year === currentYear && month < currentMonth)) {
                    notyf.error("A data de validade do cartão já expirou!");
                    cardExpirationInput.classList.add("ring-danger-500");
                    cardExpirationInput.value = "";
                }

                cardExpirationInput.classList.remove("ring-danger-500");
            });
        });
    </script>

    <script>
        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }

            return null;
        }
    </script>

    <script>
        function formatTimeFromMinutes(minutes) {
            const hrs = Math.floor(minutes / 60);
            const mins = Math.floor(minutes % 60);
            const secs = Math.floor((minutes % 1) * 60);

            return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        let purchaseDetails = {
            totalElement: $('.total'),
            subtotalElement: $('.subtotal'),
            discountElement: $('.discount'),
            productsListElement: $('.products-list'),
            currency: function(value) {
                return parseFloat(value).toLocaleString("pt-BR", {
                    style: "currency",
                    currency: "BRL"
                });
            },
            updateView: function(checkout) {
                this.productsListElement.html('')
                checkout.items.forEach(function(product) {
                    purchaseDetails.productsListElement.append(`
                    <li class="flex items-center justify-between">
                        <span>` + product.name + `</span>
                        <span>` + purchaseDetails.currency(product.has_first_payment ? product.priceFirstPayment : (product.price ?? product.promotional_price)) + `</span>
                    </li>
                `)
                })

                this.totalElement.html(this.currency(checkout.total))
                this.subtotalElement.html(this.currency(checkout.subtotal))
                this.discountElement.html(this.currency(checkout.discount))

                if (!$('.coupon_field').length) {
                    $('.discount-line').addClass('hidden')
                }
            }
        }

        function showModalCheckout(id, message) {
            const targetEl = document.getElementById(id);
            const messageContainer = document.querySelector(`#${id} h4`);
            console.log(messageContainer);


            $('html').css('overflow', 'hidden');
            $(targetEl).removeClass('hidden').addClass('flex');

            messageContainer.innerHTML = message;

            // Adicionando evento de clique para fechar o modal
            const closeButton = targetEl.querySelector('.closeButtonModalCheckout');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    $(targetEl).removeClass('flex').addClass('hidden');
                    $('html').css('overflow', 'auto');
                });
            }
        }

        let checkout = {
            timerMinutes: {{ $checkoutSettings['timer_timer'] ?? 0 }},
            parentProductCode: '{{ $product->parentProduct->code }}',
            principalProductId: '{{ $product->parent_id }}',
            maxInstallments: {{ $product->parentProduct->maxInstallments }},
            shop_id: '{{ $product->shop_id }}',
            currentPaymentMethod: document.querySelector('input[name="payment[paymentMethod]"]').value,
            total: 0,
            subtotal: 0,
            totalWithoutOrderBump: 0,
            discount: 0,
            fee: 0,
            items: [],
            currentCoupon: null,
            setCurrentPaymentMethod: function() {
                this.currentPaymentMethod = document.querySelector('input[name="payment[paymentMethod]"]:checked').value;

                @if ($checkoutSettings['allowCouponsDiscounts'] ?? 0)
                    if (!checkCurrentCouponPaymentMethod()) {
                        checkAutoCoupon(checkout.principalProductId);
                        applyCoupon()
                    }
                @endif
            },
            calc: function() {
                this.total = 0;
                this.totalWithoutOrderBump = 0;

                this.items.forEach(function(product) {
                    let value = product.has_first_payment ? parseFloat(product.priceFirstPayment) : parseFloat(product.price ?? product.promotional_price);

                    if (!product.isOrderBump) {
                        checkout.totalWithoutOrderBump += value;
                    }

                    checkout.total += value
                });

                this.subtotal = this.total;
                this.total -= this.discount

                this.getInstallments()

                this.total += this.fee;
            },
            cleanOrderBumps: function() {
                let itemsToRemove = this.items.filter((item) => item.isOrderBump);
                itemsToRemove.forEach(function(element) {
                    checkout.removeProduct(element, true)
                })

                $('.order-bump').prop('checked', false);
            },
            addProduct: function(product, isOrderBump = false, quantity = 1) {
                if (isOrderBump) product.isOrderBump = true;

                product.quantity = quantity;
                this.items.push(product)

                this.calc()

                purchaseDetails.updateView(checkout)
            },
            removeProduct: function(product, isOrderBump = false) {
                let findProductIndex = isOrderBump ? ((element) => (element.id === product.id && element.isOrderBump)) :
                    ((element) => (element.id === product.id));
                let index = this.items.findIndex(findProductIndex);
                this.items.splice(index, 1)
                this.calc()
                purchaseDetails.updateView(this)
            },
            getInstallments: function() {

                $('.installments').html('<option> loading... </option>')

                $.ajax({
                    headers: {
                        'x-csrf-token': '{{ csrf_token() }}'
                    },
                    method: 'get',
                    url: '{{ route('api.public.checkout.cardInstallments') }}',
                    data: {
                        value: this.total,
                        shop_id: this.shop_id
                    },
                    success: function(data, textStatus, xhr) {
                        @if (!$product->isRecurring)
                            $('.installments').html('')
                            data.formatted.forEach(function(item, index) {
                                if (index < parseInt(checkout.maxInstallments)) {
                                    $('.installments')
                                        .append(`<option ` + (index === (checkout.maxInstallments - 1) ? 'selected' : '') + ` value="` + (index + 1) + `" data-total="` + item.total + `" >` + item.text + `</option>`);
                                }
                            });

                            let checkoutMaxInstallments = checkout.maxInstallments > data.formatted.length ? data.formatted.length : checkout.maxInstallments;

                            $('.max-installments-value-show').html(purchaseDetails.currency(data.installments['installment' + checkoutMaxInstallments + 'x']))
                            $('.max-installments-show').html(checkoutMaxInstallments + " x ")
                        @endif
                    },
                    error: function(data, textStatus, xhr) {
                        console.error(data)
                    }
                })
            },
            submit: function() {

                let currentCardData = null;
                const selectedPaymentMethod = document.querySelector('input[name="payment[paymentMethod]"]:checked');
                if (selectedPaymentMethod && selectedPaymentMethod.value === 'CREDIT_CARD') {
                    currentCardData = getCurrentCardData();
                }

                let products = this.items.map(function(item) {
                    return {
                        id: item.id.toString(),
                        name: item.name,
                        quantity: item.quantity,
                        value: item.price,
                    }
                });

                trackGoogleAdsPurchase(
                    '{{ config('services.google_analytics_checkout.tag_tracker') }}',
                    products,
                    products.length,
                    this.total,
                    null,
                    checkout.currentPaymentMethod,
                );

                let affiliateCode = (new URLSearchParams(window.location.search)).get('afflt');
                let form = Object.fromEntries(new FormData(document.querySelector('.form')).entries())
                form.shop_id = this.shop_id;

                if (!checkout.currentCoupon) {
                    form.coupon_code = null
                }

                if (affiliateCode) {
                    form.affiliate_code = affiliateCode;
                }

                form.items = this.items.map(function(item) {
                    let data = {
                        'quantity': 1,
                    }

                    if (item.isOrderBump) {
                        data.order_bump_id = item.id
                        data.product_id = null
                    } else {
                        data.order_bump_id = null
                        data.product_id = item.id
                    }

                    return data
                });

                urlParams = new URLSearchParams(window.location.search)
                const utmParams = {};

                if (urlParams.has('abId')) utmParams.abId = urlParams.get('abId');
                if (urlParams.has('utm_source')) utmParams.source = urlParams.get('utm_source');
                if (urlParams.has('utm_campaign')) utmParams.campaign = urlParams.get('utm_campaign');
                if (urlParams.has('utm_medium')) utmParams.medium = urlParams.get('utm_medium');
                if (urlParams.has('utm_content')) utmParams.content = urlParams.get('utm_content');
                if (urlParams.has('utm_term')) utmParams.term = urlParams.get('utm_term');
                if (urlParams.has('sck')) utmParams.sck = urlParams.get('sck');
                if (urlParams.has('src')) utmParams.src = urlParams.get('src');

                if (Object.keys(utmParams).length > 0) {
                    form.utm = utmParams;
                }

                $.ajax({
                    headers: {
                        'x-csrf-token': '{{ csrf_token() }}'
                    },
                    method: 'POST',
                    url: '{{ route('api.public.checkout.pay') }}',
                    data: form,
                    beforeSend: function() {
                        $('.form-payment button').addClass('hidden').prop('disabled', true);
                        showModalCheckout('isProcessingPayment', 'Olá, estamos processando seu pedido. Por favor aguarde!');
                    },
                    success: function(data, textStatus, xhr) {

                        if (!['FAILED', 'CANCELED', 'UNPAID', 'CHARGEBACK', 'WAITING_FOR_APPROVAL'].includes(data.payment_status)) {

                            removeCardDataFromLocalStorage();

                            if (checkout.currentPaymentMethod === '{{ \App\Enums\PaymentMethodEnum::PIX->name }}') {
                                document.location = 'checkout/thanks/' + data.order_hash;
                            } else {
                                document.location = data.thanks_page;
                            }

                        } else {
                            if (currentCardData)
                                saveCardDataToLocalStorage(currentCardData);

                            $("#isProcessingPayment").addClass('hidden');
                            showModalCheckout('paymentErrorMessage', 'Não foi possível completar a compra. Tente novamente!');
                        }
                    },
                    error: function(data, textStatus, xhr) {
                        if (currentCardData)
                            saveCardDataToLocalStorage(currentCardData);

                        $('.form-payment button').removeClass('hidden').prop('disabled', false);
                        if (data.status == '422') {
                            $("#isProcessingPayment").addClass('hidden');
                            const errors = Object.values(data.responseJSON.errors).map(error => error[0]);
                            showModalCheckout('serverErrorMessage', errors);
                        } else {
                            $("#isProcessingPayment").addClass('hidden');
                            showModalCheckout('serverErrorMessage', 'Internal serve error');
                        }
                    },
                    complete: function() {
                        $('.form-payment button').removeClass('hidden').prop('disabled', false);
                    }
                })
            }
        }

        $('.currency').html(parseFloat($('.currency').html()).toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
        }))

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector(".form-payment");
            const nameInput = document.querySelector("input[name='user[name]']");
            const emailInput = document.querySelector("input[name='user[email]']");
            const phoneInput = document.querySelector("input[name='user[phone_number]']");
            const documentInput = document.querySelector("input[name='user[document_number]']");
            const customField = document.querySelector('.customField');
            const paymentSubmitButton = document.querySelector(".paymentSubmitButton");

            function setValidationStyle(input, isValid) {
                input.classList.remove("is-valid", "is-invalid");
                input.classList.add(isValid ? "is-valid" : "is-invalid");
            }

            function validateName() {
                const isValid = nameInput.value.trim().length >= 3;
                setValidationStyle(nameInput, isValid);
                return isValid;
            }

            function validateEmail() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const isValid = emailRegex.test(emailInput.value.trim());
                setValidationStyle(emailInput, isValid);
                return isValid;
            }

            function validatePhone() {
                const phoneRegex = /^\(\d{2}\) \d{5}-\d{4}$/;
                const isValid = phoneRegex.test(phoneInput.value.trim());
                setValidationStyle(phoneInput, isValid);
                return isValid;
            }

            function validateCPF(cpf) {
                cpf = cpf.replace(/\D/g, '');
                if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
                let sum = 0,
                    remainder;
                for (let i = 0; i < 9; i++) sum += parseInt(cpf.charAt(i)) * (10 - i);
                remainder = (sum * 10) % 11;
                if (remainder >= 10) remainder = 0;
                if (remainder !== parseInt(cpf.charAt(9))) return false;
                sum = 0;
                for (let i = 0; i < 10; i++) sum += parseInt(cpf.charAt(i)) * (11 - i);
                remainder = (sum * 10) % 11;
                if (remainder >= 10) remainder = 0;
                return remainder === parseInt(cpf.charAt(10));
            }

            function validateDocument() {
                let doc = documentInput.value.replace(/\D/g, '');
                const isValid = validateCPF(doc);
                setValidationStyle(documentInput, isValid);
                return isValid;
            }

            function validateCustomField() {
                const customField = document.querySelector('.customField');

                if (customField) {
                    const isValid = customField.value.trim() !== '';

                    if (customField.required) {
                        if (!isValid) {
                            customField.classList.remove("is-valid");
                            customField.classList.add("is-invalid");
                        } else {
                            customField.classList.remove("is-invalid");
                            customField.classList.add("is-valid");
                        }
                        return isValid;
                    } else {
                        customField.classList.remove("is-invalid");
                        customField.classList.toggle("is-valid", isValid);
                        return true;
                    }
                }

                return true;
            }

            if (form) {
                // Adicionando eventos 'input' para validação dinâmica
                nameInput.addEventListener("input", validateName);
                emailInput.addEventListener("input", validateEmail);
                phoneInput.addEventListener("input", validatePhone);
                documentInput.addEventListener("input", validateDocument);

                if (customField) {
                    customField.addEventListener("input", validateCustomField);
                }

                paymentSubmitButton.addEventListener("click", function(event) {
                    event.preventDefault();

                    const isNameValid = validateName();
                    const isEmailValid = validateEmail();
                    const isPhoneValid = validatePhone();
                    const isDocumentValid = validateDocument();
                    const isCustomField = validateCustomField();

                    // Verificação de cartão recusado para evitar múltiplas tentativas com o mesmo cartão
                    const selectedPaymentMethod = document.querySelector('input[name="payment[paymentMethod]"]:checked');
                    if (selectedPaymentMethod && selectedPaymentMethod.value === 'CREDIT_CARD') {
                        const currentCardData = getCurrentCardData();

                        const previousCardData = getPreviousCardDataFromLocalStorage();

                        if (previousCardData && isSameCard(currentCardData, previousCardData)) {
                            showModalCheckout('paymentErrorMessage', 'A tentativa anterior de pagamento com este cartão foi recusada. Por favor, tente com outro cartão de crédito.');
                            return;
                        }
                    }

                    if (!isNameValid) {
                        notyf.error("Por favor, insira um nome válido com pelo menos 3 caracteres.");
                    }
                    if (!isEmailValid) {
                        notyf.error("Por favor, insira um e-mail válido.");
                    }
                    if (!isPhoneValid) {
                        notyf.error("Por favor, insira um número de telefone válido.");
                    }
                    if (!isDocumentValid) {
                        notyf.error("Por favor, insira um CPF válido.");
                    }
                    if (!isCustomField) {
                        notyf.error("Por favor, insira um valor válido.");
                    }

                    if (isNameValid && isEmailValid && isPhoneValid && isDocumentValid && isCustomField) {
                        checkout.submit();
                    } else {
                        const offset = 40;
                        const formPosition = form.getBoundingClientRect().top + window.scrollY - offset;
                        window.scrollTo({
                            top: formPosition,
                            behavior: "smooth"
                        });
                    }
                });
            }
        });

        $(document).ready(function() {
            checkout.addProduct(@json($product))

            $('.form input.abandoned-cart-field').blur(function() {
                createAbandonedCart()

                let form = new FormData(document.querySelector('.form'))
                if (form.get('user[name]') && form.get('user[email]') && form.get('user[phone_number]')) {
                    trackFacebookContact(form.get('user[name]'), form.get('user[email]'), form.get('user[phone_number]'))
                }
            });

            @if ($checkoutSettings['allowCouponsDiscounts'] ?? 0)
                checkAutoCoupon(checkout.principalProductId);
            @endif

            let afflt_code = (new URLSearchParams(window.location.search)).get('afflt')
            let afflt_code_cookie = getCookie('afflt_code_' + checkout.parentProductCode)

            if (!afflt_code && afflt_code_cookie) {
                const url = new URL(window.location.href);
                url.searchParams.set('afflt', afflt_code_cookie);
                window.history.pushState({}, '', url);
            }
        })

        window.addEventListener('load', function() {
            // evita bug CS-525
            document.querySelector('.selectPaymentMethod').click()
        })
    </script>

    <script>
        setInterval(function() {
            let time = (checkout.timerMinutes -= 0.01) < 0 ? 0 : checkout.timerMinutes
            $('.showtime').html(formatTimeFromMinutes(time))
        }, 1000)
    </script>

    <script>
        const emailInput = document.getElementById('user[email]');
        const suggestionsContainerEmail = document.getElementById('suggestions');
        const commonDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'icloud.com'];

        function handleInput(inputElement, suggestionsContainer) {
            inputElement.addEventListener('input', function() {
                const emailValue = inputElement.value;
                const atIndex = emailValue.indexOf('@');

                // Verificar se existe um '@' na entrada
                if (atIndex > -1) {
                    const inputBeforeAt = emailValue.substring(0, atIndex + 1); // Parte antes do '@'
                    const inputAfterAt = emailValue.substring(atIndex + 1); // Parte depois do '@'

                    // Filtrar domínios comuns que comecem com o que foi digitado após o '@'
                    const filteredDomains = commonDomains.filter(domain => domain.startsWith(inputAfterAt));

                    // Mostrar as sugestões se houver algum domínio filtrado
                    if (filteredDomains.length > 0) {
                        suggestionsContainer.innerHTML = filteredDomains
                            .map(domain => `<div class="suggestion">${inputBeforeAt}${domain}</div>`)
                            .join('');
                        suggestionsContainer.classList.remove('hidden');
                    } else {
                        suggestionsContainer.classList.add('hidden');
                    }
                } else {
                    suggestionsContainer.classList.add('hidden');
                }
            });

            // Selecionar a sugestão quando clicada
            suggestionsContainer.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('suggestion')) {
                    inputElement.value = e.target.textContent;
                    suggestionsContainer.classList.add('hidden');
                }
            });

            // Fechar a lista de sugestões se clicar fora do input ou da lista
            document.addEventListener('click', function(e) {
                if (!suggestionsContainer.contains(e.target) && e.target !== inputElement) {
                    suggestionsContainer.classList.add('hidden');
                }
            });
        }

        // Aplicar a função de autocompletar nos dois campos
        handleInput(emailInput, suggestionsContainerEmail);
    </script>

    <script>
        let productUnvailableForSales = @json($product->unvailableForSales);
        if (productUnvailableForSales) {
            document.querySelectorAll('.form-payment input, .form-payment select').forEach(element => element.disabled = true);
        }
    </script>

    <script>
        document.querySelector('#user\\[name\\], #user\\[email\\], #user\\[phone_number\\], #user\\[document_number\\]').addEventListener('focus', function() {
            let products = checkout.items.map(function(item) {
                return {
                    id: item.id.toString(),
                    name: item.name,
                    quantity: item.quantity,
                    value: item.price,
                }
            });

            trackGoogleBeginCheckout(
                '{{ config('services.google_analytics_checkout.tag_tracker') }}',
                products,
                products.length,
                checkout.total,
                null,
                checkout.currentPaymentMethod,
            );
        }, {
            once: true
        });
    </script>

    @includeWhen(isset($appsShop['google-analytics']), 'partials.checkout.apps.google-analytics')

    <script>
        function getCurrentCardData() {
            const cardNumberInput = document.getElementById("payment[cardNumber]");
            const cardHolderNameInput = document.getElementById("payment[cardHolderName]");
            const cardExpirationInput = document.getElementById("payment[cardExpiration]");
            const cardCvvInput = document.getElementById("payment[cardCvv]");

            return {
                number: cardNumberInput ? cardNumberInput.value.replace(/\s/g, '') : '',
                name: cardHolderNameInput ? cardHolderNameInput.value.trim() : '',
                expiration: cardExpirationInput ? cardExpirationInput.value.trim() : '',
                cvv: cardCvvInput ? cardCvvInput.value.trim() : ''
            };
        }

        function serializeCardData(cardData) {
            if (!cardData)
                return null;

            const maskedNumber = cardData.number.length > 4 ?
                '*'.repeat(cardData.number.length - 4) + cardData.number.slice(-4) :
                cardData.number;

            const maskedCVV = cardData.cvv ? '*'.repeat(cardData.cvv.length) : '';

            const serializedData = {
                nonce: btoa(Date.now().toString()),
                hash: btoa(cardData.number + cardData.name),
                data: {
                    n: maskedNumber,
                    h: cardData.name,
                    e: cardData.expiration,
                    c: maskedCVV
                },

                fingerprint: btoa(
                    cardData.number.slice(-4) +
                    cardData.name.replace(/\s+/g, '').toLowerCase() +
                    cardData.expiration.replace(/\D/g, '')
                )
            };

            return JSON.stringify(serializedData);
        }

        function deserializeCardData(serializedData) {
            if (!serializedData)
                return null;

            try {
                const parsedData = JSON.parse(serializedData);

                return {
                    number: parsedData.data.n,
                    name: parsedData.data.h,
                    expiration: parsedData.data.e,
                    cvv: parsedData.data.c,
                    fingerprint: parsedData.fingerprint
                };
            } catch (e) {
                console.error('Erro ao deserializar dados do cartão:', e);
                return null;
            }
        }

        function saveCardDataToLocalStorage(cardData) {
            if (!cardData)
                return;

            const serializedData = serializeCardData(cardData);

            localStorage.setItem('lastFailedCardData', serializedData);
        }

        function getPreviousCardDataFromLocalStorage() {
            const serializedData = localStorage.getItem('lastFailedCardData');

            return deserializeCardData(serializedData);
        }

        function isSameCard(card1, card2) {
            if (!card1 || !card2) return false;

            if (card1.fingerprint && card2.fingerprint) {
                return card1.fingerprint === card2.fingerprint;
            }

            return card1.number.slice(-4) === card2.number.slice(-4) &&
                card1.name.toLowerCase().replace(/\s+/g, '') === card2.name.toLowerCase().replace(/\s+/g, '') &&
                card1.expiration === card2.expiration;
        }

        function removeCardDataFromLocalStorage() {
            localStorage.removeItem('lastFailedCardData');
        }
    </script>
@endsection
