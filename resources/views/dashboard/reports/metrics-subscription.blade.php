@extends('layouts.dashboard')

@section('content')
    <div class="relative space-y-6 md:space-y-8 lg:space-y-10">

        <div class="mb-8">
            <h1 class="text-2xl md:text-4xl font-bold mb-4">
                Métricas de Assinaturas
            </h1>
            <p class="text-lg">
                Acompanhe o desempenho das suas assinaturas e tome decisões baseadas em dados
            </p>
        </div>

        <div class="space-y-6">
            <div class="mb-6 flex flex-col items-center justify-between gap-4 md:flex-row md:gap-6 w-full">

                <form
                        class="w-full flex-1"
                        method="GET"
                >
                    <div class="grid grid-cols-12 gap-2 md:gap-4 w-full">
                        <div class="col-span-12 md:col-span-11">
                            <div class="append">
                                <input
                                    class="form-input pl-10"
                                    id="filter[period]"
                                    name="filter[period]"
                                    autocomplete="off"
                                    type="text"
                                    value="{{ request('filter.period', now()->startOfMonth()->format('d/m/Y') . ' - ' . now()->endOfMonth()->format('d/m/Y')) }}"
                                />
                                <div class="append-item-left w-10">
                                    @include('components.icon', [
                                        'icon' => 'calendar_month',
                                        'custom' => 'text-xl text-gray-400',
                                    ])
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-1 flex items-end">
                            <button
                                    class="button button-outline-primary h-12 w-full gap-1 md:w-auto flex items-center justify-center"
                                    type="submit"
                            >
                                @include('components.icon', [
                                    'icon' => 'filter_alt',
                                    'type' => 'fill',
                                    'custom' => 'text-xl',
                                ])
                                <span>Filtrar</span>
                            </button>
                        </div>
                    </div>
                </form>

            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-2">

                <!-- Taxa de Sucesso de Cobrança -->
                <div class="bg-white/95 glass-effect rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 card-gradient rounded-xl flex items-center justify-center text-white text-xl bg-gray-100">
                                💳
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    Taxa de Sucesso de Cobrança
                                    <button
                                        data-tooltip-target="tooltip-success-rate"
                                        data-tooltip-placement="top"
                                        class="ml-2 text-gray-400 hover:text-gray-600 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Percentual de cobranças bem-sucedidas no período
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tooltip -->
                    <div id="tooltip-success-rate" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        <div class="tooltip-content">
                            <strong>Taxa de Sucesso de Cobrança:</strong><br>
                            Indica a eficiência do seu sistema de cobrança. Uma taxa alta (>85%) sugere que seus métodos de pagamento estão funcionando bem. Taxas baixas podem indicar problemas com cartões vencidos, saldo insuficiente ou falhas técnicas.
                        </div>
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <div class="text-5xl font-bold metric-value mb-6" id="successRate">
                        {{ $metrics['successRate']['value'] }}%
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-green-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Cobranças Processadas
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ $metrics['successRate']['total']['processed'] }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-green-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Bem-sucedidas
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ $metrics['successRate']['total']['successful'] }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-green-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Falhas
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ $metrics['successRate']['total']['failed'] }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-green-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Valor Total
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ Number::currency($metrics['successRate']['total']['amount'], 'BRL', 'pt-br') }}
                            </p>
                        </div>
                    </div>

                    <!-- Trend Indicator -->
                    <div @class([
                            "flex items-center justify-center rounded-lg p-3",
                            "bg-green-50 text-green-800" => $metrics['successRate']['change']['successRateTrend'] == '+',
                            "bg-red-50 text-red-800" => $metrics['successRate']['change']['successRateTrend'] == '-'
                        ])
                    >
                        @if($metrics['successRate']['change']['successRateTrend'] == '+')
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                            </svg>
                        @elseif($metrics['successRate']['change']['successRateTrend'] == '-')
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10V7H7z"></path>
                            </svg>
                        @endif

                        <span class="font-medium">
                            {{ $metrics['successRate']['change']['successRateTrend']  }}
                            {{ $metrics['successRate']['change']['successRateChange']  }}%
                            em relação ao mês anterior
                        </span>
                    </div>
                </div>

                <!-- Taxa de Conversão de Upgrade/Downgrade -->
                <div class="bg-white/95 glass-effect rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 conversion-gradient rounded-xl flex items-center justify-center text-white text-xl bg-gray-100">
                                📈
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    Taxa de Conversão de Planos
                                    <button
                                        data-tooltip-target="tooltip-conversion-rate"
                                        data-tooltip-placement="top"
                                        class="ml-2 text-gray-400 hover:text-gray-600 transition-colors"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Percentual de clientes que concluíram alteração de plano
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tooltip -->
                    <div id="tooltip-conversion-rate" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        <div class="tooltip-content">
                            <strong>Taxa de Conversão de Planos:</strong><br>
                            Mede a eficácia dos seus processos de upgrade/downgrade. Uma taxa alta (>70%) indica que os clientes encontram facilidade no processo de alteração. Monitore para otimizar a experiência do usuário e maximizar receita.
                        </div>
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <div class="text-5xl font-bold metric-value mb-6" id="conversionRate">
                        {{ $metrics['conversionRate']['value'] }}%
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Links Enviados
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ $metrics['conversionRate']['total']['links'] }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Conversões Concluídas
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ $metrics['conversionRate']['total']['conversions'] }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Upgrades
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ $metrics['conversionRate']['total']['upgrades'] }}
                                ({{ $metrics['conversionRate']['total']['upgradePercentage'] }}%)
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">
                                Downgrades
                            </p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">
                                {{ $metrics['conversionRate']['total']['downgrades'] }}
                                ({{ $metrics['conversionRate']['total']['downgradePercentage'] }}%)
                            </p>
                        </div>
                    </div>

                    <!-- Trend Indicator -->
                    <div @class([
                            "flex items-center justify-center rounded-lg p-3",
                            "bg-green-50 text-green-800" => $metrics['conversionRate']['change']['conversionRateTrend'] == '+',
                            "bg-red-50 text-red-800" => $metrics['conversionRate']['change']['conversionRateTrend'] == '-'
                        ])
                    >
                        @if ($metrics['conversionRate']['change']['conversionRateTrend'] == '+')
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                            </svg>
                        @elseif($metrics['conversionRate']['change']['conversionRateTrend'] == '-')
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10V7H7z"></path>
                            </svg>
                        @endif

                        <span class="font-medium">
                            {{ $metrics['conversionRate']['change']['conversionRateTrend']  }}
                            {{ $metrics['conversionRate']['change']['conversionRateChange']  }}%
                            em relação ao mês anterior
                        </span>
                    </div>
                </div>

            </div>

            <div class="bottom-0 left-0  text-center bg-white p-3 rounded shadow z-50">
                <p class="text-sm italic">
                    Última atualização:
                    <span class="font-medium">
                        {{ now()->subHour()->isoFormat('LLL') }}
                    </span>
                </p>
            </div>

        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('js/dashboard/startDateRangePicker.js') }}"></script>

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
@endsection
