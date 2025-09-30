<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UTMReportsService;
use Illuminate\Contracts\View\View;

class UTMReportsController extends Controller
{
    public function index(): View
    {
        $period = [
            'start' => request()->input('filter.start_at') ?? now()->startOfMonth(),
            'end'   => request()->input('filter.end_at') ?? now(),
        ];

        [
            'campaigns'    => $campaigns,
            'products'     => $products,
            'orderMetrics' => $orderMetrics,
            'views'        => $views,
            'conversion'   => $conversion,
            'ordersByUtm'  => $ordersByUtm,
        ] = (new UTMReportsService())->getMetrics($period['start'], $period['end']);

        return view('dashboard.utm-reports.index', compact(
            'campaigns',
            'products',
            'orderMetrics',
            'views',
            'conversion',
            'ordersByUtm'
        ));
    }
}
