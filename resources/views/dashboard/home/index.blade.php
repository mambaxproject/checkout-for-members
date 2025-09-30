@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex flex-col gap-y-3 md:flex-row md:items-center md:justify-between md:gap-y-0 md:px-6">

            <h1>Olá, {{ user()->shortName }}</h1>

            <div class="flex items-center justify-between gap-4">

                <div class="flex items-center gap-2">
                    <span class="text-sm">Ver os valores</span>
                    @include('components.toggle', [
                        'id' => 'toggleMaskMoney',
                        'custom' => '',
                        'isChecked' => false,
                        'contentEmpty' => true,
                    ])
                </div>

                <button
                    class="button button-primary h-10 gap-1 rounded-full"
                    data-drawer-target="drawerFilterDashboard"
                    data-drawer-show="drawerFilterDashboard"
                    data-drawer-placement="right"
                    type="button"
                >
                    @include('components.icon', [
                        'icon' => 'filter_alt',
                        'custom' => 'text-xl',
                    ])
                    Filtrar
                </button>

            </div>

        </div>

        <div class="grid grid-cols-12 gap-4 md:gap-6">

            <div class="col-span-12 md:col-span-6 xl:col-span-4">
                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="flex h-full flex-col">
                        <h3>Faturamento de pedidos pagos</h3>
                        <div class="">
                            @if ($totalRevenuePaidOrders)
                                <p class="text-sm">
                                    R$
                                    <span class="mask-value-money text-xl font-bold">
                                        {{ Number::format($totalRevenuePaidOrders, precision: 2, locale: 'pt-br') }}
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

            <div class="col-span-12 md:col-span-6 xl:col-span-4">
                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="flex h-full flex-col">
                        <h3>Vendas por tipo de pagamento</h3>
                        <div class="mt-2 grid grid-cols-3">
                            <div class="col-span-1">
                                <div class="flex flex-col">
                                    <h4>Cartão: <span class="mask-value-money">{{ $infosOrdersByPaymentMethod['CREDIT_CARD']?->total_orders ?? 0 }}</span></h4>
                                    @isset($infosOrdersByPaymentMethod['CREDIT_CARD']?->percentage)
                                        <p class="text-sm">
                                            <span class="mask-value-money text-base font-semibold">
                                                {{ Number::percentage($infosOrdersByPaymentMethod['CREDIT_CARD']?->percentage, precision: 2, locale: 'pt-br') }}
                                            </span>
                                        </p>
                                    @else
                                        <span class="text-xs italic text-neutral-400">Dados indisponíveis</span>
                                    @endisset
                                </div>
                            </div>
                            <div class="col-span-1">
                                <div class="flex flex-col">
                                    <h4>Pix: <span class="mask-value-money">{{ $infosOrdersByPaymentMethod['PIX']?->total_orders ?? 0 }}</span></h4>
                                    @isset($infosOrdersByPaymentMethod['PIX']?->percentage)
                                        <p class="text-sm">
                                            <span class="mask-value-money text-base font-semibold">
                                                {{ Number::percentage($infosOrdersByPaymentMethod['PIX']?->percentage, precision: 2, locale: 'pt-br') }}
                                            </span>
                                        </p>
                                    @else
                                        <span class="text-xs italic text-neutral-400">Dados indisponíveis</span>
                                    @endisset
                                </div>
                            </div>
                            <div class="col-span-1">
                                <div class="flex flex-col">
                                    <h4>Boleto: <span class="mask-value-money">{{ $infosOrdersByPaymentMethod['BILLET']?->total_orders ?? 0 }}</span></h4>
                                    @isset($infosOrdersByPaymentMethod['BILLET']?->percentage)
                                        <p class="text-sm">
                                            <span class="mask-value-money text-base font-semibold">
                                                {{ Number::percentage($infosOrdersByPaymentMethod['BILLET']?->percentage, precision: 2, locale: 'pt-br') }}
                                            </span>
                                        </p>
                                    @else
                                        <span class="text-xs italic text-neutral-400">Dados indisponíveis</span>
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>

            <div class="col-span-12 md:col-span-6 xl:col-span-4">
                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="flex h-full flex-col">
                        <h3>Comissionamento</h3>
                        <div class="mt-2 grid grid-cols-2">
                            <div class="col-span-1">
                                <div class="flex flex-col">
                                    <h4>Coprodutor:</h4>

                                    @if ($totalCommissionCoproducerPaidOrders)
                                        <p class="text-sm">
                                            R$
                                            <span class="mask-value-money text-base font-semibold">
                                                {{ Number::format($totalCommissionCoproducerPaidOrders, precision: 2, locale: 'pt-br') }}
                                            </span>
                                        </p>
                                    @else
                                        <span class="text-xs italic text-neutral-400">Dados indisponíveis</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-span-1">
                                <div class="flex flex-col">
                                    <h4>Afiliado:</h4>

                                    @if ($totalCommissionAffiliatePaidOrders)
                                        <p class="text-sm">
                                            R$
                                            <span class="mask-value-money text-base font-semibold">
                                                {{ Number::format($totalCommissionAffiliatePaidOrders, precision: 2, locale: 'pt-br') }}
                                            </span>
                                        </p>
                                    @else
                                        <span class="text-xs italic text-neutral-400">Dados indisponíveis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>

            <div class="col-span-12">

                @component('components.card', ['custom' => 'p-6'])
                    <div class="space-y-6">

                        <h3>Faturamento total por dia</h3>

                        <div
                            class="space-y-6"
                            id="containerChartOrdersPerDay"
                        >

                            <div class="h-56">
                                <canvas id="myChart"></canvas>
                            </div>

                        </div>

                        <p class="text-xs italic text-gray-400">* Valores líquidos</p>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-12 xl:col-span-6">

                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="space-y-6">

                        <h3>Seus melhores produtos</h3>

                        <div class="">

                            <div class="overflow-hidden rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="table-green table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Produto</th>
                                                <th>Faturamento</th>
                                                <th>Vendas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topSellingProducts as $product)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $product->parent_product_name }}</td>
                                                    <td>{{ Number::currency($product->total_amount, 'BRL', 'pt-br') }}</td>
                                                    <td>{{ $product->quantity_orders }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="text-center"
                                                    >
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

            <div class="col-span-12 md:col-span-6 xl:col-span-3">

                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="space-y-6">

                        <h3>Assinaturas</h3>

                        <div class="flex h-full flex-col">

                            <canvas
                                id="myChart2"
                                class="mx-auto !h-[260px] !w-[260px] md:!h-full md:!w-full"
                            >
                            </canvas>

                            <ul class="mt-10">
                                <li class="flex items-center gap-2">
                                    <span class="text-primary">•</span>
                                    <span class="text-sm">Novos Assinantes</span>
                                    <span class="ml-auto text-sm">
                                        {{ $quantitiesSubscriptionsPerSituation->new_subscribers ?? 0 }}
                                    </span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-blue-600">•</span>
                                    <span class="text-sm">Assinantes mantidos</span>
                                    <span class="ml-auto text-sm">
                                        {{ $quantitiesSubscriptionsPerSituation->maintained_subscribers ?? 0 }}
                                    </span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-red-600">•</span>
                                    <span class="text-sm">Cancelamentos</span>
                                    <span class="ml-auto text-sm">
                                        {{ $quantitiesSubscriptionsPerSituation->cancellations ?? 0 }}
                                    </span>
                                </li>
                            </ul>

                        </div>

                    </div>
                @endcomponent

            </div>

            <div class="col-span-12 md:col-span-6 xl:col-span-3">

                <div class="grid h-full grid-cols-2 gap-4 md:grid-cols-1 md:gap-6">

                    @component('components.card', ['custom' => 'p-6 h-full'])
                        <div class="space-y-6">

                            <h3>Mais vendas</h3>

                            <div class="space-y-3">
                                <p>{{ $totalAbandonedCarts }} oportunidade(s) de recuperar vendas</p>
                                <a
                                    class="button button-primary h-12 rounded-full"
                                    title="Carrinhos de compras abandonados"
                                    href="{{ route('dashboard.abandoned-carts.index') }}"
                                >
                                    Vender mais
                                </a>
                            </div>

                        </div>
                    @endcomponent

                    <div class="grid h-full grid-cols-1 grid-rows-2 gap-4 md:gap-6">

                        @component('components.card', ['custom' => 'p-6 h-full'])
                            <div class="flex items-start gap-3">
                                @include('components.icon', [
                                    'icon' => 'currency_exchange',
                                    'custom' => 'text-gray-400',
                                ])
                                <div class="flex flex-col gap-px">
                                    <span class="text-xs font-medium uppercase text-gray-500">Reembolso</span>
                                    <span class="text-tg font-semibold">
                                        {{ Number::percentage($percentOrdersRefunded->percentage_refunded ?? 0) }}
                                    </span>
                                </div>
                            </div>
                        @endcomponent

                        @component('components.card', ['custom' => 'p-6 h-full'])
                            <div class="flex items-start gap-3">
                                @include('components.icon', [
                                    'icon' => 'block',
                                    'custom' => 'text-gray-400',
                                ])
                                <div class="flex flex-col gap-px">
                                    <span class="text-xs font-medium uppercase text-gray-500">Chargeback</span>
                                    <span class="text-tg font-semibold">
                                        {{ Number::percentage($percentOrdersChargeback->percentage_chargeback ?? 0) }}
                                    </span>
                                </div>
                            </div>
                        @endcomponent

                    </div>

                </div>

            </div>

            <div class="col-span-12 xl:col-span-6">

                <div
                    class="h-80 w-full overflow-hidden rounded-xl bg-cover bg-no-repeat p-2 xl:h-full xl:min-h-[480px]"
                    style="background-image: url('{{ asset('images/dashboard/img-dashboard.png') }}')"
                >
                </div>

            </div>

            <div class="col-span-12 xl:col-span-6">

                @component('components.card', ['custom' => 'p-6 h-full'])
                    <div class="space-y-6">

                        <h3>Seus melhores afiliados</h3>

                        <div class="">

                            <div class="overflow-hidden rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="table-green table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="w-[50%]">Afiliados</th>
                                                <th>Faturamento</th>
                                                <th>Vendas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($topAffiliates as $affiliate)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="uppercase">{{ Str::limit($affiliate->affiliate_name, 30) }}</td>
                                                    <td>{{ Number::currency($affiliate->total_amount, 'BRL', 'pt-br') }}</td>
                                                    <td>{{ $affiliate->quantity_orders }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="text-center"
                                                    >
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
                        <input
                            class="form-input pl-10"
                            id="filter[period]"
                            name="filter[period]"
                            autocomplete="off"
                            type="text"
                            value="{{ request('filter.period') }}"
                        />
                        <div class="append-item-left w-10">
                            @include('components.icon', [
                                'icon' => 'calendar_month',
                                'custom' => 'text-xl text-gray-400',
                            ])
                        </div>
                    </div>
                </div>

                <div class="col-span-12">
                    <label for="filter[type]">Tipo de venda</label>
                    <select
                        id="filter[type]"
                        name="filter[type]"
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
                </div>

                <div class="col-span-12">
                    <label for="filter[product]">Nome do produto</label>
                    <input
                        type="text"
                        id="filter[product]"
                        name="filter[product]"
                        value="{{ request('filter.product') }}"
                        placeholder="Digite o nome do produto"
                    />
                </div>
            </div>

            <button
                class="button button-primary mt-8 h-12 w-full rounded-full"
                type="submit"
            >
                Pesquisar
            </button>

        </form>
    @endcomponent
@endpush

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{ asset('js/dashboard/startDateRangePicker.js') }}"></script>
    <script src="{{ asset('js/dashboard/dropzone-chunking-config.js') }}"></script>

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
                    'Mês Passado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            const $toggleButton = $("#toggleMaskMoney");
            const $elements = $("span.mask-value-money");
            let lastValue = [];

            $elements.each(function(index, item) {
                lastValue.push($(item).html());
                $(item).html("••••");
            });

            $toggleButton.removeClass("active");

            $toggleButton.on("click", function() {
                const isActive = $toggleButton.toggleClass("active").hasClass("active");

                if (isActive) {
                    $elements.each((index, item) => {
                        $(item).html(lastValue[index]);
                    });
                } else {
                    $elements.each(function(index, item) {
                        lastValue[index] = $(item).html();
                        $(item).html("••••");
                    });
                }
            });
        });
    </script>

    <script>
        let ordersPerDay = @json($ordersPerDay);
        let days = ordersPerDay.map(item => item.day);
        let ordersAmount = ordersPerDay.map(item => item.total_net_amount);

        if (ordersPerDay.length === 0) {
            document.getElementById('myChart').parentElement.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-center italic text-gray-500">Dados insuficientes para gerar gráfico</p></div>';
        } else {
            var ctx = document.getElementById('myChart').getContext('2d');
            var data = {
                labels: days,
                datasets: [{
                    label: 'Faturamento por dia',
                    data: ordersAmount,
                    fill: false,
                    borderColor: '#33cc33',
                    tension: 0.1
                }]
            };

            var config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL'
                                    }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    return new Intl.NumberFormat('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL'
                                    }).format(value);
                                }
                            }
                        }
                    }
                }
            };

            var myChart = new Chart(ctx, config);
        }
    </script>

    <script>
        let quantitiesSubscriptionsPerSituation = @json($quantitiesSubscriptionsPerSituation);

        var ctx2 = document.getElementById('myChart2').getContext('2d');

        var data = {
            labels: [
                'Novos Assinantes',
                'Assinantes mantidos',
                'Cancelamentos'
            ],
            datasets: [{
                data: [
                    quantitiesSubscriptionsPerSituation?.new_subscribers ?? 0,
                    quantitiesSubscriptionsPerSituation?.maintained_subscribers ?? 0,
                    quantitiesSubscriptionsPerSituation?.cancellations ?? 0
                ],
                backgroundColor: [
                    '#33cc33',
                    '#00c2ff',
                    '#f00'
                ],
                hoverOffset: 4
            }]
        };

        var config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                cutout: '55%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        var myChart2 = new Chart(ctx2, config);
    </script>

    <script>
        $(document).on('click', '#toggleMaskMoney', function() {
            localStorage.setItem('toggleMaskMoney', $(this).is(':checked'));
        });

        window.addEventListener('load', function() {
            const toggleMaskMoney = localStorage.getItem('toggleMaskMoney');

            if (toggleMaskMoney === 'true') {
                $('#toggleMaskMoney').trigger('click');
            }
        });
    </script>
@endsection
