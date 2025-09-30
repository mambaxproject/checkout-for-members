@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">
            <h1>Pedido
                <span class="copyClipboard cursor-pointer rounded-full -mt-1 bg-neutral-200 px-3 py-[3px] text-sm font-semibold"
                    data-tooltip-text="Click para copiar o ID: <br> {{ $order->client_orders_uuid }}"
                    data-tooltip-position="bottom" data-clipboard-text="{{ $order->client_orders_uuid }}">
                    <span>
                        ID:
                        <i class="ti ti-key text-1xl"></i>
                        <i class="ti ti-copy text-1xl"></i>
                    </span>
                </span>
            </h1>

            <div>
                @if ($order->isPaid())
                    <form method="POST" action="{{ route('dashboard.orders.refund', $order) }}"
                        onsubmit="return confirm('Tem certeza que deseja solicitar estorno do pedido do cliente {{ $order->user->name }} no valor de {{ $order->brazilianPrice }}? \n\n ️O saldo correspondente será debitado da sua conta e o estorno não poderá ser desfeito.')">
                        @csrf

                        <button type="submit" class="button button-primary h-12 gap-1 rounded-full"
                            title="Solicitar estorno">
                            Solicitar estorno
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <nav class="flex items-center border-b border-neutral-300" data-tabs-toggle="#page-tab-content">

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-order" aria-selected="true" role="tab" type="button">
                Venda
            </button>

            @if ($canShowCustomersData)
                <button
                    class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                    data-tabs-target="#tab-client" aria-selected="false" role="tab" type="button">
                    Cliente
                </button>
            @endif

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-value" aria-selected="false" role="tab" type="button">
                Valores
            </button>

            @if ($order->getValueSchemalessAttributes('utm'))
                <button
                    class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                    data-tabs-target="#tab-utm" aria-selected="false" role="tab" type="button">
                    UTM
                </button>
            @endif

        </nav>

        <div id="page-tab-content">

            <div class="hidden" id="tab-order">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <table class="w-full">
                        <tbody class="divide-y">
                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Número do pedido</td>
                                <td class="px-3 py-4">{{ $order->client_orders_uuid }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Status</td>
                                <td class="px-3 py-4">
                                    <span
                                        class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' => 'text-xs ' . $order->classCssPaymentStatus,
                                        ])
                                        {{ $order->paymentStatus }}
                                    </span>

                                    @if(($order->isFailed() || $order->isUnpaid()) && ! empty($order->payment->reasonRefused))
                                        <p class="mt-2 text-sm text-red-600">
                                            Motivo: {{ $order->payment->reasonRefused }}
                                        </p>
                                    @endif
                                </td>
                            </tr>

                            @if (!empty($order->affiliate_id))
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Tipo</td>
                                    <td class="px-3 py-4">Venda com afiliado</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Valor</td>
                                <td class="px-3 py-4">{{ $order->brazilianPrice }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Produto</td>
                                <td class="px-3 py-4">
                                    {{ $order->items->implode('product.parentProduct.name', ', ') }}
                                </td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Método de pagamento</td>
                                <td class="px-3 py-4">{{ $order->paymentMethod }}</td>
                            </tr>

                            @if (!empty($order->payments?->last()?->installments))
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Parcelas</td>
                                    <td class="px-3 py-4">{{ $order->payments?->last()?->installments }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Data da criação</td>
                                <td class="px-3 py-4">{{ $order->created_at->isoFormat('dddd, DD/MM/YYYY HH:mm') }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endcomponent
            </div>

            @if ($canShowCustomersData)
                <div class="hidden" id="tab-client">
                    @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                        <table class="w-full">
                            <tbody class="divide-y">
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Nome</td>
                                    <td class="px-3 py-4">{{ $order->user->name }}</td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">E-mail</td>
                                    <td class="px-3 py-4">{{ $order->user->email }}</td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Celular</td>
                                    <td class="px-3 py-4">
                                        {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $order->user->phone_number) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">CPF</td>
                                    <td class="px-3 py-4">
                                        {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $order->user->document_number) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endcomponent
                </div>
            @endif

            <div class="hidden" id="tab-value">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <table class="w-full">
                        <tbody class="divide-y">
                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Valor</td>
                                <td class="px-3 py-4">{{ $order->brazilianPrice }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Preço base</td>
                                <td class="px-3 py-4">{{ $order->brazilianShopAmount }}</td>
                            </tr>

                            @if ($order->affiliate_id || $order->coproducerAmount)
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Comissionamento</td>
                                    <td class="px-3 py-4">
                                        @if ($order->belongsToShop)
                                            <div class="">
                                                <span class="font-semibold">Afiliado:</span>
                                                <span>{{ $order->brazilianAffiliateAmount }}</span>
                                            </div>
                                        @elseif ($order->belongsToCoProducer || $userIsAffiliateParentProductOrder)
                                            <div class="">
                                                <span class="font-semibold">Valor a receber:</span>
                                                @php
                                                    $user = \Illuminate\Support\Facades\Auth::user();
                                                    $shopUser = $user->shop();
                                                @endphp
                                                <span>{{ $order->brazilianAmountByTypeUser($user, $shopUser) }}</span>
                                            </div>
                                        @endif

                                        @if ($order->coproducerAmount > 0 && $order->belongsToShop)
                                            <div class="">
                                                <span class="font-semibold">Coprodutor:</span>
                                                <span>{{ Number::currency($order->coproducerAmount, 'BRL', 'pt-br') }}</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            @if ($order->belongsToShop)
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Faturamento</td>
                                    <td class="px-3 py-4">{{ $order->brazilianInvoicingShop }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Status</td>
                                <td class="px-3 py-4">
                                    <span
                                        class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' => 'text-xs ' . $order->classCssPaymentStatus,
                                        ])
                                        {{ $order->paymentStatus }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endcomponent
            </div>

            @if ($order->getValueSchemalessAttributes('utm'))
                <div class="hidden" id="tab-utm">
                    @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                        <table class="w-full">
                            <tbody class="divide-y">
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Origem (utm_source)</td>
                                    <td class="px-3 py-4">{{ $order->getValueSchemalessAttributes('utm.source') }}</td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Meio (utm_medium)</td>
                                    <td class="px-3 py-4">{{ $order->getValueSchemalessAttributes('utm.medium') }}</td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Campanha (utm_campaign)</td>
                                    <td class="px-3 py-4">{{ $order->getValueSchemalessAttributes('utm.campaign') }}</td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Conteúdo (utm_content)</td>
                                    <td class="px-3 py-4">{{ $order->getValueSchemalessAttributes('utm.content') }}</td>
                                </tr>

                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Termo (utm_term)</td>
                                    <td class="px-3 py-4">{{ $order->getValueSchemalessAttributes('utm.term') }}</td>
                                </tr>


                            </tbody>
                        </table>
                    @endcomponent
                </div>
            @endif

        </div>

    </div>
@endsection
