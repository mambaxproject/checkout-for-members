@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Carrinho abandonado #{{ $abandonedCart->id }}</h1>

        <nav class="flex items-center border-b border-neutral-300" data-tabs-toggle="#page-tab-content">

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-order" aria-selected="true" role="tab" type="button">
                Venda
            </button>

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-client" aria-selected="false" role="tab" type="button">
                Cliente
            </button>

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-value" aria-selected="false" role="tab" type="button">
                Valores
            </button>

            @if ($tracking = $abandonedCart->lastTracking)
                <button
                        class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                        data-tabs-target="#tab-utm"
                        aria-selected="false"
                        role="tab"
                        type="button"
                >
                    UTM
                </button>
            @endif

        </nav>

        <div id="page-tab-content">

            <div class="hidden" id="tab-order">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <table class="w-full">
                        <tbody class="divide-y">
                            @if ($abandonedCart->order)
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">ID da venda</td>
                                    <td class="px-3 py-4">{{ $abandonedCart->order->client_orders_uuid }}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Status do carrinho</td>
                                    <td class="px-3 py-4">
                                        <span
                                            class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                            @include('components.icon', [
                                                'icon' => 'circle',
                                                'type' => 'fill',
                                                'custom' =>
                                                    'text-xs ' .
                                                    \App\Enums\StatusAbandonedCartEnum::getClass(
                                                        $abandonedCart->status),
                                            ])
                                            {{ \App\Enums\StatusAbandonedCartEnum::getDescription($abandonedCart->status) }}
                                        </span>
                                    </td>
                                </tr>

                                @if ($abandonedCart->order?->hasAffiliate)
                                    <tr>
                                        <td class="px-3 py-4 font-semibold md:w-1/3">Tipo</td>
                                        <td class="px-3 py-4">Sou afiliado</td>
                                    </tr>
                                @endif
                            @endif

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Valor</td>
                                <td class="px-3 py-4">{{ $abandonedCart->brazilianAmount }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Produto</td>
                                <td class="px-3 py-4">
                                    {{ $abandonedCart->product->parentProduct->name }}
                                </td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Método de pagamento</td>
                                <td class="px-3 py-4">{{ $abandonedCart->paymentMethodTranslated }}</td>
                            </tr>

                            @if (!empty($abandonedCart->order?->payments?->last()?->installments))
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Parcelas</td>
                                    <td class="px-3 py-4">{{ $abandonedCart->order->payments?->last()?->installments }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Data da criação</td>
                                <td class="px-3 py-4">{{ $abandonedCart->created_at->isoFormat('dddd, DD/MM/YYYY HH:mm') }}</td>
                            </tr>
                            @if (!is_null($abandonedCart->convertedMethod))
                                <td class="px-3 py-4 font-semibold md:w-1/3">Convertido via:</td>
                                <td class="px-3 py-4">{{ ucfirst($abandonedCart->convertedMethod) }}</td>
                            @endif
                        </tbody>
                    </table>
                @endcomponent
            </div>

            <div class="hidden" id="tab-client">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <table class="w-full">
                        <tbody class="divide-y">
                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Nome</td>
                                <td class="px-3 py-4">{{ $abandonedCart->name }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">E-mail</td>
                                <td class="px-3 py-4">{{ $abandonedCart->email }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Celular</td>
                                <td class="px-3 py-4">{{ $abandonedCart->phone_number }}</td>
                            </tr>

                            @if ($abandonedCart->order)
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">CPF</td>
                                    <td class="px-3 py-4">{{ $abandonedCart->order->user->document_number }}</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                @endcomponent
            </div>

            <div class="hidden" id="tab-value">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <table class="w-full">
                        <tbody class="divide-y">
                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Preço base</td>
                                <td class="px-3 py-4">{{ $abandonedCart->brazilianAmount }}</td>
                            </tr>

                            @if ($abandonedCart->order)
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Divisão de valores</td>
                                    <td class="px-3 py-4">
                                        @if ($abandonedCart->order?->hasAffiliate)
                                            <div class="">
                                                <span class="font-semibold">Afiliado:</span>
                                                <span>
                                                    {{ $abandonedCart->order->brazilianAffiliateAmount }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="">
                                            <span class="font-semibold">Seu recebimento:</span>
                                            <span>
                                                {{ $abandonedCart->order->brazilianShopAmount }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Status</td>
                                    <td class="px-3 py-4">
                                        <span
                                            class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                            @include('components.icon', [
                                                'icon' => 'circle',
                                                'type' => 'fill',
                                                'custom' =>
                                                    'text-xs ' . $abandonedCart->order->classCssPaymentStatus,
                                            ])
                                            {{ $abandonedCart->order->paymentStatus }}
                                        </span>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Valor</td>
                                <td class="px-3 py-4">{{ $abandonedCart->brazilianAmount }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endcomponent
            </div>

            @if ($tracking)

                <div
                        class="hidden"
                        id="tab-utm"
                >
                    @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                        <table class="w-full">
                            <tbody class="divide-y">
                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Origem (utm_source)</td>
                                <td class="px-3 py-4">{{ $tracking->utm_source }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Meio (utm_medium)</td>
                                <td class="px-3 py-4">{{  $tracking->utm_medium }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Campanha (utm_campaign)</td>
                                <td class="px-3 py-4">{{  $tracking->utm_campaign }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Conteúdo (utm_content)</td>
                                <td class="px-3 py-4">{{  $tracking->utm_content }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Termo (utm_term)</td>
                                <td class="px-3 py-4">{{  $tracking->utm_term }}</td>
                            </tr>


                            </tbody>
                        </table>
                    @endcomponent
                </div>
            @endif

        </div>

    </div>
@endsection
