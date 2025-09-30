@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <h1>Carrinho abandonado</h1>

        <div class="grid grid-cols-2 gap-3 xl:gap-6">

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-4 md:p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 md:gap-4">

                        <h3>Número total de pedidos</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                <span class="text-2xl font-semibold">{{ $totalAbandonedCarts }}</span>
                            </p>

                        </div>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-1">

                @component('components.card', ['custom' => 'p-4 md:p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 md:gap-4">

                        <h3>Valor Líquido</h3>

                        <div class="mt-auto space-y-3">

                            <p class="flex items-center gap-3">
                                <span
                                    class="text-2xl font-semibold">{{ Number::currency($totalAmountAbandonedCarts, 'BRL', 'pt-br') }}</span>
                            </p>

                        </div>

                    </div>
                @endcomponent

            </div>

        </div>

        <div class="space-y-6">

            <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">

                <form class="w-full flex-1" action="" method="">

                    <div class="grid grid-cols-12 gap-2 md:gap-4">

                        <div class="col-span-12 md:col-span-7">

                            <div class="append">

                                <input placeholder="Pesquisar" type="text" />

                                <button class="append-item-right w-12" type="button">
                                    @include('components.icon', ['icon' => 'search'])
                                </button>

                            </div>

                        </div>

                        <div class="col-span-12 md:col-span-5">

                            {{-- <div class="append">
                                <select
                                    class="pl-24"
                                    id="filter[type]"
                                    name="filter[type]"
                                    onchange="this.form.submit()"
                                >
                                    <option value="">Todos</option>
                                    <option
                                        value="shop"
                                        @selected(request('filter.type') == 'shop')
                                    >
                                        Sou produtor
                                    </option>
                                    <option
                                        value="coproducer"
                                        @selected(request('filter.type') == 'coproducer')
                                    >
                                        Sou coprodutor
                                    </option>
                                    <option
                                        value="affiliate"
                                        @selected(request('filter.type') == 'affiliate')
                                    >
                                        Sou afiliado
                                    </option>
                                </select>
                                <div class="append-item-left px-4">
                                    <span class="font-semibold">Filtrar por:</span>
                                </div>
                            </div> --}}

                            <div class="append">
                                <select class="pl-24" id="filter[payment_method]" name="filter[payment_method]"
                                    onchange="this.form.submit()">
                                    <option value="">Todas</option>
                                    @foreach (\App\Enums\PaymentMethodEnum::getDescriptions() as $paymentMethod)
                                        <option value="{{ $paymentMethod['value'] }}" @selected(request('filter.payment_method') == $paymentMethod['value'])>
                                            {{ $paymentMethod['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="append-item-left px-4">
                                    <span class="font-semibold">Filtrar por:</span>
                                </div>
                            </div>

                        </div>

                    </div>

                </form>

                <button class="button button-outline-primary h-12 w-full gap-1 md:w-auto"
                    data-drawer-target="drawerFilterAbandonendCart" data-drawer-show="drawerFilterAbandonendCart"
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
                            <th class="{{ $sortField == 'id' ? 'desc' : ($sortField == '-id' ? 'asc' : '') }} group cursor-pointer" onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'id' ? '-' . 'id' : 'id', 'page' => null ]) }}'">
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
                            <th class="{{ $sortField == 'name' ? 'desc' : ($sortField == '-name' ? 'asc' : '') }} group cursor-pointer" onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'name' ? '-' . 'name' : 'name', 'page' => null ]) }}'">
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
                            <th class="{{ $sortField == 'product_name' ? 'desc' : ($sortField == '-product_name' ? 'asc' : '') }} group cursor-pointer" onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'product_name' ? '-' . 'product_name' : 'product_name', 'page' => null ]) }}'">
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
                            <th class="{{ $sortField == 'amount' ? 'desc' : ($sortField == '-amount' ? 'asc' : '') }} group cursor-pointer" onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'amount' ? '-' . 'amount' : 'amount', 'page' => null ]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Valor do Produto

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

                                    UTM

                                </div>
                            </th>

                            <th class="{{ $sortField == 'status' ? 'desc' : ($sortField == '-status' ? 'asc' : '') }} group cursor-pointer" onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'status' ? '-' . 'status' : 'status', 'page' => null ]) }}'">
                                <div class="flex items-center justify-between gap-2">

                                    Status

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
                            <th class="{{ $sortField == 'data' ? 'desc' : ($sortField == '-data' ? 'asc' : '') }} group cursor-pointer" onclick="window.location.href = '{{ request()->fullUrlWithQuery(['sort' => $sortField === 'data' ? '-' . 'data' : 'data', 'page' => null ]) }}'">
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
                        @foreach ($abandonedCarts as $abandonedCart)
                            <tr>
                                <td>
                                    <span
                                        class="copyClipboard cursor-pointer rounded-full bg-neutral-200 px-2 py-[3px] text-[10px] font-semibold"
                                        data-tooltip-text="Click para copiar o ID : <br> {{ $abandonedCart->client_abandoned_cart_uuid }}"
                                        data-tooltip-position="right"
                                        data-clipboard-text="{{ $abandonedCart->client_abandoned_cart_uuid }}">
                                        <i class="ti ti-key text-1xl"></i>
                                        <i class="ti ti-copy text-1xl"></i>
                                    </span>
                                </td>
                                <td>{{ $abandonedCart->name }}</td>
                                <td>{{ $abandonedCart->product->parentProduct->name }}</td>
                                <td>{{ $abandonedCart->brazilianAmount }}</td>
                                <td>{{ $abandonedCart->lastTracking?->utm_source ?? '-' }}</td>
                                <td>
                                    <div
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
                                    </div>
                                </td>
                                <td>{{ $abandonedCart->created_at->isoFormat('DD/MM/YYYY HH:mm:ss') }}</td>
                                <td class="text-end">

                                    @component('components.dropdown-button', [
                                        'id' => 'dropdownMoreTableParticipations' . $abandonedCart->client_abandoned_cart_uuid,
                                        'customButton' => 'h-8 w-8 rounded-md hover:bg-neutral-200/50',
                                        'custom' => 'text-xl',
                                    ])
                                        <ul>
                                            <li>
                                                <a class="flex items-center rounded-lg px-3 py-2 hover:bg-neutral-100"
                                                    href="{{ route('dashboard.abandoned-carts.show', $abandonedCart->client_abandoned_cart_uuid) }}">
                                                    Detalhes
                                                </a>
                                            </li>
                                        </ul>
                                    @endcomponent

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $abandonedCarts->withQueryString()->links() }}

        </div>

    </div>
    @push('custom-script')
        <script>
            document.addEventListener("click", function(event) {
                const button = event.target.closest(".copyClipboard");
                if (!button) return;

                const clipboardText = button.getAttribute("data-clipboard-text");

                if (!navigator.clipboard) {
                    notyf.error("Seu navegador não suporta copiar para a área de transferência!");
                    return;
                }

                if (!clipboardText) {
                    notyf.error("Nenhum texto encontrado para copiar!");
                    return;
                }

                navigator.clipboard.writeText(clipboardText)
                    .then(() => notyf.success("Copiado com sucesso!"))
                    .catch(() => notyf.error("Erro ao tentar copiar!"));
            });
        </script>
    @endpush
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterAbandonendCart',
        'title' => 'Pesquisar',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="{{ route('dashboard.abandoned-carts.index') }}" method="GET">
            <div class="col-span-12">
                <label for="filter[client_abandoned_cart_uuid]">Número do carrinho abandonado</label>
                <input type="text" id="filter[client_abandoned_cart_uuid]" name="filter[client_abandoned_cart_uuid]"
                    value="{{ request('filter.client_abandoned_cart_uuid') }}" placeholder="Digite número do pedido" />
            </div>

            <div class="col-span-12">
                <label for="filter[product]">Nome do produto</label>
                <input type="text" id="filter[product]" name="filter[product]" value="{{ request('filter.product') }}"
                    placeholder="Digite o nome do produto" />
            </div>

            <div class="col-span-12">
                <label for="filter[user]">Informações de do cliente</label>
                <input type="text" id="filter[user]" name="filter[user]" value="{{ request('filter.user') }}"
                    placeholder="Digite o nome, e-mail ou telefone do cliente" />
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
                <label for="filter[status]">Status do carrinho abandonado</label>
                <select id="filter[status]" name="filter[status]">
                    <option value="">Todos</option>
                    @foreach (\App\Enums\StatusAbandonedCartEnum::cases() as $case)
                        <option value="{{ $case->name }}" @selected(request('filter.status') == $case->name)>
                            {{ \App\Enums\StatusAbandonedCartEnum::getDescription($case) }}
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
