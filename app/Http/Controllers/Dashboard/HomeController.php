<?php

namespace App\Http\Controllers\Dashboard;

use App\Services\DashboardService;
use Illuminate\View\View;

class HomeController
{
    public function __construct(
        protected DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $totalRevenuePaidOrders = $this->dashboardService->getTotalRevenuePaidOrders();

        $infosOrdersByPaymentMethod = $this->dashboardService->getInfosOrdersByPaymentMethod();

        $totalCommissionAffiliatePaidOrders = $this->dashboardService->getTotalCommissionAffiliatePaidOrders();

        $totalCommissionCoproducerPaidOrders = $this->dashboardService->getTotalCommissionCoproducerPaidOrders();

        $ordersPerDay = $this->dashboardService->ordersGroupedByDay();

        $totalAbandonedCarts = $this->dashboardService->getTotalAbandonedCarts();

        $topSellingProducts = $this->dashboardService->getTopSellingProducts();

        $percentOrdersRefunded = $this->dashboardService->getPercentOrdersRefunded();

        $percentOrdersChargeback = $this->dashboardService->getPercentOrdersChargeback();

        $quantitiesSubscriptionsPerSituation = $this->dashboardService->getQuantitiesSubscriptionsPerSituation();

        $topAffiliates = $this->dashboardService->geTopAffiliates();

        return view('dashboard.home.index', compact(
            'totalRevenuePaidOrders',
            'infosOrdersByPaymentMethod',
            'totalCommissionAffiliatePaidOrders',
            'totalCommissionCoproducerPaidOrders',
            'ordersPerDay',
            'totalAbandonedCarts',
            'topSellingProducts',
            'percentOrdersRefunded',
            'percentOrdersChargeback',
            'quantitiesSubscriptionsPerSituation',
            'topAffiliates',
        ));
    }

}