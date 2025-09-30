@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="flex items-center justify-between gap-6 md:gap-8 lg:gap-10">
            <h1>Métricas UTM</h1>
        </div>

        <p class="text-neutral-600">
            Acompanhe a performance dos seus links UTM e tome decisões baseadas em dados
        </p>

    </div>

    <div class="space-y-6 mt-6">
        <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6">
            <form class="w-full flex-1" action="{{ route('dashboard.utm-reports.index') }}" method="GET">
                <div class="grid grid-cols-12 gap-2 md:gap-3 mb-4">
                    <div class="col-span-12 lg:col-span-2">
                        <label for="filter[start_at]">A partir de</label>
                        <input type="datetime-local" id="filter[start_at]" onchange="this.form.submit()" name="filter[start_at]"
                               value="{{ request('filter.start_at') ?? now()->startOfMonth()->format('Y-m-d\TH:i') }}" />
                    </div>

                    <div class="col-span-12 lg:col-span-2">
                        <label for="filter[end_at]">Até</label>
                        <input type="datetime-local" id="filter[end_at]" onchange="this.form.submit()" name="filter[end_at]"
                               value="{{ request('filter.end_at') ?? now()->format('Y-m-d\TH:i') }}" />
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-2 md:gap-3">
                    <div class="col-span-12 md:col-span-7">

                        <div class="append">
                            <select class="pl-24" id="filter[product_id]" name="filter[product_id]" onchange="this.form.submit()">
                                <option value="">Todos</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" @selected(request('filter.product_id') == $product->id)>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="append-item-left px-4">
                                <span class="font-semibold">Produto:</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <div class="append">
                            <select class="pl-24" id="filter[campaign]" name="filter[campaign]" onchange="this.form.submit()">
                                <option value="">Todos</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{ $campaign }}" @selected(request('filter.campaign') == $campaign)>
                                        {{ $campaign }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="append-item-left px-4">
                                <span class="font-semibold">Campanha:</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <a href="{{ route('dashboard.utm-reports.index') }}"
                           class="button button-outline-primary h-12 w-full gap-1 md:w-auto">
                            Limpar Filtros
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-3 xl:grid-cols-4 xl:gap-6 mt-6">
        @php
            $metrics = [
                ['title'=>'Total de Cliques','value'=>$views['views'],'description'=>'Cliques únicos nos links UTM','percent'=>$views['viewsPercentStr']],
                ['title'=>'Conversões Totais','value'=>$orderMetrics['ordersCount'],'description'=>'Vendas realizadas via UTM','percent'=>$orderMetrics['orderCountPercentStr']],
                ['title'=>'Receita Total','value'=>$orderMetrics['ordersTotalAmount'],'description'=>'Faturamento via links UTM','percent'=>$orderMetrics['orderTotalAmountPercentStr'],'isCurrency'=>true],
                ['title'=>'Taxa de Conversão','value'=>$conversion['percent'],'description'=>'Percentual de cliques que converteram','percent'=>$conversion['viewsPercentStr'],'isPercent'=>true],
            ];
        @endphp

        @foreach($metrics as $metric)
            <div class="col-span-1">
                @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                    <div class="flex h-full flex-col gap-1 lg:gap-4">
                        <h3>{{ $metric['title'] }}</h3>
                        <div class="mt-auto space-y-3">
                            <p class="flex items-center gap-3">
                            <span class="text-2xl font-semibold">
                                @if(!empty($metric['isCurrency']))
                                    R$ {{ number_format($metric['value'], 2, ',', '.') }}
                                @elseif(!empty($metric['isPercent']))
                                    {{ $metric['value'] }}%
                                @else
                                    {{ $metric['value'] }}
                                @endif
                            </span>
                            </p>
                            <p>{{ $metric['description'] }}</p>
                            <div class="alert alert-{{ $metric['percent'] > 0 ? 'success': 'danger' }} mt-4">
                                <div class="flex items-center gap-px text-sm font-semibold">
                                    @include('components.icon', [
                                        'icon' => $metric['percent'] > 0 ? 'arrow_upward_alt': 'arrow_downward_alt',
                                        'custom' => 'text-xl',
                                    ])
                                    {{ $metric['percent'] }}%
                                </div>
                                <p class="text-sm">Comparado ao período anterior</p>
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-2 xl:gap-6 mt-6">
        <div class="col-span-1">
            @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                <h3 class="font-semibold text-neutral-900 mb-4">Top 5 UTMs por Performance</h3>
                <div class="space-y-3">
                    @foreach($ordersByUtm as $utm)
                        <div class="border-l-4 border-green-500 bg-white shadow-sm p-6 xl:p-8 flex flex-col gap-3 relative">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-neutral-900">{{ $utm->utmLink->utm_source ?? 'google / cpc' }}</h4>
                                    <p class="text-sm text-neutral-600">Campanha: {{ $utm->utmLink->utm_campaign ?? '-' }}</p>
                                    <p class="text-sm text-neutral-600">Produto: {{ $utm->utmLink->product->parentProduct->name ?? '-' }}</p>
                                </div>
                                <span class="bg-green-100 text-green-600 text-sm font-semibold px-2 py-1 rounded-lg">
                                {{ number_format($utm['conversion_rate'], 2) }}%
                            </span>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                                <div>
                                    <p class="text-neutral-600">Cliques</p>
                                    <p class="text-lg font-semibold">{{ number_format($utm['total_clicks'] ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-neutral-600">Vendas</p>
                                    <p class="text-lg font-semibold">{{ number_format($utm['total_orders'] ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-neutral-600">Receita</p>
                                    <p class="text-lg font-semibold">
                                        R$ {{ number_format($utm['total_amount'] ?? 0, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endcomponent
        </div>

        <div class="col-span-1">
            @component('components.card', ['custom' => 'p-6 xl:p-8 h-full'])
                <h2 class="text-lg font-semibold text-gray-900">Funil de Conversão UTM</h2>
                <p class="text-sm text-gray-500 mb-4"><strong>Filtrado</strong></p>
                <div class="flex justify-between items-center py-4">
                    <span class="text-sm text-gray-600">Ticket Médio</span>
                    <div class="text-right">
                        <div class="text-base font-bold text-gray-900">
                            R$ {{ number_format($orderMetrics['ordersCount'] > 0 ? $orderMetrics['ordersTotalAmount'] / $orderMetrics['ordersCount'] : 0, 2, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-500">por venda</div>
                    </div>
                </div>
            @endcomponent
        </div>

    </div>
@endsection
