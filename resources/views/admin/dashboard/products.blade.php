@extends('layouts.new-admin', ['title' => 'Dashboard Produtos'])

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            @component('components.admin.ui.card')
                <div class="flex items-center justify-between">
                    <div class="">
                        <h6>Bem vindo, <strong>{{ \Illuminate\Support\Facades\Auth::user()->name }}</strong></h6>
                        <p class="text-gray-500">Acompanhe suas métricas de pedidos em tempo real.</p>
                    </div>

                    @component('components.admin.ui.drawer', [
                        'id' => 'filter',
                        'btnTitle' => 'Filtrar',
                        'drawerTitle' => 'Filtrar',
                    ])
                        <div class="flex items-center space-x-4 hidden">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Atualizar a cada</span>
                                <select id="refreshInterval" class="form-select rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm py-1">
                                    <option value="5000">5 segundos</option>
                                    <option value="10000" selected>10 segundos</option>
                                    <option value="30000">30 segundos</option>
                                    <option value="60000">1 minuto</option>
                                </select>
                            </div>
                            <button id="refreshBtn" type="button" class="ti-btn ti-btn-primary-full flex items-center py-2">
                                <i class='bx bx-refresh mr-2 refresh-indicator'></i> Atualizar
                            </button>
                        </div>

                        <form
                                id="filters-form"
                                action=""
                                method=""
                        >
                            <div class="space-y-6 p-4">
                                <div class="grid grid-cols-12 gap-4 md:gap-6">

                                    <div class="col-span-12">
                                        <label
                                                class="form-label"
                                                for="filter[allByUser]"
                                        >
                                            Por lojista
                                        </label>

                                        <select class="select2 form-control" name="filter[allByUser]" id="filterShopkeeper">
                                            <option value="">Todos</option>
                                            @foreach($shopkeepers as $item)
                                                <option @selected($item->id == request('filter.allByUser')) value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-span-12">
                                        <label
                                                class="form-label"
                                                for=""
                                        >
                                            Por data
                                        </label>
                                        <div class="grid grid-cols-2 gap-4 md:gap-6">
                                            <div class="col-span-1">
                                                <label
                                                        class="form-label"
                                                        for=""
                                                >
                                                    Data ínicio
                                                </label>
                                                <input
                                                        class="form-control"
                                                        type="date"
                                                        name="filter[start_date]"
                                                        id="filterStartDate"
                                                        value="{{ request('filter.start_date') }}"
                                                >
                                            </div>
                                            <div class="col-span-1">
                                                <label
                                                        class="form-label"
                                                        for=""
                                                >
                                                    Data final
                                                </label>
                                                <input
                                                        class="form-control"
                                                        type="date"
                                                        name="filter[end_date]"
                                                        id="filterEndDate"
                                                        value="{{ request('filter.end_date') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <button
                                        class="ti-btn ti-btn-primary-full w-full"
                                        type="button"
                                        data-hs-overlay="#filter"
                                        onclick="showLoadingState()"
                                        id="applyFilters"
                                >
                                    Aplicar Filtros
                                </button>

                            </div>
                        </form>
                    @endcomponent

                </div>
            @endcomponent
        </div>

        <div class="col-span-12 md:col-span-6">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Quantidade Total de Pedidos',
                'customCardBody' => '!p-0',
            ])
                <div id="ordersCountContainer">
                    <div class="border-b p-4">
                        <h4 class="font-bold" id="totalOrdersCount">0</h4>
                        <p>Pedidos Realizados</p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <div class="flex items-center gap-4 px-3 py-4">
                                <i class="bx bx-cart flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-xl text-green-800"></i>
                                <div class="">
                                    <h6 class="font-bold leading-none" id="totalPaidOrdersCount">0</h6>
                                    <p class="mb-0 text-green-500">Pagos</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex items-center gap-4 px-3 py-4">
                                <i class="bx bx-cart flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100 text-xl text-yellow-800"></i>
                                <div class="">
                                    <h6 class="font-bold leading-none" id="totalPendingOrdersCount">0</h6>
                                    <p class="mb-0 text-yellow-500">Pendentes</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex items-center gap-4 px-3 py-4">
                                <i class="bx bx-cart flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-xl text-red-800"></i>
                                <div class="">
                                    <h6 class="font-bold leading-none" id="totalCanceledOrdersCount">0</h6>
                                    <p class="mb-0 text-red-500">Cancelados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>

        <div class="col-span-12 md:col-span-6">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Valor Total de Pedidos Realizados',
                'customCardBody' => '!p-0',
            ])
                <div id="ordersValueContainer">
                    <div class="border-b p-4">
                        <h4 class="font-bold" id="totalOrdersValue">R$ 0,00</h4>
                        <p>Pedidos Realizados</p>
                    </div>
                    <div class="flex gap-4 divide-x">
                        <div class="flex-1 px-3 py-4">
                            <div class="flex items-center gap-4">
                                <i class="bx bx-dollar flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-xl text-green-800"></i>
                                <div class="flex-1">
                                    <h6 class="font-bold leading-none" id="totalPaidValue">R$ 0,00</h6>
                                    <p class="mb-0 text-green-500">Pagos</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 px-3 py-4">
                            <div class="flex items-center gap-4">
                                <i class="bx bx-dollar flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100 text-xl text-yellow-800"></i>
                                <div class="flex-1">
                                    <h6 class="font-bold leading-none" id="totalPendingValue">R$ 0,00</h6>
                                    <p class="mb-0 text-yellow-500">Pendentes</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 px-3 py-4">
                            <div class="flex items-center gap-4">
                                <i class="bx bx-dollar flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-xl text-red-800"></i>
                                <div class="flex-1">
                                    <h6 class="font-bold leading-none" id="totalCanceledValue">R$ 0,00</h6>
                                    <p class="mb-0 text-red-500">Cancelados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>

        <!-- Seção de Pedidos por Forma de Pagamento -->
        <div class="col-span-12">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Pedidos por forma de pagamento',
                'customCard' => 'h-full overflow-hidden',
                'customCardBody' => '!p-0 h-full',
            ])
                <div id="paymentMethodsContainer" class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-auto p-3">
                    <!-- Os cards serão preenchidos via JavaScript -->
                </div>
            @endcomponent
        </div>

        <!-- Seção de Valor Total de Pedidos por Dia -->
        <div class="col-span-12">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Valor total de pedidos por dia',
            ])
                <div id="dailyOrdersChart"></div>
            @endcomponent
        </div>

        <!-- Seção de Valor Total de Pedidos por Dia com Forma de Pagamento -->
        <div class="col-span-12">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Valor total de pedidos por dia com forma de pagamento',
            ])
                <div id="dailyOrdersPaymentMethodChart"></div>
            @endcomponent
        </div>

        <!-- Seção de Ranking de Produtos Mais Vendidos -->
        <div class="col-span-12 xl:col-span-4">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Ranking de produtos mais vendidos',
                'customCardBody' => '!p-0',
            ])
                <div id="topProductsContainer" class="divide-y">
                    <!-- Os itens serão preenchidos via JavaScript -->
                </div>
            @endcomponent
        </div>

        <!-- Seção de Ranking de Melhores Afiliados -->
        <div class="col-span-12 md:col-span-7 xl:col-span-4">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Ranking de melhores afiliados',
                'customCard' => 'h-full',
                'customCardBody' => '!p-0',
            ])
                <div id="topAffiliatesContainer" class="divide-y">
                    <!-- Os itens serão preenchidos via JavaScript -->
                </div>
            @endcomponent
        </div>

        <!-- Seção de Assinaturas -->
        <div class="col-span-12 md:col-span-7 xl:col-span-4">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Assinaturas',
                'customCard' => 'h-full',
                'customCardBody' => 'h-full',
            ])
                <div id="subscriptionsChart"></div>
            @endcomponent
        </div>

        <div class="col-span-12 md:col-span-6 xl:col-span-4">
            @component('components.admin.ui.card', [
                'cardTitle' => 'Usuários ativos',
                'customCardBody' => '!p-0',
            ])
                <div id="activeUsersContainer">
                    <div class="border-b p-4">
                        <h4 class="font-bold" id="activeUsersCount">0</h4>
                        <p>Ativos</p>
                    </div>
                </div>
            @endcomponent

            @component('components.admin.ui.card', [
                'cardTitle' => 'Usuários autenticados ativos',
                'customCardBody' => '!p-0',
                'customCard' => 'mt-5'
            ])
                <div id="activeSalesUsersContainer">
                    <div class="border-b p-4">
                        <h4 class="font-bold" id="activeSalesUsersCount">0</h4>
                        <p>Ativos</p>
                    </div>
                </div>
            @endcomponent
        </div>

        <!-- Seção de Total de Acessos ao Checkout -->
        <div class="col-span-12 md:col-span-6 xl:col-span-8">
            @component('components.admin.ui.card', [
               'cardTitle' => 'Total de acessos ao Checkout',
           ])
                <div id="checkoutAccessChart"></div>
            @endcomponent
        </div>

        <!-- Seção de Checkouts Iniciados -->
        <div class="col-span-12 md:col-span-6 xl:col-span-6">
            @component('components.admin.ui.card', [
               'cardTitle' => 'Total de Checkouts iniciados',
           ])
                <div id="beginCheckoutChart"></div>
            @endcomponent
        </div>

        <!-- Seção de Purchase no Checkout -->
        <div class="col-span-12 md:col-span-6 xl:col-span-6">
            @component('components.admin.ui.card', [
               'cardTitle' => 'Total de Purchase no Checkout',
           ])
                <div id="checkoutPurchaseChart"></div>
            @endcomponent
        </div>

        <!-- Status de Atualização -->
        <div class="col-span-12 text-center text-sm text-gray-500 mt-4">
            <p id="lastUpdate">Última atualização: --:--:--</p>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('./vendor/ynex/libs/apexcharts/apexcharts.css') }}">
    <style>
        .loading {
            position: relative;
            overflow: hidden;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
        }
        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: loading 1.5s infinite;
        }
        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        .refresh-indicator {
            transition: transform 0.5s ease;
        }
        .refreshing {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .payment-method-card {
            transition: all 0.3s ease;
        }
        .payment-method-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .ranking-item {
            transition: all 0.3s ease;
        }
        .ranking-item:hover {
            background-color: #f9fafb;
        }
        .ranking-badge {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
            font-size: 14px;
        }
        .ranking-badge-1 {
            background-color: #fef3c7;
            color: #d97706;
        }
        .ranking-badge-2 {
            background-color: #e5e7eb;
            color: #374151;
        }
        .ranking-badge-3 {
            background-color: #fed7aa;
            color: #ea580c;
        }
        .ranking-badge-other {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .subscription-stat {
            text-align: center;
            padding: 1rem;
        }
        .subscription-stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4f46e5;
        }
        .subscription-stat-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('/vendor/ynex/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Variáveis globais
        let refreshIntervalId = null;
        let dailyOrdersChart = null;
        let dailyOrdersPaymentMethodChart = null;
        let subscriptionsChart = null;
        let checkoutAccessChart = null;
        let beginCheckoutChart = null;
        let checkoutPurchaseChart = null;
        let currentFilters = {
            start_date: document.getElementById('filterStartDate')?.value || '',
            end_date: document.getElementById('filterEndDate')?.value || '',
            allByUser: document.getElementById('filterShopkeeper')?.value || ''
        };

        // Formatação de números
        const formatCurrency = (value) => {
            if (!value) return 'R$ 0,00';
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
        };

        const formatNumber = (value) => {
            if (!value) return '0';
            return new Intl.NumberFormat('pt-BR').format(value);
        };

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar gráficos
            initDailyOrdersChart();
            initDailyOrdersPaymentMethodChart();
            initSubscriptionsChart();
            initCheckoutAccessChart();
            initBeginCheckoutChart();
            initCheckoutPurchaseChart();

            // Carregar dados iniciais
            loadData();

            // Configurar auto-atualização
            setupAutoRefresh();

            // Configurar event listeners
            document.getElementById('refreshBtn').addEventListener('click', function() {
                manualRefresh();
            });

            document.getElementById('refreshInterval').addEventListener('change', function() {
                restartAutoRefresh();
            });

            // Configurar evento do botão de aplicar filtros
            document.getElementById('applyFilters').addEventListener('click', function() {
                applyFilters();
            });

            // Inicializar Select2 se existir
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2').select2({
                    dropdownParent: $('#filters-form')
                });
            }
        });

        // Aplicar filtros
        function applyFilters() {
            currentFilters = {
                start_date: document.getElementById('filterStartDate').value,
                end_date: document.getElementById('filterEndDate').value,
                allByUser: document.getElementById('filterShopkeeper').value
            };

            // Fechar o drawer de filtros (se estiver usando algum framework)
            if (typeof window.closeDrawer === 'function') {
                window.closeDrawer('filter');
            }

            // Recarregar dados com os novos filtros
            loadData();
        }

        // Configurar auto-atualização
        function setupAutoRefresh() {
            const interval = parseInt(document.getElementById('refreshInterval').value);
            refreshIntervalId = setInterval(loadData, interval);
        }

        function restartAutoRefresh() {
            if (refreshIntervalId) {
                clearInterval(refreshIntervalId);
            }
            setupAutoRefresh();
        }

        // Atualização manual
        function manualRefresh() {
            const refreshIcon = document.querySelector('.refresh-indicator');
            refreshIcon.classList.add('refreshing');

            loadData();

            setTimeout(() => {
                refreshIcon.classList.remove('refreshing');
            }, 1000);
        }

        // Carregar dados da API
        function loadData() {
            //showLoadingState();

            // Construir query string com os filtros atuais
            const queryParams = new URLSearchParams();

            if (currentFilters.start_date) {
                queryParams.append('filter[start_date]', currentFilters.start_date);
            }

            if (currentFilters.end_date) {
                queryParams.append('filter[end_date]', currentFilters.end_date);
            }

            if (currentFilters.allByUser) {
                queryParams.append('filter[allByUser]', currentFilters.allByUser);
            }

            // Fazer requisição para a API com os filtros
            axios.get(`{{ route("api.data.admin.dashboard.products.data") }}?${queryParams.toString()}`)
                .then(response => {
                    updateUI(response.data);
                    updateLastUpdateTime();
                })
                .catch(error => {
                    console.error('Erro ao carregar dados:', error);
                });
        }

        // Mostrar estado de carregamento
        function showLoadingState() {
            // Adicionar efeito de loading aos elementos
            const elements = [
                'totalOrdersCount', 'totalPaidOrdersCount', 'totalPendingOrdersCount', 'totalCanceledOrdersCount',
                'totalOrdersValue', 'totalPaidValue', 'totalPendingValue', 'totalCanceledValue'
            ];

            elements.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.add('loading');
                    // Adicionar placeholder durante o loading
                    if (id.includes('Value')) {
                        el.textContent = 'R$ --,--';
                    } else {
                        el.textContent = '--';
                    }
                }
            });

            // Adicionar loading para os cards de métodos de pagamento
            const paymentContainer = document.getElementById('paymentMethodsContainer');
            if (paymentContainer) {
                paymentContainer.innerHTML = `
                    <div class="col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 payment-method-card loading" style="height: 180px;"></div>
                    </div>
                    <div class="col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 payment-method-card loading" style="height: 180px;"></div>
                    </div>
                    <div class="col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 payment-method-card loading" style="height: 180px;"></div>
                    </div>
                `;
            }

            // Adicionar loading para os rankings
            const topProductsContainer = document.getElementById('topProductsContainer');
            if (topProductsContainer) {
                topProductsContainer.innerHTML = `
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                `;
            }

            const topAffiliatesContainer = document.getElementById('topAffiliatesContainer');
            if (topAffiliatesContainer) {
                topAffiliatesContainer.innerHTML = `
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item loading" style="height: 80px;"></div>
                `;
            }

            // Adicionar loading para o gráfico de assinaturas
            const subscriptionsContainer = document.getElementById('subscriptionsChart');
            if (subscriptionsContainer) {
                subscriptionsContainer.innerHTML = `
                    <div class="loading" style="height: 300px; border-radius: 0.5rem;"></div>
                `;
            }

            document.getElementById('activeUsersCount').classList.add('loading');
            document.getElementById('activeSalesUsersCount').classList.add('loading');
            document.getElementById('activeUsersCount').textContent = '--';
            document.getElementById('activeSalesUsersCount').textContent = '--';
        }

        // Atualizar UI com os dados recebidos
        function updateUI(data) {
            // Atualizar contagens de pedidos
            document.getElementById('totalOrdersCount').textContent = formatNumber(data.totalOrdersCount);
            document.getElementById('totalPaidOrdersCount').textContent = formatNumber(data.totalPaidOrdersCount);
            document.getElementById('totalPendingOrdersCount').textContent = formatNumber(data.totalPendingOrdersCount);
            document.getElementById('totalCanceledOrdersCount').textContent = formatNumber(data.totalCanceledOrdersCount);

            // Atualizar valores de pedidos
            document.getElementById('totalOrdersValue').textContent = formatCurrency(data.ordersSumData.total_sum);
            document.getElementById('totalPaidValue').textContent = formatCurrency(data.ordersSumData.total_paid_sum);
            document.getElementById('totalPendingValue').textContent = formatCurrency(data.ordersSumData.total_pending_sum);
            document.getElementById('totalCanceledValue').textContent = formatCurrency(data.ordersSumData.total_canceled_sum);

            document.getElementById('activeUsersCount').textContent = formatNumber(data.analyticsActiveUsers);
            document.getElementById('activeSalesUsersCount').textContent = formatNumber(data.analyticsActiveUsersSales);

            // Atualizar métodos de pagamento
            updatePaymentMethods(data.infosOrdersByPaymentMethod);

            // Atualizar rankings
            updateTopProducts(data.topSellingProducts);
            updateTopAffiliates(data.topAffiliates);

            // Atualizar gráficos
            updateDailyOrdersChart(data.ordersPerDay);
            updateDailyOrdersPaymentMethodChart(data.ordersPerDayWithPaymentMethod);
            updateSubscriptionsChart(data.quantitiesSubscriptionsPerSituation);
            updateCheckoutAccessChart(data.analyticsCheckoutAccessPerDay);
            updateBeginCheckoutChart(data.analyticsBeginCheckoutPerDay);
            updateCheckoutPurchaseChart(data.analyticsCheckoutPurchasePerDay);

            // Remover estado de carregamento
            const loadingElements = document.querySelectorAll('.loading');
            loadingElements.forEach(el => {
                el.classList.remove('loading');
            });
        }

        // Atualizar cards de métodos de pagamento
        function updatePaymentMethods(paymentMethods) {
            const container = document.getElementById('paymentMethodsContainer');
            if (!container) return;

            if (!paymentMethods || Object.keys(paymentMethods).length === 0) {
                container.innerHTML = '<div class="col-span-3 text-center py-8 text-gray-500">Nenhum dado disponível</div>';
                return;
            }

            // Mapear métodos de pagamento
            const paymentTypes = [
                {
                    key: 'CREDIT_CARD',
                    name: 'Cartão de Crédito',
                    icon: 'bx-credit-card',
                    color: 'text-blue-500',
                    bgColor: 'bg-blue-100'
                },
                {
                    key: 'PIX',
                    name: 'PIX',
                    icon: 'bx-qr-scan',
                    color: 'text-purple-500',
                    bgColor: 'bg-purple-100'
                },
                {
                    key: 'BILLET',
                    name: 'Boleto',
                    icon: 'bx-file',
                    color: 'text-green-500',
                    bgColor: 'bg-green-100'
                }
            ];

            let html = '';

            paymentTypes.forEach(type => {
                const method = paymentMethods[type.key];
                if (!method) return;

                html += `
                    <div class="col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 payment-method-card">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium uppercase">
                                        PEDIDOS POR <b>${type.name.toUpperCase()}</b>
                                    </h2>
                                    <p class="text-2xl font-bold text-dark mt-1">
                                        ${formatNumber(method.quantity_orders)}
                                    </p>
                                </div>
                                <div class="rounded-full p-3 ${type.bgColor}">
                                    <i class='bx ${type.icon} ${type.color} text-xl'></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">total</p>
                                    <p class="text-green-500 font-medium mt-1">
                                        ${formatCurrency(method.total_net_amount)}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">total pago</p>
                                    <p class="text-green-500 font-medium mt-1">
                                        ${formatCurrency(method.total_paid_amount)}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">conversão</p>
                                    <p class="text-green-500 font-medium mt-1">
                                        ${method.conversion_rate}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html || '<div class="col-span-3 text-center py-8 text-gray-500">Nenhum dado disponível</div>';
        }

        // Atualizar ranking de produtos mais vendidos
        function updateTopProducts(products) {
            const container = document.getElementById('topProductsContainer');
            if (!container) return;

            if (!products || products.length === 0) {
                container.innerHTML = '<div class="flex items-center gap-4 px-4 py-4"><p class="text-gray-500">Nenhum produto registrado no ranking</p></div>';
                return;
            }

            let html = '';

            products.forEach((product, index) => {
                const rankClass = index === 0 ? 'ranking-badge-1' :
                    index === 1 ? 'ranking-badge-2' :
                        index === 2 ? 'ranking-badge-3' : 'ranking-badge-other';

                html += `
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item">
                        <span class="ranking-badge ${rankClass}">${index + 1}º</span>
                        <div class="flex-1">
                            <h6 class="text-sm font-semibold">${product.product_name}</h6>
                            <div class="flex items-center gap-3">
                                <div class="flex flex-1 flex-col md:flex-none md:flex-row md:items-center md:gap-1">
                                    <h6 class="text-xs font-medium">Logista:</h6>
                                    <p class="text-xs">${product.item?.product?.shop?.owner?.name || 'N/A'}</p>
                                </div>
                                <div class="flex flex-1 flex-col md:flex-none md:flex-row md:items-center md:gap-1">
                                    <h6 class="text-xs font-medium">Vendas:</h6>
                                    <p class="text-xs">${formatNumber(product.quantity_orders)}</p>
                                </div>
                                <div class="flex flex-1 flex-col md:flex-none md:flex-row md:items-center md:gap-1">
                                    <h6 class="text-xs font-medium">Valor:</h6>
                                    <p class="text-xs">${formatCurrency(product.total_amount)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Atualizar ranking de melhores afiliados
        function updateTopAffiliates(affiliates) {
            const container = document.getElementById('topAffiliatesContainer');
            if (!container) return;

            if (!affiliates || affiliates.length === 0) {
                container.innerHTML = '<div class="flex items-center gap-4 px-4 py-4"><p class="text-gray-500">Sem afiliados registrados no ranking</p></div>';
                return;
            }

            let html = '';

            affiliates.forEach((affiliate, index) => {
                const rankClass = index === 0 ? 'ranking-badge-1' :
                    index === 1 ? 'ranking-badge-2' :
                        index === 2 ? 'ranking-badge-3' : 'ranking-badge-other';

                html += `
                    <div class="flex items-center gap-4 px-4 py-4 ranking-item">
                        <span class="ranking-badge ${rankClass}">${index + 1}º</span>
                        <div class="flex-1">
                            <h6 class="text-sm font-semibold">${affiliate.affiliate_name}</h6>
                            <div class="flex items-center gap-3">
                                <div class="flex flex-1 flex-col md:flex-none md:flex-row md:items-center md:gap-1">
                                    <h6 class="text-xs font-medium">Faturamento:</h6>
                                    <p class="text-xs">${formatCurrency(affiliate.total_amount)}</p>
                                </div>
                                <div class="flex flex-1 flex-col md:flex-none md:flex-row md:items-center md:gap-1">
                                    <h6 class="text-xs font-medium">Vendas:</h6>
                                    <p class="text-xs">${formatNumber(affiliate.quantity_orders)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // Inicializar gráfico de pedidos por dia
        function initDailyOrdersChart() {
            const options = {
                series: [{
                    name: 'Valor Total de Pedidos',
                    data: []
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                    zoom: {
                        enabled: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'linear',
                        dynamicAnimation: {
                            speed: 1000
                        }
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '55%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                colors: ['#4f46e5'],
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    }
                },
                xaxis: {
                    categories: [],
                    title: {
                        text: 'Data'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Valor Total (R$)'
                    },
                    labels: {
                        formatter: function(value) {
                            return 'R$ ' + new Intl.NumberFormat('pt-BR').format(value);
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            };

            dailyOrdersChart = new ApexCharts(document.querySelector("#dailyOrdersChart"), options);
            dailyOrdersChart.render();
        }

        // Inicializar gráfico de pedidos por dia com forma de pagamento
        function initDailyOrdersPaymentMethodChart() {
            const options = {
                series: [],
                chart: {
                    height: 350,
                    type: 'bar',
                    stacked: true,
                    zoom: {
                        enabled: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'linear',
                        dynamicAnimation: {
                            speed: 1000
                        }
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '55%',
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                colors: ['#4f46e5', '#22c55e', '#f59e0b', '#ef4444'],
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    }
                },
                xaxis: {
                    categories: [],
                    title: {
                        text: 'Data'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Valor Total (R$)'
                    },
                    labels: {
                        formatter: function(value) {
                            return 'R$ ' + new Intl.NumberFormat('pt-BR').format(value);
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return formatCurrency(value);
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    offsetY: 0
                }
            };

            dailyOrdersPaymentMethodChart = new ApexCharts(document.querySelector("#dailyOrdersPaymentMethodChart"), options);
            dailyOrdersPaymentMethodChart.render();
        }

        // Inicializar gráfico de assinaturas
        function initSubscriptionsChart() {
            const options = {
                chart: {
                    type: 'donut',
                    height: 350
                },
                series: [0, 0, 0],
                labels: ['Novos Assinantes', 'Assinantes Mantidos', 'Assinantes Cancelados'],
                colors: ['#4f46e5', '#22c55e', '#ef4444'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total de Assinantes',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            subscriptionsChart = new ApexCharts(document.querySelector("#subscriptionsChart"), options);
            subscriptionsChart.render();
        }

        function initCheckoutAccessChart() {
            const options = {
                series: [{
                    name: 'acessos',
                    data: []
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                dataLabels: {
                    enabled: false,
                    formatter: function(val) {
                        return val;
                    }
                },
                xaxis: {
                    categories: [],
                    title: {
                        text: 'Data'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Acessos'
                    }
                },
                colors: ['#4ade80'],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            checkoutAccessChart = new ApexCharts(document.querySelector("#checkoutAccessChart"), options);
            checkoutAccessChart.render();
        }

        function initBeginCheckoutChart () {
            const options = {
                series: [{
                    name: 'checkouts iniciados',
                    data: []
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                dataLabels: {
                    enabled: false,
                    formatter: function(val) {
                        return val;
                    }
                },
                xaxis: {
                    categories: [],
                    title: {
                        text: 'Data'
                    }
                },
                yaxis: {
                    title: {
                        text: ''
                    }
                },
                colors: ['#4ade80'],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            beginCheckoutChart = new ApexCharts(document.querySelector("#beginCheckoutChart"), options);
            beginCheckoutChart.render();
        }

        function initCheckoutPurchaseChart () {
            const options = {
                series: [{
                    name: 'purchase',
                    data: []
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                dataLabels: {
                    enabled: false,
                    formatter: function(val) {
                        return val;
                    }
                },
                xaxis: {
                    categories: [],
                    title: {
                        text: 'Data'
                    }
                },
                yaxis: {
                    title: {
                        text: 'purchase'
                    }
                },
                colors: ['#4ade80'],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            checkoutPurchaseChart = new ApexCharts(document.querySelector("#checkoutPurchaseChart"), options);
            checkoutPurchaseChart.render();
        }

        // Atualizar gráfico de pedidos por dia com novos dados
        function updateDailyOrdersChart(ordersPerDay) {
            if (!ordersPerDay || !dailyOrdersChart) return;

            const categories = ordersPerDay.map(item => item.day);
            const values = ordersPerDay.map(item => item.total_net_amount);

            dailyOrdersChart.updateOptions({
                xaxis: {
                    categories: categories
                }
            });

            dailyOrdersChart.updateSeries([{
                name: 'Valor Total de Pedidos',
                data: values
            }]);
        }

        function updateCheckoutAccessChart(checkoutAccess) {
            if (!checkoutAccess || !checkoutAccessChart) return;

            const categories = checkoutAccess.map(item => item.day);
            const values = checkoutAccess.map(item => item.count);

            checkoutAccessChart.updateOptions({
                xaxis: {
                    categories: categories
                }
            });

            checkoutAccessChart.updateSeries([{
                name: 'Acessos Total',
                data: values
            }]);
        }

        function updateBeginCheckoutChart(beginCheckout) {
            if (!beginCheckout || !beginCheckoutChart) return;

            const categories = beginCheckout.map(item => item.day);
            const values = beginCheckout.map(item => item.count);

            beginCheckoutChart.updateOptions({
                xaxis: {
                    categories: categories
                }
            });

            beginCheckoutChart.updateSeries([{
                name: 'Iniciados Total',
                data: values
            }]);
        }

        function updateCheckoutPurchaseChart(checkoutPurchase) {
            if (!checkoutPurchase || !checkoutPurchaseChart) return;

            const categories = checkoutPurchase.map(item => item.day);
            const values = checkoutPurchase.map(item => item.count);

            checkoutPurchaseChart.updateOptions({
                xaxis: {
                    categories: categories
                }
            });

            checkoutPurchaseChart.updateSeries([{
                name: 'Total',
                data: values
            }]);
        }

        // Atualizar gráfico de pedidos por dia com forma de pagamento
        function updateDailyOrdersPaymentMethodChart(ordersPerDayWithPaymentMethod) {
            if (!ordersPerDayWithPaymentMethod || !dailyOrdersPaymentMethodChart) return;

            // Extrair datas únicas
            const dates = [...new Set(ordersPerDayWithPaymentMethod.map(item => item.day))];

            // Extrair métodos de pagamento únicos
            const paymentMethods = [...new Set(ordersPerDayWithPaymentMethod.map(item => item.payment_method_translated))];

            // Preparar dados para o gráfico
            const series = paymentMethods.map(method => {
                return {
                    name: method,
                    data: dates.map(date => {
                        const match = ordersPerDayWithPaymentMethod.find(item =>
                            item.day === date && item.payment_method_translated === method
                        );
                        return match ? match.total_net_amount : 0;
                    })
                };
            });

            dailyOrdersPaymentMethodChart.updateOptions({
                xaxis: {
                    categories: dates
                }
            });

            dailyOrdersPaymentMethodChart.updateSeries(series);
        }

        // Atualizar gráfico de assinaturas
        function updateSubscriptionsChart(subscriptionsData) {
            if (!subscriptionsData || !subscriptionsChart) return;

            const { new_subscribers = 0, maintained_subscribers = 0, cancellations = 0 } = subscriptionsData;

            subscriptionsChart.updateSeries([new_subscribers, maintained_subscribers, cancellations]);
        }

        // Atualizar hora da última atualização
        function updateLastUpdateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR');
            document.getElementById('lastUpdate').textContent = `Última atualização: ${timeString}`;
        }
    </script>
@endpush