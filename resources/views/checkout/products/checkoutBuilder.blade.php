@extends('layouts.checkout')

@section('content')
    <div class="mx-auto max-w-4xl">
        <div class="rounded-3xl bg-neutral-100 p-4 md:p-8">
            <div class="space-y-4 md:space-y-6">
                @if (isset($product->id) && $product->offers->count() > 0)
                    @php
                        $firstActiveOffer = $product->activeOffers($product->paymentType)->first(['id', 'name', 'price', 'priceFirstPayment']);
                        $hasCreditCard = $product->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name);
                    @endphp

                    <div class="flex flex-col gap-6">

                        <figure class="setHorizontalBanner hidden max-h-80 w-full overflow-hidden rounded-xl">

                            <img
                                class="setHorizontalBannerURL h-full w-full"
                                src="https://placehold.co/1100x320"
                                alt="Imagem"
                                loading="lazy"
                            >

                        </figure>

                        <div class="flex items-center gap-4 md:gap-6">

                            <figure class="h-20 w-20 overflow-hidden rounded-xl md:h-28 md:w-28">
                                <img
                                    class="h-full w-full object-cover"
                                    src="{{ $product->featuredImageUrl }}"
                                    alt="Imagem"
                                    loading="lazy"
                                >
                            </figure>

                            <div class="flex-1">
                                <h3>{{ $product->name }}</h3>

                                @if ($firstActiveOffer->isRecurring)
                                    <p>
                                        @if ($firstActiveOffer->hasFirstPayment)
                                            <span class="text-xl font-bold md:text-2xl"> {{ $firstActiveOffer->brazilianPriceFirstPayment }}</span>
                                            <span class="text-sm font-bold text-neutral-600">primeiro pagamento</span>
                                        @else
                                            <span class="text-xl font-bold md:text-2xl">{{ $firstActiveOffer->brazilianPrice }}</span>
                                        @endif
                                    </p>
                                    <p>
                                        <span class="text-sm text-neutral-600">
                                            <span class="font-bold">Próxima cobrança:</span>
                                            {{ $firstActiveOffer->brazilianPrice }} em {{ $product->nextCharge()->format('d/m/Y') }}
                                        </span>
                                    </p>
                                    <p>
                                        <span class="text-sm text-neutral-600">{{ $firstActiveOffer->cyclePaymentTranslated }}</span>
                                    </p>
                                @else
                                    @if ($hasCreditCard)
                                        <p>
                                            <span class="max-installments-show text-sm text-neutral-600">{{ $product->maxInstallments }}x</span>
                                            <span class="max-installments-value-show text-xl font-bold md:text-2xl"></span>
                                        </p>
                                    @endif
                                    <p class="text-sm text-neutral-600">{{ $hasCreditCard ? 'ou' : '' }} {{ $firstActiveOffer->brazilianPrice }} à vista</p>
                                @endif
                            </div>

                        </div>

                    </div>
                @else
                    <div class="flex flex-col gap-6">

                        <figure class="setHorizontalBanner hidden max-h-80 w-full overflow-hidden rounded-xl">

                            <img
                                class="setHorizontalBannerURL h-full w-full"
                                src="https://placehold.co/1100x320"
                                alt="Imagem"
                                loading="lazy"
                            >

                        </figure>

                        <div class="flex items-center gap-4 md:gap-6">

                            <figure class="h-20 w-20 overflow-hidden rounded-xl md:h-28 md:w-28">
                                <img
                                    class="h-full w-full object-cover"
                                    src="{{ asset('images/dashboard/img-product-3.png') }}"
                                    alt="Imagem"
                                    loading="lazy"
                                >
                            </figure>

                            <div class="flex-1">
                                <h3>Nome do info produto - Um subtítulo para agregar informações</h3>
                                <p>
                                    <span class="text-sm text-neutral-600">12x</span>
                                    <span class="text-xl font-bold md:text-2xl">R$ 39,90</span>
                                </p>
                                <p class="text-sm text-neutral-600">ou R$ 399,00 à vista</p>

                            </div>

                        </div>

                    </div>
                @endif

                <div class="timer setTimer hidden rounded-xl p-10">
                    <h3 class="timerTitle text-center text-base font-medium leading-tight"></h3>
                    <div class="timerText flex items-center justify-center gap-2">
                        @include('components.icon', [
                            'icon' => 'timer',
                            'custom' => 'text-2xl',
                        ])
                        <p class="text-center text-lg font-semibold">Oferta termina em <span class="showtime"></span></p>
                    </div>
                </div>

                <div class="flex lg:gap-6">

                    @component('components.card', ['custom' => 'p-6 md:p-8 flex-1'])
                        <div class="space-y-6">

                            <h3>Dados pessoais</h3>

                            <div class="grid grid-cols-12 gap-6">

                                <div class="col-span-12">
                                    <label for="">Nome completo</label>
                                    <input
                                        placeholder="Digite seu nome completo"
                                        type="text"
                                    />
                                </div>

                                <div class="col-span-12">
                                    <label for="">Seu e-mail</label>
                                    <input
                                        placeholder="seuemail@dominio.com.br"
                                        type="email"
                                    />
                                </div>

                                <div class="col-span-12">
                                    <label for="">Confirme seu e-mail</label>
                                    <input
                                        placeholder="seuemail@dominio.com.br"
                                        type="email"
                                    />
                                </div>

                                <div class="col-span-12">
                                    <label for="">CPF/CNPJ</label>
                                    <input
                                        placeholder="000.000.000-00"
                                        oninput="setCpfCnpjMask(this)"
                                        type="text"
                                    />
                                </div>

                                <div class="col-span-12">
                                    <label for="">Telefone</label>
                                    <input
                                        placeholder="(99) 99999-9999"
                                        oninput="setInputMask(this, '(99) 99999-9999')"
                                        type="text"
                                    />
                                </div>

                                <div
                                    class="col-span-12"
                                    id="setCustomField"
                                >
                                </div>

                            </div>

                        </div>
                    @endcomponent

                    <div class="setVerticalBanner hidden">

                        <figure class="hidden h-full w-[320px] overflow-hidden rounded-xl lg:block">

                            <img
                                class="setVerticalBannerURL h-full w-full object-cover"
                                src="https://placehold.co/320x590"
                                alt="Imagem"
                                loading="lazy"
                            >

                        </figure>

                    </div>

                </div>

                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-6">

                        <h3>Pagamento</h3>

                        <div class="grid grid-cols-12 gap-6">

                            <div class="col-span-12">

                                <label for="">Selecione uma opção</label>

                                <div class="space-y-2">

                                    <label
                                        class="selectPaymentMethod mb-0 w-full cursor-pointer"
                                        for="selectCreditCard"
                                    >

                                        <input
                                            class="peer hidden"
                                            id="selectCreditCard"
                                            name="selectPaymentMethod"
                                            value="selectCreditCard"
                                            type="radio"
                                            onchange="showPaymentMethod('contentCreditCard')"
                                            checked
                                        >

                                        <div class="content rounded-lg border p-6">

                                            <div class="flex items-center gap-4">

                                                <div class="radio flex h-5 w-5 items-center justify-center rounded-full border">
                                                    @include('components.icon', [
                                                        'icon' => 'check',
                                                        'custom' => 'text-xl text-white',
                                                    ])
                                                </div>
                                                <p>Cartão de crédito</p>

                                            </div>

                                        </div>

                                    </label>

                                    <label
                                        class="selectPaymentMethod mb-0 w-full cursor-pointer"
                                        for="selectBankSlip"
                                    >

                                        <input
                                            class="peer hidden"
                                            id="selectBankSlip"
                                            name="selectPaymentMethod"
                                            value="selectBankSlip"
                                            type="radio"
                                            onchange="showPaymentMethod()"
                                        >

                                        <div class="content rounded-lg border p-6">

                                            <div class="flex items-center gap-4">

                                                <div class="radio flex h-5 w-5 items-center justify-center rounded-full border">
                                                    @include('components.icon', [
                                                        'icon' => 'check',
                                                        'custom' => 'text-xl text-white',
                                                    ])
                                                </div>
                                                <p>Boleto bancário</p>

                                            </div>

                                        </div>

                                    </label>

                                    <label
                                        class="selectPaymentMethod mb-0 w-full cursor-pointer"
                                        for="selectPix"
                                    >

                                        <input
                                            class="peer hidden"
                                            id="selectPix"
                                            name="selectPaymentMethod"
                                            value="selectPix"
                                            type="radio"
                                            onchange="showPaymentMethod()"
                                        >

                                        <div class="content rounded-lg border p-6">

                                            <div class="flex items-center gap-4">

                                                <div class="radio flex h-5 w-5 items-center justify-center rounded-full border">
                                                    @include('components.icon', [
                                                        'icon' => 'check',
                                                        'custom' => 'text-xl text-white',
                                                    ])
                                                </div>
                                                <p>Pix</p>

                                            </div>

                                        </div>

                                    </label>

                                </div>

                            </div>

                        </div>

                        <div
                            id="contentCreditCard"
                            class="payment-method"
                        >
                            <div class="grid grid-cols-12 gap-6">

                                <div class="col-span-12">
                                    <label for="">Númbero do cartão</label>
                                    <input
                                        placeholder="0000 0000 0000 0000"
                                        oninput="setInputMask(this, '9999 9999 9999 9999')"
                                        type="text"
                                    />
                                </div>

                                <div class="col-span-12">
                                    <label for="">Nome completo</label>
                                    <input
                                        placeholder="JOÃO PEDRO CARDOSO"
                                        type="text"
                                    />
                                </div>

                                <div class="col-span-6">
                                    <label for="">Data de Expiração</label>
                                    <input
                                        placeholder="00/00"
                                        oninput="setInputMask(this, '99/99')"
                                        type="text"
                                    />
                                </div>

                                <div class="col-span-6">
                                    <label for="">CVV</label>
                                    <input
                                        placeholder="CVV"
                                        oninput="setInputMask(this, '999')"
                                        type="text"
                                    />
                                </div>

                            </div>
                        </div>

                    </div>
                @endcomponent

                @include('components.orderbump.card')

                @component('components.card', ['custom' => 'p-6 md:p-8'])
                    <div class="space-y-6">

                        <h3>Detalhes da compra</h3>

                        <div class="setCupom hidden">

                            <div class="grid-cols-12 gap-4">

                                <div class="col-span-12">
                                    <label for="">Insira seu cupom de desconto</label>
                                    <input
                                        placeholder="Ex: CUPOM19"
                                        type="text"
                                    />
                                </div>

                            </div>

                        </div>

                        <ul>
                            <li class="flex items-center justify-between">
                                <span>Valor total do pagamento:</span>
                                <span>R$ 3.948,00</span>
                            </li>
                        </ul>

                    </div>
                @endcomponent

                <button
                    class="button setButtonColor setButtonTextColor h-10 w-full rounded-full md:h-12"
                    type="button"
                >
                    Pagar e receber agora
                </button>

                @component('components.card', ['custom' => 'setTestimonials hidden p-6 md:p-8'])
                    <div
                        class="space-y-6"
                        id="testimonials"
                    >

                        <div class="flex items-center justify-between">

                            <h3>Depoimentos</h3>

                            <button
                                class="button button-outline-primary h-10 rounded-full"
                                id="add-item"
                                type="button"
                            >
                                Adicionar depoimento
                            </button>

                        </div>

                        <ul
                            class="divide-y divide-neutral-100 md:p-4 lg:p-8"
                            id="list"
                        >
                        </ul>

                    </div>
                @endcomponent

                @component('components.card', ['custom' => 'p-6 md:p-8 notSeal'])
                    <div class="flex items-center justify-center gap-8">

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

                    </div>
                @endcomponent

                <div class="">

                    <h4 class="mb-3">Precisa de ajuda?</h4>

                    <ul>
                        <li>
                            <div class="flex items-center gap-2">
                                @include('components.icon', [
                                    'icon' => 'account_circle',
                                    'custom' => 'text-lg text-gray-400',
                                ])
                                <span class="text-xs md:text-sm">Nome do produtor</span>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center gap-2">
                                @include('components.icon', [
                                    'icon' => 'link',
                                    'custom' => 'text-lg text-gray-400',
                                ])
                                <a
                                    class="textPrimaryColor flex items-center gap-2 text-xs md:text-sm"
                                    href="#"
                                >
                                    nomedaloja.com.br
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
                                    class="textPrimaryColor text-xs md:text-sm"
                                    href="#"
                                >
                                    emaildesuporte@dominio.com.br
                                </a>
                            </div>
                        </li>
                    </ul>

                    <hr class="my-4 border-gray-200">

                    <p class="linkPrimaryColor text-xs md:text-sm">Ao clicar em “Finalizar pagamento, eu declaro que li e concordo que a SuitPay está processando este pedido em nome de Nome do Afiliado e não possuí responsabilidade pelo conteúdo e/ou faz controle prévio deste, assim como está previsto nos <a href="#">Termos de Uso</a> e <a href="#">Política de Privacidade</a> da SuitPay. SuitPay © 2024 - Todos os direitos reservados</p>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style id="customCheckoutStyle"></style>
@endsection
