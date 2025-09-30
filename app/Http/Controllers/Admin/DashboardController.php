<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Shop};
use Gate;
use Illuminate\Http\{Request};
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function products(Request $request): View
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopkeepers = Shop::whereNotNull('owner_id')
            ->toBase()
            ->get(['owner_id AS id', 'name']);

        return view('admin.dashboard.products', compact(
            'shopkeepers',
        ));
    }
}
