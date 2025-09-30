<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\StatusAbandonedCartEnum;
use App\Http\Controllers\Controller;
use App\Models\{AbandonedCart, AbandonedCartsTracking};
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\QueryBuilder\{AllowedFilter, AllowedSort, QueryBuilder};

class AbandonedCartController extends Controller
{
    public function index(): View
    {
        $shop                      = Auth::user()->shop();
        $totalAmountAbandonedCarts = $shop->abandonedCarts()->sum('amount');

        $abandonedCarts = QueryBuilder::for(AbandonedCart::class)
            ->with([
                'product.parentProduct:id,name',
                'lastTracking:id,abandoned_carts_tracking.abandoned_cart_id,utm_source,created_at'
            ])
            ->allForUser()
            ->allowedSorts([
                'id',
                'name',
                'amount',
                'status',
                AllowedSort::field('data', 'created_at'),
                AllowedSort::callback('product_name', function ($query, $direction) {
                    $direction = $direction ? 'desc' : 'asc';

                    $query->join('products', 'abandoned_carts.product_id', '=', 'products.id')
                        ->join('products as parent_products', 'products.parent_id', '=', 'parent_products.id')
                        ->orderBy('parent_products.name', $direction)
                        ->select('abandoned_carts.*');
                }),
            ])
            ->allowedFilters([
                AllowedFilter::scope('user', 'filterByUser'),
                AllowedFilter::partial('client_abandoned_cart_uuid'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('product.parentProduct', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
                AllowedFilter::scope('payment_method', 'filterByPaymentMethod'),
                AllowedFilter::callback('start_at', fn ($query, $value) => $query->whereDate('created_at', '>=', $value)),
                AllowedFilter::callback('end_at', fn ($query, $value) => $query->whereDate('created_at', '<=', $value)),
                AllowedFilter::exact('status'),
            ])
            ->latest('id')
            ->paginate()
            ->withQueryString();

        $totalAbandonedCarts = $abandonedCarts->total();

        return view('dashboard.abandoned-carts.index', compact(
            'abandonedCarts',
            'totalAbandonedCarts',
            'totalAmountAbandonedCarts',
        ));
    }

    public function show(string $clientAbandonedCartUuid): View
    {
        $abandonedCart = AbandonedCart::where('client_abandoned_cart_uuid', $clientAbandonedCartUuid)->first();
        $this->authorize('show', $abandonedCart);

        $this->checkHasConverted($abandonedCart);

        return view('dashboard.abandoned-carts.show', compact('abandonedCart'));
    }

    private function checkHasConverted(AbandonedCart $abandonedCart): void
    {
        if ($abandonedCart->status != StatusAbandonedCartEnum::CONVERTED) {
            return;
        }

        $abandonedCartTracking = AbandonedCartsTracking::where('abandoned_cart_id', $abandonedCart->id)
            ->select('utm_medium')->first();

        if (is_null($abandonedCart)) {
            return;
        }

        $abandonedCart->convertedMethod = $abandonedCartTracking->utm_medium;
    }
}
