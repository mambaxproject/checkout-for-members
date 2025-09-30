<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Data\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\{JsonResponse, Request, Response};
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class OrdersApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = QueryBuilder::for(Order::class)
            ->isOrder()
            ->fromShop()
            ->with([
                'user:id,name,email,document_number,phone_number',
                'items.product' => fn ($query) => $query->with([
                    'media',
                    'category:id,name',
                ]),
                'payments',
                'affiliate',
            ])
            ->withWhereHas('payments')
            ->latest('id')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::callback('product', function ($query, $value) {
                    $query->whereHas('items.product', fn ($query) => $query->where('name', 'LIKE', "%$value%"));
                }),
                AllowedFilter::scope('payment_method', 'filterByPaymentMethod'),
                AllowedFilter::scope('payment_status', 'FilterByPaymentStatus'),
                AllowedFilter::callback('start_at', fn ($query, $value) => $query->whereDate('created_at', '>=', $value)),
                AllowedFilter::callback('end_at', fn ($query, $value) => $query->whereDate('created_at', '<=', $value)),
            ])
            ->orderByDesc('id')
            ->paginate()
            ->withQueryString();

        return response()->json(OrderResource::collection($orders)->resource);
    }

    public function show(Order $order): JsonResponse
    {
        abort_if(
            user()?->shop()?->id !== $order->shop_id,
            Response::HTTP_FORBIDDEN,
            'Você não tem permissão para acessar este pedido.'
        );

        $order->load([
            'user:id,name,email,document_number,phone_number',
            'items.product' => fn ($query) => $query->with([
                'media',
                'category:id,name',
            ]),
            'payments',
            'affiliate',
        ]);

        return response()->json(new OrderResource($order));
    }

}
