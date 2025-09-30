@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex flex-col gap-y-3 md:flex-row md:items-center md:justify-between md:gap-y-0 md:px-6">

            <h1>Olá, {{ user()->shortName }}</h1>

            <div class="flex items-center justify-between gap-4">

                <button class="button button-primary h-10 gap-1 rounded-full" data-drawer-target="drawerFilterDashboard"
                    data-drawer-show="drawerFilterDashboard" data-drawer-placement="right" type="button">
                    @include('components.icon', [
                        'icon' => 'filter_alt',
                        'custom' => 'text-xl',
                    ])
                    Filtrar
                </button>

            </div>

        </div>

        <div class="grid grid-cols-12 gap-4 md:gap-6">

            <div class="col-span-12 md:col-span-4 xl:col-span-4">
                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="flex h-full flex-col">
                        <h3>Faturamento Total com Comissionados</h3>
                        <div class="">
                            @if ($totalValueCommissioningFromPaidOrdersShop)
                                <p class="text-sm">
                                    R$
                                    <span class="mask-value-money text-xl font-bold">
                                        {{ Number::currency($totalValueCommissioningFromPaidOrdersShop, 'BRL', 'pt-br') }}
                                    </span>
                                </p>
                            @else
                                <span class="text-xs italic text-neutral-400">Dados indisponíveis</span>
                            @endif
                        </div>
                        <p class="mt-auto text-xs italic text-gray-400">* Valores líquidos</p>
                    </div>
                @endcomponent
            </div>

            <div class="col-span-12 md:col-span-4 xl:col-span-4">
                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="flex h-full flex-col">
                        <h3>Total de Comissionados Ativos</h3>
                        <div class="">
                            <p class="text-sm">
                                <span class="mask-value-money text-xl font-bold">
                                    {{ $quantityCommissionedShop }}
                                </span>
                            </p>
                        </div>
                    </div>
                @endcomponent
            </div>

            <div class="col-span-12 md:col-span-4 xl:col-span-4">
                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="flex h-full flex-col">
                        <h3>Vendas realizadas por comissionados</h3>
                        <div class="">
                            <p class="text-sm">
                                <span class="mask-value-money text-xl font-bold">
                                    {{ $quantityOrdersCommissionedShop }}
                                </span>
                            </p>
                        </div>
                    </div>
                @endcomponent
            </div>

            <div class="col-span-12">

                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="space-y-6">

                        <h3>Seus melhores comissionados</h3>

                        <div class="">

                            <div class="overflow-hidden rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="table-green table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="w-[50%]">Comissionado</th>
                                                <th>Faturamento</th>
                                                <th>Vendas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($topCommissionedShop as $affiliate)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="uppercase">{{ Str::limit($affiliate->commissioned_name, 30) }}
                                                    </td>
                                                    <td>{{ Number::currency($affiliate->total_amount, 'BRL', 'pt-br') }}</td>
                                                    <td>{{ $affiliate->quantity_orders }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        <em>Aguardando sua primeira venda :)</em>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-12">

                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="space-y-6">

                        <h3>Vendas de comissionados</h3>

                        <div class="">

                            <div class="overflow-hidden rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="table-green table">
                                        <thead>
                                            <tr>
                                                <th>Nº#</th>
                                                <th>Comissionado</th>
                                                <th>Tipo</th>
                                                <th>Produto</th>
                                                <th>Data da Venda</th>
                                                <th>Valor da Venda</th>
                                                <th>Comissão</th>
                                                <th>Valor da Comissão</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($commissioningOrders as $item)
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="copyClipboard cursor-pointer rounded-full bg-neutral-200 px-2 py-[3px] text-[10px] font-semibold"
                                                            data-tooltip-text="Click para copiar o ID : <br> {{ $item?->order?->client_orders_uuid }}"
                                                            data-tooltip-position="right"
                                                            data-clipboard-text="{{ $item?->order?->client_orders_uuid }}">
                                                            <i class="ti ti-key text-1xl"></i>
                                                            <i class="ti ti-copy text-1xl"></i>
                                                        </span>
                                                    </td>

                                                    <td>{{ $item?->order?->client_orders_uuid }}</td>
                                                    <td class="uppercase">{{ $item->commissioned->name }}</td>
                                                    <td>{{ $item->typeTranslated }}</td>
                                                    <td>{{ $item?->order?->items->implode('product.parentProduct.name', ', ') }}
                                                    </td>
                                                    <td>{{ $item?->order?->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $item?->order?->brazilianPrice }}</td>
                                                    <td>{{ Number::currency($item->value, 'BRL', 'pt-br') }}</td>
                                                    <td>{{ $item->valueCommissionFormatted }}</td>
                                                    <td class="uppercase">{{ $item?->order?->paymentStatus }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        <em>Aguardando suas primeiras vendas :)</em>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>

                        {{ $commissioningOrders->links() }}

                    </div>
                @endcomponent

            </div>

        </div>

    </div>
@endsection

@push('floating')
    <!-- DRAWER FILTER -->
    @component('components.drawer', [
        'id' => 'drawerFilterDashboard',
        'title' => 'Pesquisar por',
        'custom' => 'persist-inputs max-w-xl',
    ])
        <form action="{{ route('dashboard.home.index') }}">

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label for="filter[period]">Data</label>
                    <div class="append flex-1">
                        <input class="form-input pl-10" id="filter[period]" name="filter[period]" autocomplete="off"
                            type="text" value="{{ request('filter.period') }}" />
                        <div class="append-item-left w-10">
                            @include('components.icon', [
                                'icon' => 'calendar_month',
                                'custom' => 'text-xl text-gray-400',
                            ])
                        </div>
                    </div>
                </div>

            </div>

            <button class="button button-primary mt-8 h-12 w-full rounded-full" type="submit">
                Pesquisar
            </button>

        </form>
    @endcomponent
@endpush

@section('script')
    <script src="{{ asset('js/dashboard/startDateRangePicker.js') }}"></script>
    <script src="{{ asset('js/dashboard/copyToClipboard.js') }}"></script>
    <script>
        $(document).ready(function() {
            startDateRangePicker("#filter\\[period\\]", {
                opens: "left",
                ranges: {
                    'Hoje': [moment(), moment()],
                    'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                    'Este Mês': [moment().startOf('month'), moment().endOf('month')],
                    'Mês Passado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
            })
        })
    </script>
@endsection
