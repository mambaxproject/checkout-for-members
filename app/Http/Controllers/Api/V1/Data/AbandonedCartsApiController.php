<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Data\AbandonedCart\AbandonedCartResource;
use App\Models\AbandonedCart;
use Illuminate\Http\{JsonResponse, Request, Response};
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class AbandonedCartsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $abandonedCarts = QueryBuilder::for(AbandonedCart::class)
            ->with(['product.parentProduct:id,name,code'])
            ->fromShop()
            ->allowedFilters([
                AllowedFilter::scope('user', 'filterByUser'),
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

        return response()->json(AbandonedCartResource::collection($abandonedCarts)->resource);
    }

    public function show(AbandonedCart $abandonedCart): JsonResponse
    {
        abort_if(
            $abandonedCart?->shop?->id !== auth()->user()?->shop()?->id,
            Response::HTTP_FORBIDDEN,
            'Você não tem permissão para acessar este produto.'
        );

        $abandonedCart->load(['product.parentProduct:id,name,code']);

        return response()->json(new AbandonedCartResource($abandonedCart));
    }

}
