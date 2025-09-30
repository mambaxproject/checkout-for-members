@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between gap-6 md:gap-8 lg:gap-10">
            <h1>Assinaturas</h1>

            <a class="button button-outline-primary h-12 w-auto" title="Exportar assinaturas para EXCEL"
               href="{{ route('dashboard.subscriptions.index') . '?' . http_build_query(array_merge(request()->all(), ['export_file' => 'excel'])) }}"
               onclick="return confirm('Você tem certeza?')">
                @include('components.icon', [
                    'icon' => 'file_download',
                    'type' => 'fill',
                    'custom' => 'text-xl',
                ])
                Exportar
            </a>
        </div>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:gap-6">

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-4 md:p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">

                        <h3>Faturamento de vendas recorrentes</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                R$
                                <span class="text-2xl font-semibold">
                                    {{ \Illuminate\Support\Number::format($totalRevenue, locale: 'pt_BR') }}
                                </span>
                            </p>

                            <!--
                                                                                    <div class="alert alert-success mt-4">
                                                                                        <div class="flex items-center gap-px text-sm font-semibold text-success-600">
                                                                                            @include(
                                                                                                'components.icon',
                                                                                                [
                                                                                                    'icon' =>
                                                                                                        'arrow_upward_alt',
                                                                                                    'custom' =>
                                                                                                        'text-xl',
                                                                                                ]
                                                                                            )
                                                                                            50%
                                                                                        </div>
                                                                                        <p class="text-sm">Comparado a última semana</p>
                                                                                    </div>
                                                                                    -->

                        </div>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-4 md:p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">

                        <h3>Total de assinaturas recorrentes</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                <span class="text-2xl font-semibold">{{ $quantityPaidSubscriptions }}</span>
                            </p>

                            <!--
                                                                                    <div class="alert alert-danger mt-4">
                                                                                        <div class="flex items-center gap-px text-sm font-semibold text-danger-600">
                                                                                            @include(
                                                                                                'components.icon',
                                                                                                [
                                                                                                    'icon' =>
                                                                                                        'arrow_upward_alt',
                                                                                                    'custom' =>
                                                                                                        'text-xl',
                                                                                                ]
                                                                                            )
                                                                                            50%
                                                                                        </div>
                                                                                        <p class="text-sm">Comparado a última semana</p>
                                                                                    </div>
                                                                                    -->

                        </div>

                    </div>
                @endcomponent

            </div>

        </div>

        <div class="space-y-6">

            <div class="flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">

                <form class="w-full flex-1" action="{{ route('dashboard.subscriptions.index') }}" method="GET">

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
                    data-drawer-target="drawerFilterSignature" data-drawer-show="drawerFilterSignature"
                    data-drawer-placement="right" type="button">
                    @include('components.icon', [
                        'icon' => 'filter_alt',
                        'type' => 'fill',
                        'custom' => 'text-xl',
                    ])
                    Filtros de pesquisa
                </button>

            </div>

            <div class="overflow-x-scroll md:overflow-visible">

                @php
                    $sortField = request('sort');
                @endphp

                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="{{ $sortField == 'id' ? 'desc' : ($sortField == '-id' ? 'asc' : '') }} group cursor-pointer"
                                onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'id' ? '-' . 'id' : 'id', 'page' => null]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    ID

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
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700 ',
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
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700 ',
                                        ])
                                    </div>

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
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700 ',
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

                            <th class="group cursor-pointer">
                                <div class="flex items-center justify-between gap-2">

                                    UTM

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
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700 ',
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
                                                'absolute top-1 text-neutral-400 group-[&.desc]:text-neutral-700 ',
                                        ])
                                    </div>

                                </div>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $subscription)
                            <tr>
                                <td> <span
                                        class="copyClipboard cursor-pointer rounded-full bg-neutral-200 px-2 py-[3px] text-[10px] font-semibold"
                                        data-tooltip-text="Click para copiar o ID : <br> {{ $subscription->client_orders_uuid }}"
                                        data-tooltip-position="right"
                                        data-clipboard-text="{{ $subscription->client_orders_uuid }}">
                                        <i class="ti ti-key text-1xl"></i>
                                        <i class="ti ti-copy text-1xl"></i>
                                    </span></td>
                                <td>{{ $subscription->user->name }}</td>
                                <td>{{ $subscription->items->implode('product.parentProduct.name', ', ') }}</td>
                                <td>{{ $subscription->brazilianPrice }}</td>
                                <td>{{ $subscription->brazilianShopAmount }}</td>
                                <td>{{ $subscription->brazilianAmountByTypeUser($user, $shopUser) }}</td>
                                <td>{{ $subscription->getValueSchemalessAttributes('utm.source') ?? '-' }}</td>
                                <td>
                                    <div
                                        class="flex w-fit items-center gap-2 rounded-full border border-neutral-600 px-3 py-1">
                                        @include('components.icon', [
                                            'icon' => 'circle',
                                            'type' => 'fill',
                                            'custom' => 'text-xs ' . $subscription->classCssPaymentStatus,
                                        ])
                                        {{ $subscription->paymentStatus }}
                                    </div>
                                </td>
                                <td>{{ $subscription->created_at->isoFormat('DD/MM/YYYY HH:mm:ss') }}</td>
                                <td class="text-end">

                                    @component('components.dropdown-button', [
                                        'id' => 'dropdownMoreTableParticipations' . $loop->iteration,
                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                        'custom' => 'text-xl',
                                    ])
                                        <ul>
                                            <li>
                                                <a class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                    href="{{ route('dashboard.subscriptions.show', ['orderUuid' => $subscription->client_orders_uuid]) }}"
                                                    title="Detalhes da assinatura">
                                                    Detalhes
                                                </a>
                                            </li>
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

            {{ $subscriptions->links() }}

        </div>

    </div>
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterSignature',
        'title' => 'Filtros de Pesquisa',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="{{ route('dashboard.subscriptions.index') }}" method="GET">

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
                    <label for="filter[client_orders_uuid]">Número da assinatura</label>
                    <input type="text" id="filter[client_orders_uuid]" name="filter[client_orders_uuid]"
                        value="{{ request('filter.client_orders_uuid') }}" placeholder="Digite número da assinatura" />
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
<script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>
