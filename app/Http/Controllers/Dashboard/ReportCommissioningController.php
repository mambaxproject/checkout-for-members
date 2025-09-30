<?php

namespace App\Http\Controllers\Dashboard;

use App\Services\CommissioningOrderService;
use Illuminate\View\View;

class ReportCommissioningController
{
    public function __construct(
        public CommissioningOrderService $commissioningOrderService
    ) {}

    public function index(): View
    {
        $totalValueCommissioningFromPaidOrdersShop = $this->commissioningOrderService->getTotalValueCommissioningFromPaidOrdersShop();

        $quantityCommissionedShop = $this->commissioningOrderService->getQuantityCommissionedShop();

        $quantityOrdersCommissionedShop = $this->commissioningOrderService->getQuantityOrdersCommissionedShop();

        $topCommissionedShop = $this->commissioningOrderService->getTopCommissionedShop();

        $commissioningOrders = $this->commissioningOrderService->getCommissionedOrdersShop();

        return view('dashboard.reportCommissioning.index', compact(
            'totalValueCommissioningFromPaidOrdersShop',
            'quantityCommissionedShop',
            'quantityOrdersCommissionedShop',
            'topCommissionedShop',
            'commissioningOrders'
        ));
    }

}
