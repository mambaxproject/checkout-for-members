<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Services\Reports\Subscription\ReportSubscriptionService;
use App\Services\SuitPay\Endpoints\SuitpaySubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ReportController
{
    public function index(): View
    {
        return view('dashboard.reports.index');
    }

    public function metricsSubscriptions(Request $request): View
    {
        $request->mergeIfMissing([
            'filter' => [
                'period' => now()->startOfMonth()->format('d/m/Y') . ' - ' . now()->endOfMonth()->format('d/m/Y'),
            ],
        ]);

        $paidSubscriptions = Order::with(['payment', 'comments'])
            ->filterByPaymentStatus('paid')
            ->IsSubscription()
            ->fromShop()
            ->when($request->filled('filter.period'), fn ($query) => $query->searchPeriod($request->input('filter.period')))
            ->get();

        $recurrencyIds = $paidSubscriptions->pluck('payment.recurrency_id')->filter()->unique()->values();
        $shop          = auth()->user()->shop();

        $subscriptionService = new SuitpaySubscriptionService(
            $shop->client_id_banking,
            $shop->client_secret_banking
        );

        $reportSubscriptionService = new ReportSubscriptionService;
        $subscriptionsData         = [];

        foreach ($recurrencyIds as $recurrencyId) {
            try {
                $details = $subscriptionService->getDetails($recurrencyId);

                if (isset($details['recurrencyStatus']) && isset($details['transactions'])) {
                    $subscriptionsData[] = $details;
                }
            } catch (\Exception $e) {
                Log::error('Erro ao buscar detalhes da assinatura: ' . $e->getMessage(), [
                    'recurrencyId' => $recurrencyId,
                    'exception'    => $e,
                ]);
            }
        }

        $metrics = $reportSubscriptionService->processSubscriptionMetrics($subscriptionsData, $paidSubscriptions);

        return view('dashboard.reports.metrics-subscription', compact('metrics'));
    }
}
