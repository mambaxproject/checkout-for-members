@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between">
            <h1>Assinatura <span
                    class="copyClipboard cursor-pointer rounded-full bg-neutral-200 px-2 py-[3px] text-[10px] font-semibold"
                    data-tooltip-text="Click para copiar o ID : <br> {{ $order->client_orders_uuid }}"
                    data-tooltip-position="right" data-clipboard-text="{{ $order->client_orders_uuid }}">
                    <i class="ti ti-key text-1xl"></i>
                    <i class="ti ti-copy text-1xl"></i>
                </span></h1>

            <div class="flex gap-2">
                @if ($order->isPaid() && filled($order->payment->recurrencyId))
                    <div>
                        <button type="button" class="button button-primary h-12 gap-1 rounded-full"
                            title="Atualizar cartão assinatura" data-modal-target="cardApproveModal"
                            data-modal-toggle="cardApproveModal">
                            Atualizar cartão assinatura
                        </button>

                    </div>

                    <div>
                        <button type="button" class="button button-primary h-12 gap-1 rounded-full"
                            title="Atualizar oferta assinatura" data-modal-target="modalUpdateLinkOfferCustomer"
                            data-modal-toggle="modalUpdateLinkOfferCustomer">
                            Atualizar oferta assinatura
                        </button>
                    </div>
                @endif

                @if ($order->isUnpaid())
                    <div>
                        <form method="POST" action="{{ route('dashboard.subscriptions.chargeRetry', $order) }}"
                            onsubmit="return confirm('Tem certeza?')">
                            @csrf

                            <button type="submit" class="button button-primary h-12 gap-1 rounded-full"
                                title="Retentativa de cobrança">
                                Retentativa de cobrança
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <nav class="flex items-center border-b border-neutral-300" data-tabs-toggle="#page-tab-content">

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-order" aria-selected="true" role="tab" type="button">
                Assinatura
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

            @if ($order->getValueSchemalessAttributes('utm'))
                <button
                    class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                    data-tabs-target="#tab-utm" aria-selected="false" role="tab" type="button">
                    UTM
                </button>
            @endif

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-history" aria-selected="false" role="tab" type="button">
                Histórico
            </button>

            <button
                class="border-b-2 px-6 py-4 hover:border-primary aria-selected:border-primary aria-selected:text-neutral-800"
                data-tabs-target="#tab-transactions" aria-selected="false" role="tab" type="button">
                Cobranças
            </button>

        </nav>

        <div id="page-tab-content">

            <div class="hidden" id="tab-order">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <table class="w-full">
                        <tbody class="divide-y">
                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">ID da assinatura</td>
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
                                    <td class="px-3 py-4">Sou afiliado</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Valor</td>
                                <td class="px-3 py-4">{{ $order->brazilianPrice }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Nº de cobranças</td>
                                <td class="px-3 py-4">{{ $order->item->product->numberPaymentsRecurringPaymentFormatted }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Periodicidade</td>
                                <td class="px-3 py-4">{{ $order->item->product->cyclePaymentTranslated }}</td>
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
                                <td class="px-3 py-4">{{ $order->user->phone_number }}</td>
                            </tr>

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">CPF</td>
                                <td class="px-3 py-4">{{ $order->user->document_number }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endcomponent
            </div>

            <div class="hidden" id="tab-value">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <table class="w-full">
                        <tbody class="divide-y">
                            @if ($order->item->product->hasFirstPayment)
                                <tr>
                                    <td class="px-3 py-4 font-semibold md:w-1/3">Preço primeiro pagamento</td>
                                    <td class="px-3 py-4">{{ $order->brazilianFirstPrice }}</td>
                                </tr>
                            @endif

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

                            <tr>
                                <td class="px-3 py-4 font-semibold md:w-1/3">Valor</td>
                                <td class="px-3 py-4">{{ $order->brazilianPrice }}</td>
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

            <div class="hidden" id="tab-history">
                @component('components.card', ['custom' => 'p-2 xl:p-4 h-full'])
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <tbody class="divide-y">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-3 py-4 font-semibold md:w-1/3">Data</th>
                                        <th class="px-3 py-4 font-semibold">Descrição</th>
                                    </tr>
                                </thead>
                                @forelse ($order->comments as $comment)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-3 py-4">
                                            {{ $comment->created_at->isoFormat('dddd, DD/MM/YYYY [às] HH:mm') }}
                                        </td>
                                        <td class="px-3 py-4">{{ $comment->comment }}</td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td class="px-3 py-4" colspan="2">Nenhum histórico encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endcomponent
            </div>

            <div class="hidden" id="tab-transactions">
                @component('components.card', ['custom' => 'p-2 xl:p-4 h-full'])
                    @isset($detailsSubscription['recurrencyNextBillingDate'])
                        <div class="flex justify-between items-center mb-7">
                            <div></div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-700">Sua próxima cobrança é em:</span>
                                <span class="ml-2 text-gray-900 border border-neutral-300 rounded-lg p-2">
                                    {{ \Carbon\Carbon::parse($detailsSubscription['recurrencyNextBillingDate'])->isoFormat('dddd, DD/MM/YYYY') }}
                                </span>
                            </div>
                        </div>
                    @endisset

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <tbody class="divide-y">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-3 py-4 font-semibold">Data</th>
                                        <th class="px-3 py-4 font-semibold">Valor</th>
                                        <th class="px-3 py-4 font-semibold">Forma de pagamento</th>
                                        <th class="px-3 py-4 font-semibold">Status de pagamento</th>
                                    </tr>
                                </thead>
                                @forelse ($transactionsSubscription as $transaction)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-3 py-4">
                                            {{ \Carbon\Carbon::parse($transaction['transactionDate'])->isoFormat('dddd, DD/MM/YYYY HH:mm') }}
                                        </td>
                                        <td class="px-3 py-4">
                                            {{ \Illuminate\Support\Number::currency($transaction['value'], 'BRL', 'pt-br') }}
                                        </td>
                                        <td class="px-3 py-4">
                                            <p class="small italic mt-2">
                                                {{ $transaction['creditCard']['creditCardBrand'] }}
                                                •••• •••• •••• {{ $transaction['creditCard']['creditCardNumber'] }}
                                            </p>
                                        </td>
                                        <td class="px-3 py-4">
                                            <span
                                                class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                                {{ \App\Models\Order::getTextByPaymentStatus($transaction['statusTransaction']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td class="px-3 py-4" colspan="4">
                                            Ainda não há cobranças registradas para esta assinatura.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endcomponent
            </div>

        </div>

    </div>

    <div id="modalUpdateLinkOfferCustomer" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <form method="POST"
                        action="{{ route('dashboard.subscriptions.sendLinkUpdateOfferCustomer', $order) }}"
                        onsubmit="return confirm('Enviar link de atualização de oferta para o(a) cliente {{ $order->user->name }}? \n\n Um link seguro será enviado ao e-mail do(a) cliente com validade de 24 horas.')">
                        @csrf

                        <div class="mb-4">
                            <label for="offer_id" class="block mb-3 font-medium text-gray-900 dark:text-white">
                                Selecione a oferta que o cliente deseja migrar:
                            </label>
                            <select id="offer_id" name="offer_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                <option value="" disabled selected>Selecione uma oferta</option>
                                @foreach ($activeOffers as $offer)
                                    <option value="{{ $offer->id }}">
                                        {{ $offer->name }} - {{ $offer->brazilianPrice }}

                                        @if ($offer->price > $order->amount)
                                            (Upgrade)
                                        @elseif ($offer->price < $order->amount)
                                            (Downgrade)
                                        @else
                                            (Mesma oferta)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="button button-primary h-12 gap-1 rounded-full w-full"
                            title="Atualizar oferta assinatura">
                            Enviar link de atualização de oferta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @component('components.modal', [
        'id' => 'cardApproveModal',
        'title' => '',
    ])
        <div class="pb-5 flex flex-col h-full">
            <form method="POST" action="{{ route('dashboard.subscriptions.sendLinkUpdateCreditCardCustomer', $order) }}"
                class="flex flex-col justify-between h-full">
                @csrf
                <div class="flex-grow">
                    Enviar link de atualização de oferta para o(a) cliente <strong>{{ $order->user->name }}?</strong>
                    <p>Um link seguro será enviado ao e-mail do(a) cliente com validade de 24 horas.
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button type="button" data-modal-toggle="cardApproveModal"
                        class="button button-primary h-12 gap-1 rounded-full bg-danger-200 hover:bg-danger-300 text-danger-800 ">
                        Não
                    </button>
                    <button type="submit" class="button button-primary h-12 gap-1 rounded-full">
                        Sim
                    </button>
                </div>
            </form>
        </div>
    @endcomponent
    
    <script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>
@endsection
