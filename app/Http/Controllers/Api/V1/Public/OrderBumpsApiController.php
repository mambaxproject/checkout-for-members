<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderBumpResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class OrderBumpsApiController extends Controller
{

    public function index(Product $product): JsonResponse
    {
        $orderBumps = QueryBuilder::for($product->orderBumps())
            ->with([
                'product:id,name',
                'product.media',
                'product_offer:id,name',
                'product_offer.media',
            ])
            ->allowedFilters([
                'payment_methods',
                AllowedFilter::exact('product.id'),
                AllowedFilter::exact('productOffer.id'),
            ])
            ->active()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => OrderBumpResource::collection($orderBumps),
        ], Response::HTTP_OK);
    }

}
