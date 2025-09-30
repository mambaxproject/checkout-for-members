@extends('layouts.new-admin', ['title' => 'Detalhes do produto'])

@section('content')
    <div class="grid grid-cols-12 md:gap-x-6">

        <div class="col-span-12">

            <div class="box bg-slate-200">

                <div class="box-header justify-between border-0 pb-0">

                    <div class="box-title before:hidden">Informações gerais</div>

                    <div class="{{ \App\Enums\SituationProductEnum::getClassAdmin($product->situation) }} flex items-center gap-2 rounded-full px-3 py-2">
                        <i class="bx bx-check-circle text-xl"></i>
                        {{ $product->situationTranslated }}
                    </div>

                </div>
                <div class="box-body space-y-4 p-2">

                    @component('components.admin.ui.card', ['cardTitle' => 'Informações gerais'])
                        <div class="space-y-4">

                            <div class="">
                                <p class="form-label">Nome do Produto</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->name }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Categoria do Produto</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->category?->name }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Página de vendas</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('externalSalesLink') ?? '-' }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Descrição</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->description ?? '-' }}</p>
                            </div>

                        </div>
                    @endcomponent

                    @component('components.admin.ui.card', ['cardTitle' => 'Garantia e valores'])
                        <div class="space-y-4">

                            <div class="">
                                <p class="form-label">Garantia</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->guarantee }} dias</p>
                            </div>

                            <div class="">
                                <p class="form-label">Forma de pagamento</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->paymentTypeTranslated }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Ofertas</p>
                                <div class="table-responsive rounded-md border">
                                    <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                        <thead class="bg-slate-100">
                                            <tr>
                                                <th>Nome da oferta</th>
                                                <th>Valor da oferta</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @forelse ($activeOffers as $offer)
                                                <tr>
                                                    <td>{{ $offer->name }}</td>
                                                    <td>{{ $offer->brazilianPrice }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="2"
                                                        class="text-center"
                                                    >Nenhuma oferta ativa</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @endcomponent

                    @component('components.admin.ui.card', ['cardTitle' => 'Anexos'])
                        <div class="space-y-4">

                            @if ($product->getMedia('attachment')->isNotEmpty())
                                <div class="">
                                    <p class="form-label">Nome do Anexo</p>
                                    <p class="form-control bg-light whitespace-normal">{{ $product->getFirstMedia('attachment')->name ?? '' }}</p>
                                </div>

                                <div class="">
                                    <p class="form-label">Descrição do Anexo</p>
                                    <p class="form-control bg-light whitespace-normal">{{ $product->getFirstMedia('attachment')?->getCustomProperty('description', '') }}</p>
                                </div>

                                <div class="">
                                    <p class="form-label">Arquivo</p>

                                    <div class="col-span-12">
                                        <div class="overflow-hidden rounded-lg border border-neutral-100 md:overflow-visible">
                                            <div class="overflow-x-scroll md:overflow-visible">
                                                <table class="table-lg table w-full">
                                                    <thead>
                                                        <tr>
                                                            <th>Foto</th>
                                                            <th>Nome</th>
                                                            <th>Extensão</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product->getMedia('attachment') as $media)
                                                            <tr>
                                                                <td>
                                                                    <a
                                                                        href="{{ $media->getUrl() }}"
                                                                        title="Ver Foto"
                                                                        target="_blank"
                                                                    >
                                                                        <img
                                                                            src="{{ $media->getUrl() }}"
                                                                            alt="{{ $media->name }}"
                                                                            class="h-16 w-16 rounded-lg object-cover"
                                                                            loading="lazy"
                                                                        />
                                                                    </a>
                                                                </td>
                                                                <td>{{ $media->name }}</td>
                                                                <td>
                                                                    <span class="rounded-md bg-neutral-600 px-3 py-2 text-xs font-semibold uppercase text-white md:mr-[20%]">
                                                                        {{ $media->extension }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center">Nenhum anexo cadastrado</div>
                            @endif

                        </div>
                    @endcomponent

                </div>

            </div>

            <div class="box bg-slate-200">

                <div class="box-header justify-between border-0 pb-0">

                    <div class="box-title before:hidden">Checkout</div>

                </div>

                <div class="box-body space-y-4 p-2 md:p-3">

                    @component('components.admin.ui.card')
                        <div class="space-y-4">

                            <div class="">
                                <p class="form-label">Modelo de checkout</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->checkout?->name }}</p>
                            </div>

                        </div>
                    @endcomponent

                </div>

            </div>

            <div class="box bg-slate-200">

                <div class="box-header justify-between border-0 pb-0">
                    <div class="box-title before:hidden">Configurações</div>
                </div>
                <div class="box-body space-y-4 p-2 md:p-3">

                    @component('components.admin.ui.card', ['cardTitle' => 'Métodos de Pagamentos'])

                        @component('components.admin.ui.accordion', [
                            'accordionId' => 'accordionPayment',
                        ])
                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordinHeadingPaymentCreditCard',
                                'accordionItemCollapseId' => 'accordionPaymentCreditCard',
                                'accordionItemTitle' => 'Cartão de crédito',
                                'open' => true,
                            ])
                                <div class="space-y-4 p-4 md:p-6">

                                    <div class="">
                                        <p class="form-label">Status</p>
                                        <p class="form-control bg-light whitespace-normal">
                                            {{ $product->hasPaymentMethod(\App\Enums\PaymentMethodEnum::CREDIT_CARD->name) ? 'Ativo' : 'Inativo' }}
                                        </p>
                                    </div>

                                    <div class="">
                                        <p class="form-label">Número máximo de parcelas</p>
                                        <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('maxInstallments') ?? '-' }} meses</p>
                                    </div>

                                </div>
                            @endcomponent

                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordinHeadingPaymentBankSlip',
                                'accordionItemCollapseId' => 'accordionPaymentBankSlip',
                                'accordionItemTitle' => 'Boleto bancário',
                            ])
                                <div class="space-y-4 p-4 md:p-6">

                                    <div class="">
                                        <p class="form-label">Status</p>
                                        <p class="form-control bg-light whitespace-normal">
                                            {{ $product->hasPaymentMethod(\App\Enums\PaymentMethodEnum::BILLET->name) ? 'Ativo' : 'Inativo' }}
                                        </p>
                                    </div>

                                    <div class="">
                                        <p class="form-label">Validade do boleto (em dias úteis)</p>
                                        <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('daysDueDateBillet') ?? '-' }} dias</p>
                                    </div>

                                </div>
                            @endcomponent

                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordinHeadingPaymentPix',
                                'accordionItemCollapseId' => 'accordionPaymentPix',
                                'accordionItemTitle' => 'Pix',
                            ])
                                <div class="space-y-4 p-4 md:p-6">

                                    <div class="">
                                        <p class="form-label">Status</p>
                                        <p class="form-control bg-light whitespace-normal">
                                            {{ $product->hasPaymentMethod(\App\Enums\PaymentMethodEnum::PIX->name) ? 'Ativo' : 'Inativo' }}
                                        </p>
                                    </div>

                                </div>
                            @endcomponent
                        @endcomponent

                    @endcomponent

                    @component('components.admin.ui.card', ['cardTitle' => 'Configurações'])
                        @component('components.admin.ui.accordion', [
                            'accordionId' => 'accordionConfig',
                        ])
                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordionHeadingConfigCouponsDiscount',
                                'accordionItemCollapseId' => 'accordionConfigCouponsDiscount',
                                'accordionItemTitle' => 'Cupom de desconto',
                                'open' => true,
                            ])
                                <div class="table-responsive">
                                    <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                        <thead class="bg-slate-100">
                                            <tr>
                                                <th>Código do cupom</th>
                                                <th>Tipo do desconto</th>
                                                <th>Valor do cupom</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @forelse ($product->couponsDiscount as $coupon)
                                                <tr>
                                                    <td>{{ $coupon->code }}</td>
                                                    <td>{{ $coupon->typeDiscountTranslated }}</td>
                                                    <td>{{ $coupon->amountFormatted }}</td>
                                                    <td class="!text-end">
                                                        @component('components.admin.ui.drawer', [
                                                            'id' => 'viewDetailCoupnsDiscount',
                                                            'btnIcon' => 'show',
                                                            'btnTitle' => 'Ver detalhes',
                                                            'drawerTitle' => 'Cupom de desconto',
                                                        ])
                                                            <div class="!p-4 !text-start">
                                                                <div class="space-y-4">

                                                                    <div class="">
                                                                        <p class="form-label">Código do cupom</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $coupon->code }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Nome</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $coupon->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Descrição</p>
                                                                        <p class="form-control bg-light text-wrap">{{ $coupon->description ?? '-' }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Total de coupons disponíveis</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $coupon->quantity }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Valor mínimo de compra</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $coupon->minimum_price_order }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Válido a partir de</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $coupon->start_at->format('d/m/Y H:i') }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Válido até</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $coupon->end_at->format('d/m/Y H:i') }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Regras</p>
                                                                        <ul class="form-control bg-light space-y-px">
                                                                            <li>
                                                                                {{ $coupon->automatic_application ? '✅' : '⛔' }}
                                                                                Aplicação automática no carrinho de compra
                                                                            </li>
                                                                            <li>
                                                                                {{ $coupon->once_per_customer ? '✅' : '⛔' }}
                                                                                Uso único, (1 vez) por cliente
                                                                            </li>
                                                                            <li>
                                                                                {{ $coupon->newsletter_abandoned_carts ? '✅' : '⛔' }}
                                                                                Envio automático nos e-mails de carrinho abandonado
                                                                            </li>
                                                                            <li>
                                                                                {{ $coupon->only_first_order ? '✅' : '⛔' }}
                                                                                Cupom de 1ª compra
                                                                            </li>
                                                                        </ul>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Métodos de pagamento</p>
                                                                        <ul class="form-control bg-light space-y-px">
                                                                            <li>
                                                                                {{ in_array('CREDIT_CARD', $coupon->payment_methods) ? '✅' : '⛔' }}
                                                                                Cartão de Crédito
                                                                            </li>
                                                                            <li>
                                                                                {{ in_array('BILLET', $coupon->payment_methods) ? '✅' : '⛔' }}
                                                                                Boleto
                                                                            </li>
                                                                            <li>
                                                                                {{ in_array('PIX', $coupon->payment_methods) ? '✅' : '⛔' }}
                                                                                Pix
                                                                            </li>
                                                                        </ul>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endcomponent
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="text-center"
                                                    >Nenhum cupom de desconto cadastrado</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endcomponent

                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordionHeadingConfigOrderBump',
                                'accordionItemCollapseId' => 'accordionConfigOrderBump',
                                'accordionItemTitle' => 'OrderBump',
                            ])
                                <div class="table-responsive">
                                    <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                        <thead class="bg-slate-100">
                                            <tr>
                                                <th>Orderbump</th>
                                                <th>Produto</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @forelse ($product->orderBumps as $orderBump)
                                                <tr>
                                                    <td>{{ $orderBump->name }}</td>
                                                    <td>{{ $orderBump->product?->name }}</td>
                                                    <td class="!text-end">
                                                        @component('components.admin.ui.drawer', [
                                                            'id' => 'viewDetailOrderBump',
                                                            'btnIcon' => 'show',
                                                            'btnTitle' => 'Ver detalhes',
                                                            'drawerTitle' => 'Orderbump',
                                                        ])
                                                            <div class="!p-4 !text-start">
                                                                <div class="space-y-4">

                                                                    <div class="">
                                                                        <p class="form-label">Nome do Order Bump</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $orderBump->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Produto</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $orderBump->product?->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Oferta</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $orderBump->product_offer?->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Chamada</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $orderBump->title_cta }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Descrição</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $orderBump->description }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Preço promocional</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $orderBump->brazilianPrice }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Métodos de pagamento</p>
                                                                        <ul class="form-control bg-light space-y-px">
                                                                            <li>
                                                                                {{ in_array('CREDIT_CARD', $orderBump->payment_methods) ? '✅' : '⛔' }}
                                                                                Cartão de Crédito
                                                                            </li>
                                                                            <li>
                                                                                {{ in_array('BILLET', $orderBump->payment_methods) ? '✅' : '⛔' }}
                                                                                Boleto
                                                                            </li>
                                                                            <li>
                                                                                {{ in_array('PIX', $orderBump->payment_methods) ? '✅' : '⛔' }}
                                                                                Pix
                                                                            </li>
                                                                        </ul>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endcomponent
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="3"
                                                        class="text-center"
                                                    >Nenhum orderbump cadastrado</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endcomponent

                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordionHeadingConfigPageThanks',
                                'accordionItemCollapseId' => 'accordionConfigPageThanks',
                                'accordionItemTitle' => 'Página de Obrigado',
                            ])
                                <div class="space-y-4 p-4 md:p-6">

                                    <div class="">
                                        <p class="form-label">Compra aprovada PIX</p>
                                        <p class="form-control bg-light whitespace-normal">
                                            {{ $product->getValueSchemalessAttributes('linkThanksForOrderInPIX') ?? 'Página de obrigado padrão do sistema' }}
                                        </p>
                                    </div>

                                    <div class="">
                                        <p class="form-label">Compra aprovada Cartão de crédito</p>
                                        <p class="form-control bg-light whitespace-normal">
                                            {{ $product->getValueSchemalessAttributes('linkThanksForOrderInCREDIT_CARD') ?? 'Página de obrigado padrão do sistema' }}
                                        </p>
                                    </div>

                                    <div class="">
                                        <p class="form-label">Compra aprovada Boleto</p>
                                        <p class="form-control bg-light whitespace-normal">
                                            {{ $product->getValueSchemalessAttributes('linkThanksForOrderInBILLET') ?? 'Página de obrigado padrão do sistema' }}
                                        </p>
                                    </div>

                                </div>
                            @endcomponent

                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordionHeadingConfigUpSells',
                                'accordionItemCollapseId' => 'accordionConfigUpSells',
                                'accordionItemTitle' => 'UpSells',
                            ])
                                <div class="table-responsive">
                                    <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                        <thead class="bg-slate-100">
                                            <tr>
                                                <th>UpSell</th>
                                                <th>Produto</th>
                                                <th>Oferta</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @forelse ($product->upSells as $upSell)
                                                <tr>
                                                    <td>{{ $upSell->name }}</td>
                                                    <td>{{ $upSell->product->name }}</td>
                                                    <td>{{ $upSell->product_offer->name }}</td>
                                                    <td class="!text-end">
                                                        @component('components.admin.ui.drawer', [
                                                            'id' => 'viewDetailUpSell',
                                                            'btnIcon' => 'show',
                                                            'btnTitle' => 'Ver detalhes',
                                                            'drawerTitle' => 'UpSell',
                                                        ])
                                                            <div class="!p-4 !text-start">
                                                                <div class="space-y-4">

                                                                    <div class="">
                                                                        <p class="form-label">Nome do Upsell</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Produto UpSell</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->product->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Oferta UpSell</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->product_offer->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Quando Oferecer</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->textTranslatedWhenOffer }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Ao aceitar UpSell</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->textTranslatedWhenAccept }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Ao recusar UpSell</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->textTranslatedWhenReject }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Texto de aceite</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->text_accept ?? '-' }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Texto de recusa</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $upSell->text_reject ?? '-' }}</p>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endcomponent
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="text-center"
                                                    >Nenhum UpSell cadastrado</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endcomponent

                            @component('components.admin.ui.accordion-item', [
                                'accordionItemId' => 'accordionHeadingConfigPixel',
                                'accordionItemCollapseId' => 'accordionConfigPixel',
                                'accordionItemTitle' => 'Pixel',
                            ])
                                <div class="table-responsive">
                                    <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                        <thead class="bg-slate-100">
                                            <tr>
                                                <th>Nome</th>
                                                <th>Serviço</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y">
                                            @forelse ($product->pixels as $pixel)
                                                <tr>
                                                    <td>{{ $pixel->name }}</td>
                                                    <td>{{ $pixel->pixelService->name }}</td>
                                                    <td class="!text-end">
                                                        @component('components.admin.ui.drawer', [
                                                            'id' => 'viewDetailPixel',
                                                            'btnIcon' => 'show',
                                                            'btnTitle' => 'Ver detalhes',
                                                            'drawerTitle' => 'Pixel',
                                                        ])
                                                            <div class="!p-4 !text-start">
                                                                <div class="space-y-4">

                                                                    <div class="">
                                                                        <p class="form-label">Serviço</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $pixel->pixelService->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Nome</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $pixel->name }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Pixel ID</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $pixel->pixel_id }}</p>
                                                                    </div>

                                                                    <div class="">
                                                                        <p class="form-label">Valor enviado para o pixel</p>
                                                                        <p class="form-control bg-light whitespace-normal">{{ $pixel->getValueSchemalessAttributes('amountToSend') }}</p>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endcomponent
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="3"
                                                        class="text-center"
                                                    >Nenhum pixel cadastrado</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @endcomponent
                        @endcomponent
                    @endcomponent

                    @component('components.admin.ui.card', ['cardTitle' => 'Suporte'])
                        <div class="space-y-4">

                            <div class="">
                                <p class="form-label">E-mail de suporte</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('emailSupport') ?? '-' }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Nome do produtor</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('mameShop') ?? '-' }}</p>
                            </div>

                        </div>
                    @endcomponent

                </div>

            </div>

            <div class="box bg-slate-200">

                <div class="box-header justify-between border-0 pb-0">
                    <div class="box-title before:hidden">Coprodutores</div>
                </div>
                <div class="box-body space-y-4 p-2 md:p-3">

                    @component('components.admin.ui.card')
                        <div class="table-responsive">
                            <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                <thead class="border-defaultborder border-b">
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Validade</th>
                                        <th>Comissão total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($product->coproducers as $coproducer)
                                        <tr>
                                            <td>{{ $coproducer->name }}</td>
                                            <td>{{ $coproducer->email }}</td>
                                            <td>{{ $coproducer->valid_until_at?->isoFormat('DD/MM/YYYY') }}</td>
                                            <td>{{ $coproducer->percentage_commission }} %</td>
                                            <td>{{ $coproducer->situationFormatted }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endcomponent

                </div>

            </div>

            <div class="box bg-slate-200">

                <div class="box-header justify-between border-0 pb-0">
                    <div class="box-title before:hidden">Afiliados</div>
                </div>
                <div class="box-body space-y-4 p-2 md:p-3">

                    @component('components.admin.ui.card', ['cardTitle' => 'Configurações'])
                        <div class="space-y-4">

                            <div class="">
                                <p class="form-label">{{ $product->getValueSchemalessAttributes('affiliate.enabled') ? '✅' : '⛔' }} Habilitar programa de afiliados</p>
                                <p class="form-label">{{ $product->getValueSchemalessAttributes('affiliate.approveRequestsManually') ? '✅' : '⛔' }} Aprovar solicitações de afiliação manualmente (Automático por padrão)</p>
                                <p class="form-label">{{ $product->getValueSchemalessAttributes('affiliate.allowAccessToCustomersData') ? '✅' : '⛔' }} Liberar acesso aos dados de contato de compradores para afiliados</p>
                                <p class="form-label">{{ $product->getValueSchemalessAttributes('affiliate.showProductInMarketplace') ? '✅' : '⛔' }} Mostrar produto no Marketplace de Afiliados</p>
                            </div>

                            <div class="">
                                <p class="form-label">E-mail de suporte para afiliados</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('affiliate.emailSupport') ?? '-' }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Descrição do produto para afiliados</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('affiliate.descriptionProduct') ?? '-' }}</p>
                            </div>

                            <div class="">
                                <p class="form-label">Escolha uma opção</p>
                                <p class="form-control bg-light whitespace-normal">
                                    Valor fixo por venda
                                    {{ $product->getValueSchemalessAttributes('affiliate.defaultTypeValue') === 'VALUE' ? 'Valor fixo por venda' : 'Porcentagem' }}
                                </p>
                            </div>

                            <div class="">
                                <p class="form-label">Valor da afiliação:</p>
                                <p class="form-control bg-light whitespace-normal">{{ $product->getValueSchemalessAttributes('affiliate.defaultValue') ?? '-' }}</p>
                            </div>

                        </div>
                    @endcomponent

                    @component('components.admin.ui.card', ['cardTitle' => 'Link de convite de afiliado'])
                        <div class="space-y-4">

                            <div class="">
                                <p class="form-label">Compartilhe esse link para convidar os seus afiliados</p>
                                <p class="form-control bg-light overflow-hidden break-all">
                                    {{ $product->linkJoinAffiliate }}
                                </p>
                            </div>

                        </div>
                    @endcomponent

                </div>

            </div>

            <div class="box bg-slate-200">

                <div class="box-header justify-between border-0 pb-0">
                    <div class="box-title before:hidden">Links</div>
                </div>
                <div class="box-body space-y-4 p-2 md:p-3">

                    @component('components.admin.ui.card', ['cardTitle' => 'Domínio Próprio'])
                        <div class="table-responsive">
                            <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                <thead class="border-defaultborder border-b">
                                    <tr>
                                        <th>Nome da oferta</th>
                                        <th>URL</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse($product->domains as $domain)
                                        <tr>
                                            <td>{{ $domain->domain }}</td>
                                            <td>{{ $domain->url }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td
                                                colspan="3"
                                                class="text-center"
                                            >Nenhum domínio cadastrado</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endcomponent

                    @component('components.admin.ui.card', ['cardTitle' => 'Links e ofertas'])
                        <div class="table-responsive">
                            <table class="table-hover ti-custom-table-hover table min-w-full whitespace-nowrap">
                                <thead class="border-defaultborder border-b">
                                    <tr>
                                        <th>Nome da oferta</th>
                                        <th>Valor</th>
                                        <th>URL</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($activeOffers as $offer)
                                        <tr>
                                            <td>{{ $offer->name }}</td>
                                            <td>{{ $offer->brazilianPrice }}</td>
                                            <td>{{ url($offer->url) }}</td>
                                            <td>{{ $offer->statusFormatted }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td
                                                colspan="4"
                                                class="text-center"
                                            >Nenhuma oferta ativa</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endcomponent

                </div>

            </div>

        </div>

        @unless($product->isDraft)
            <div class="col-span-12">

                <div class="flex items-center justify-end gap-2 bg-white p-4">

                    @includeUnless($product->isReproved, 'partials.admin.products.reject-product')

                    @if ($product->isInAnalysis)
                        <form action="{{route('admin.products.updateSituation', $product)}}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="situation" value="{{ \App\Enums\SituationProductEnum::PUBLISHED->name }}" />
                            <button
                                    class="ti-btn ti-btn-success-full mb-0"
                                    type="submit"
                            >
                                <i class="bx bx-check"></i>
                                Aprovar produto
                            </button>
                        </form>
                    @endif

                </div>

            </div>
        @endunless

    </div>
@endsection
