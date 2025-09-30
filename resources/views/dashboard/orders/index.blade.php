@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between gap-6 md:gap-8 lg:gap-10">
            <h1>Pedidos</h1>

            <a class="button button-outline-primary h-12 w-auto" title="Exportar pedidos para EXCEL"
                href="{{ route('dashboard.orders.index') . '?' . http_build_query(array_merge(request()->all(), ['export_file' => 'excel'])) }}"
                onclick="return confirm('Você tem certeza?')">
                @include('components.icon', [
                    'icon' => 'file_download',
                    'type' => 'fill',
                    'custom' => 'text-xl',
                ])
                Exportar
            </a>
        </div>

        <div class="grid grid-cols-1 gap-3 xl:grid-cols-2 xl:gap-6">

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">

                        <h3>Faturamento médio diário</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                R$
                                <span class="text-2xl font-semibold">
                                    {{ Number::format($averageDailyTurnover, precision: 2, locale: 'pt-br') }}
                                </span>
                            </p>

                            <div class="alert alert-{{ $percentageInfosDailyTurnover['class'] }} mt-4">
                                <div
                                    class="text-{{ $percentageInfosDailyTurnover['class'] }}-600 flex items-center gap-px text-sm font-semibold">
                                    @include('components.icon', [
                                        'icon' => $percentageInfosDailyTurnover['icon'],
                                        'custom' => 'text-xl',
                                    ])
                                    {{ $percentageInfosDailyTurnover['value'] }}
                                </div>
                                <p class="text-sm">
                                    @if (request()->filled('filter.start_at') || request()->filled('filter.end_at'))
                                        Comparado ao período anterior
                                    @else
                                        Comparado a última semana
                                    @endif
                                </p>
                            </div>

                        </div>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">

                        <h3>Faturamento total</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                R$
                                <span class="text-2xl font-semibold">
                                    {{ Number::format($totalRevenue, precision: 2, locale: 'pt-br') }}
                                </span>
                            </p>

                            <div class="alert alert-{{ $percentageInfosTotalRevenue['class'] }} mt-4">
                                <div
                                    class="text-{{ $percentageInfosTotalRevenue['class'] }}-600 flex items-center gap-px text-sm font-semibold">
                                    @include('components.icon', [
                                        'icon' => $percentageInfosTotalRevenue['icon'],
                                        'custom' => 'text-xl',
                                    ])
                                    {{ $percentageInfosTotalRevenue['value'] }}
                                </div>
                                <p class="text-sm">
                                    @if (request()->filled('filter.start_at') || request()->filled('filter.end_at'))
                                        Comparado ao período anterior
                                    @else
                                        Comparado a última semana
                                    @endif
                                </p>
                            </div>

                        </div>

                    </div>
                @endcomponent

            </div>

        </div>

        <div class="space-y-6">

            <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">

                <form class="w-full flex-1" action="{{ route('dashboard.orders.index') }}" method="GET">

                    <div class="grid grid-cols-12 gap-2 md:gap-4">

                        <div class="col-span-12 md:col-span-7">

                            <div class="append">

                                <input type="text" id="filter[user]" name="filter[user]"
                                    value="{{ request('filter.user') }}"
                                    placeholder="Pesquisar por nome, CPF, e-mail ou telefone" />

                                <button class="append-item-right w-12" type="button">
                                    @include('components.icon', ['icon' => 'search'])
                                </button>

                            </div>

                        </div>

                        <div class="col-span-12 md:col-span-5">

                            <div class="append">
                                <select class="pl-24" id="filter[type]" name="filter[type]" onchange="this.form.submit()">
                                    <option value="">Todos</option>
                                    <option value="shop" @selected(request('filter.type') == 'shop')>
                                        Sou produtor
                                    </option>
                                    <option value="coproducer" @selected(request('filter.type') == 'coproducer')>
                                        Sou coprodutor
                                    </option>
                                    <option value="affiliate" @selected(request('filter.type') == 'affiliate')>
                                        Sou afiliado
                                    </option>
                                </select>

                                <div class="append-item-left px-4">
                                    <span class="font-semibold">Filtrar por:</span>
                                </div>
                            </div>

                        </div>

                    </div>

                </form>

                <button class="button button-outline-primary h-12 w-full gap-1 md:w-auto"
                    data-drawer-target="drawerFilterSales" data-drawer-show="drawerFilterSales"
                    data-drawer-placement="right" type="button">
                    @include('components.icon', [
                        'icon' => 'filter_alt',
                        'type' => 'fill',
                        'custom' => 'text-xl',
                    ])
                    Filtros de pesquisa
                </button>

            </div>

            <div class="overflow-x-scroll xl:overflow-visible">

                @php
                    $sortField = request('sort');
                @endphp

                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="{{ $sortField == 'id' ? 'desc' : ($sortField == '-id' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'id' ? '-' . 'id' : 'id', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Nº#

                                    <div class="relative flex h-6 w-6 flex-col items-center justify-center">
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_up',
                                            'custom' =>
                                                'absolute bottom-1 text-neutral-400 group-[&.asc]:text-neutral-700',
                                        ])
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_down',
                                            'custom' =>
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700 ',
                                        ])
                                    </div>

                                </div>
                            </th>
                            <th class="{{ $sortField == 'customer_name' ? 'desc' : ($sortField == '-customer_name' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'customer_name' ? '-' . 'customer_name' : 'customer_name', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Nome do cliente

                                    <div class="relative flex h-6 w-6 flex-col items-center justify-center">
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_up',
                                            'custom' =>
                                                'absolute bottom-1 text-neutral-400 group-[&.asc]:text-neutral-700',
                                        ])
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_down',
                                            'custom' =>
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700',
                                        ])
                                    </div>

                                </div>
                            </th>
                            <th class="{{ $sortField == 'product_name' ? 'desc' : ($sortField == '-product_name' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'product_name' ? '-' . 'product_name' : 'product_name', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Nome do produto

                                    <div class="relative flex h-6 w-6 flex-col items-center justify-center">
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_up',
                                            'custom' =>
                                                'absolute bottom-1 text-neutral-400 group-[&.asc]:text-neutral-700',
                                        ])
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_down',
                                            'custom' =>
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700',
                                        ])
                                    </div>

                                </div>
                            </th>
                            <th class="group cursor-pointer">
                                <div class="flex items-center justify-between gap-2">

                                    UTM

                                </div>
                            </th>

                            <th class="{{ $sortField == 'amount' ? 'desc' : ($sortField == '-amount' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'amount' ? '-' . 'amount' : 'amount', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Valor

                                    <div class="relative flex h-6 w-6 flex-col items-center justify-center">
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_up',
                                            'custom' =>
                                                'absolute bottom-1 text-neutral-400 group-[&.asc]:text-neutral-700',
                                        ])
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_down',
                                            'custom' =>
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700',
                                        ])
                                    </div>

                                </div>
                            </th>

                            <th class="group cursor-pointer">
                                <div class="flex items-center justify-between gap-2">

                                    Valor líquido

                                </div>
                            </th>

                            <th class="group cursor-pointer">
                                <div class="flex items-center justify-between gap-2">

                                    Valor a receber

                                </div>
                            </th>

                            <th class="{{ $sortField == 'payment_method' ? 'desc' : ($sortField == '-payment_method' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'payment_method' ? '-' . 'payment_method' : 'payment_method', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Forma de <br> pagamento

                                    <div class="relative flex h-6 w-6 flex-col items-center justify-center">
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_up',
                                            'custom' =>
                                                'absolute bottom-1 text-neutral-400 group-[&.asc]:text-neutral-700',
                                        ])
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_down',
                                            'custom' =>
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700',
                                        ])
                                    </div>

                                </div>
                            </th>
                            <th class="{{ $sortField == 'payment_status' ? 'desc' : ($sortField == '-payment_status' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'payment_status' ? '-' . 'payment_status' : 'payment_status', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Status pagamento

                                    <div class="relative flex h-6 w-6 flex-col items-center justify-center">
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_up',
                                            'custom' =>
                                                'absolute bottom-1 text-neutral-400 group-[&.asc]:text-neutral-700',
                                        ])
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_down',
                                            'custom' =>
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700',
                                        ])
                                    </div>

                                </div>
                            </th>
                            <th class="{{ $sortField == 'data' ? 'desc' : ($sortField == '-data' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'data' ? '-' . 'data' : 'data', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Data

                                    <div class="relative flex h-6 w-6 flex-col items-center justify-center">
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_up',
                                            'custom' =>
                                                'absolute bottom-1 text-neutral-400 group-[&.asc]:text-neutral-700',
                                        ])
                                        @include('components.icon', [
                                            'icon' => 'arrow_drop_down',
                                            'custom' =>
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700',
                                        ])
                                    </div>

                                </div>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>
                                    <span
                                        class="copyClipboard cursor-pointer rounded-full bg-neutral-200 px-2 py-[3px] text-[10px] font-semibold"
                                        data-tooltip-text="Click para copiar o ID : <br> {{ $order->client_orders_uuid }}"
                                        data-tooltip-position="right"
                                        data-clipboard-text="{{ $order->client_orders_uuid }}">
                                        <i class="ti ti-key text-1xl"></i>
                                        <i class="ti ti-copy text-1xl"></i>
                                    </span>
                                </td>
                                <td class="!whitespace-normal">{{ $order->user->name }}</td>
                                <td class="!whitespace-normal">
                                    {{ $order->items->implode('product.parentProduct.name', ', ') }}</td>
                                <td>{{ $order->getValueSchemalessAttributes('utm.source') ?? '-' }}</td>
                                <td>{{ $order->brazilianTotalAmountItems }}</td>
                                <td>{{ $order->brazilianShopAmount }}</td>
                                <td>{{ $order->brazilianAmountByTypeUser($user, $shopUser) }}</td>
                                <td>{{ $order->paymentMethod }}</td>
                                <td>
                                    <div
                                        class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' => 'text-xs ' . $order->classCssPaymentStatus,
                                        ])
                                        {{ $order->paymentStatus }}
                                    </div>
                                </td>
                                <td>{{ $order->created_at->isoFormat('DD/MM/YYYY HH:mm:ss') }}</td>
                                <td class="text-end">

                                    @component('components.dropdown-button', [
                                        'id' => 'dropdownMoreTableParticipations' . $loop->iteration,
                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                        'custom' => 'text-xl',
                                    ])
                                        <ul>
                                            <li>
                                                <a class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                    href="{{ route('dashboard.orders.show', ['orderUuid' => $order->client_orders_uuid]) }}"
                                                    title="Detalhes do pedido">
                                                    Detalhes
                                                </a>
                                            </li>

                                            @if ($order->isPaid())
                                                <li>
                                                    <form method="POST"
                                                        action="{{ route('dashboard.orders.refund', $order) }}"
                                                        onsubmit="return confirm('Tem certeza que deseja solicitar estorno do pedido do cliente {{ $order->user->name }} no valor de {{ $order->brazilianPrice }}? \n\n ️O saldo correspondente será debitado da sua conta e o estorno não poderá ser desfeito.')">
                                                        @csrf

                                                        <button type="submit"
                                                            class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                            title="Solicitar estorno">
                                                            Solicitar estorno
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    @endcomponent

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">
                                    Nenhum registro encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $orders->withQueryString()->links() }}

        </div>

    </div>
    @push('custom-script')
        <script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>
    @endpush
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterSales',
        'title' => 'Filtros de Pesquisa',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="{{ route('dashboard.orders.index') }}" method="GET">

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="from">Tipo</label>
                    <select id="filter[type]" name="filter[type]">
                        <option value="">Todos</option>
                        <option value="shop" @selected(request('filter.type') == 'shop')>
                            Sou produtor
                        </option>
                        <option value="coproducer" @selected(request('filter.type') == 'coproducer')>
                            Sou coprodutor
                        </option>
                        <option value="affiliate" @selected(request('filter.type') == 'affiliate')>
                            Sou afiliado
                        </option>
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="filter[client_orders_uuid]">Número do pedido</label>
                    <input type="text" id="filter[client_orders_uuid]" name="filter[client_orders_uuid]"
                        value="{{ request('filter.client_orders_uuid') }}" placeholder="Digite número do pedido" />
                </div>

                <div class="col-span-12">
                    <label for="filter[product]">Nome do produto</label>
                    <input type="text" id="filter[product]" name="filter[product]"
                        value="{{ request('filter.product') }}" placeholder="Digite o nome do produto" />
                </div>

                <div class="col-span-12">
                    <label for="filter[user]">Informações de do cliente</label>
                    <input type="text" id="filter[user]" name="filter[user]" value="{{ request('filter.user') }}"
                        placeholder="Digite o nome, CPF, e-mail ou telefone do cliente" />
                </div>

                <div class="col-span-12">
                    <label for="filter[payment_method]">Forma de pagamento</label>
                    <select id="filter[payment_method]" name="filter[payment_method]">
                        <option value="">Todas</option>
                        @foreach (\App\Enums\PaymentMethodEnum::getDescriptions() as $paymentMethod)
                            <option value="{{ $paymentMethod['value'] }}" @selected(request('filter.payment_method') == $paymentMethod['value'])>
                                {{ $paymentMethod['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12">
                    <label for="filter[payment_status]">Status do pagamento</label>
                    <select id="filter[payment_status]" name="filter[payment_status]">
                        <option value="">Todos</option>
                        @foreach (\App\Enums\PaymentStatusEnum::getDescriptions() as $paymentStatus)
                            <option value="{{ $paymentStatus['value'] }}" @selected(request('filter.payment_status') == $paymentStatus['value'])>
                                {{ $paymentStatus['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label for="filter[start_at]">A partir de</label>
                    <input type="datetime-local" id="filter[start_at]" name="filter[start_at]"
                        value="{{ request('filter.start_at') }}" />
                </div>

                <div class="col-span-12 lg:col-span-6">
                    <label for="filter[end_at]">Até</label>
                    <input type="datetime-local" id="filter[end_at]" name="filter[end_at]"
                        value="{{ request('filter.end_at') }}" />
                </div>
            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Pesquisar
            </button>

        </form>
    @endcomponent
@endpush
