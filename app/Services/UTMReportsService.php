<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use CyrildeWit\EloquentViewable\Support\Period;

class UTMReportsService
{
    public function getMetrics($startOfMonth, $endOfMonth): array
    {
        $shop = Auth::user()->shop();

        $products = $shop->products()
            ->whereNull('parent_id')
            ->whereHas('offers.utmLinks')
            ->get();

        $links = QueryBuilder::for($shop->utmLinks())
            ->allowedFilters([
                AllowedFilter::callback('product_id', fn ($query, $value) =>
                $query->whereHas('product', fn ($q) => $q->where('parent_id', $value))
                ),
                AllowedFilter::exact('campaign', 'utm_campaign'),
            ])
            ->get();

        $campaigns = $links
            ->filter(fn ($link) => isset($link->utm_campaign))
            ->pluck('utm_campaign')
            ->unique()
            ->values();

        $orderMetrics = $this->getOrdersMetrics($shop, $links, $startOfMonth, $endOfMonth);
        $views        = $this->getViews($links, $startOfMonth, $endOfMonth);
        $conversion   = $this->getConversion($views, $orderMetrics['ordersCount'], $orderMetrics['ordersCountPrevious']);
        $ordersByUtm  = $this->getOrdersByUtm($shop, $links, $startOfMonth, $endOfMonth);

        return [
            'campaigns'    => $campaigns,
            'products'     => $products,
            'orderMetrics' => $orderMetrics,
            'views'        => $views,
            'conversion'   => $conversion,
            'ordersByUtm'  => $ordersByUtm,
        ];
    }

    private function getOrdersMetrics($shop, $links, $startMonth, $endMonth): array
    {
        $baseQuery = fn ($start, $end) => Order::query()
            ->where('shop_id', $shop->id)
            ->whereIn('utm_link_id', $links->pluck('id'))
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('payment', fn ($q) => $q->whereIn('payment_status', Order::$statusForPaid))
            ->selectRaw('COUNT(*) as total_orders, COALESCE(SUM(net_amount), 0) as total_amount')
            ->first();

        $current = $baseQuery($startMonth, $endMonth);

        $lastStart = Carbon::parse($startMonth)->subMonth();
        $lastEnd   = Carbon::parse($endMonth)->subMonth();
        $previous  = $baseQuery($lastStart, $lastEnd);

        $currentOrders  = (int) ($current->total_orders ?? 0);
        $previousOrders = (int) ($previous->total_orders ?? 0);
        $currentAmount  = (float) ($current->total_amount ?? 0);
        $previousAmount = (float) ($previous->total_amount ?? 0);

        $ordersPercent = $previousOrders > 0
            ? (($currentOrders - $previousOrders) / $previousOrders) * 100
            : ($currentOrders > 0 ? 100 : 0);

        $amountPercent = $previousAmount > 0
            ? (($currentAmount - $previousAmount) / $previousAmount) * 100
            : ($currentAmount > 0 ? 100 : 0);

        return [
            'ordersCount'                => $currentOrders,
            'ordersCountPrevious'        => $previousOrders,
            'ordersTotalAmount'          => $currentAmount,
            'ordersTotalAmountPrevious'  => $previousAmount,
            'orderCountPercentStr'       => number_format($ordersPercent, 2),
            'orderTotalAmountPercentStr' => number_format($amountPercent, 2),
        ];
    }

    private function getViews($links, $startMonth, $endMonth): array
    {
        $periodCurrent = Period::create(Carbon::parse($startMonth), Carbon::parse($endMonth));
        $periodLast    = Period::create(
            Carbon::parse($startMonth)->subMonth()->startOfMonth(),
            Carbon::parse($endMonth)->subMonth()->endOfMonth()
        );

        $currentViews = $links->sum(fn ($link) =>
        views($link)->period($periodCurrent)->unique()->count()
        );

        $previousViews = $links->sum(fn ($link) =>
        views($link)->period($periodLast)->unique()->count()
        );

        $percent = $previousViews > 0
            ? (($currentViews - $previousViews) / $previousViews) * 100
            : ($currentViews > 0 ? 100 : 0);

        return [
            'views'           => $currentViews,
            'viewsLastMonth'  => $previousViews,
            'viewsPercentStr' => number_format($percent, 2),
        ];
    }

    private function getOrdersByUtm($shop, $links, $startOfMonth, $endOfMonth)
    {
        $ordersByUtm = Order::query()
            ->with(['utmLink.product'])
            ->where('orders.shop_id', $shop->id)
            ->whereIn('orders.utm_link_id', $links->pluck('id'))
            ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
            ->whereHas('payment', fn ($q) => $q->whereIn('payment_status', Order::$statusForPaid))
            ->select('orders.utm_link_id')
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(net_amount) as total_amount')
            ->selectRaw("
        (SELECT COUNT(DISTINCT visitor)
         FROM views
         WHERE views.viewable_type = 'App\\\Models\\\UtmLink'
           AND views.viewable_id = orders.utm_link_id
           AND created_at BETWEEN ? AND ?) as total_clicks
    ", [$startOfMonth, $endOfMonth])
            ->selectRaw("
        CASE WHEN (SELECT COUNT(DISTINCT visitor)
                   FROM views
                   WHERE views.viewable_type = 'App\\\Models\\\UtmLink'
                     AND views.viewable_id = orders.utm_link_id
                     AND created_at BETWEEN ? AND ?) > 0
             THEN (COUNT(*) * 100.0 / (SELECT COUNT(DISTINCT visitor)
                                       FROM views
                                       WHERE views.viewable_type = 'App\\\Models\\\UtmLink'
                                         AND views.viewable_id = orders.utm_link_id
                                         AND created_at BETWEEN ? AND ?))
             ELSE 0
        END as conversion_rate
    ", [$startOfMonth, $endOfMonth, $startOfMonth, $endOfMonth])
            ->groupBy('orders.utm_link_id')
            ->orderByDesc('conversion_rate')
            ->limit(5)
            ->get();

        return $ordersByUtm;
    }

    private function getConversion($view, $count, $countLast): array
    {
        $percent = $count > 0
            ? ($view['views'] > 0 ? ($count / $view['views']) * 100 : 0)
            : 0;

        $percentLast = $countLast > 0
            ? ($view['viewsLastMonth'] > 0 ? ($countLast / $view['viewsLastMonth']) * 100 : 0)
            : 0;

        if ($percentLast == 0) {
            $viewsPercentStr = $percent > 0 ? '100' : '0';
        } else {
            $variation = (($percent - $percentLast) / $percentLast) * 100;
            $sign = $variation >= 0 ? '' : '-';
            $viewsPercentStr = $sign . number_format($variation, 2);
        }

        return [
            'percent'         => number_format($percent, 2),
            'percentLast'     => number_format($percentLast, 2),
            'viewsPercentStr' => $viewsPercentStr,
        ];
    }
}
