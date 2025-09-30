<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Data\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\{JsonResponse, Request, Response};
use Spatie\QueryBuilder\{AllowedFilter, QueryBuilder};

class ProductsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = user()?->shop()?->products()
            ? QueryBuilder::for(user()->shop()->products())
                ->isProduct()
                ->with(['category', 'media', 'offers'])
                ->latest('id')
                ->allowedIncludes(['couponsDiscount', 'affiliates', 'coproducers'])
                ->allowedFilters([
                    AllowedFilter::exact('id'),
                    'name',
                    AllowedFilter::exact('category_id'),
                    AllowedFilter::exact('situation'),
                ])
                ->orderByDesc('id')
                ->paginate()
                ->withQueryString()
            : collect();

        return response()->json(ProductResource::collection($products)->resource);
    }

    public function show(Product $product): JsonResponse
    {
        abort_if(
            user()?->shop()?->id !== $product->shop_id,
            Response::HTTP_FORBIDDEN,
            'Você não tem permissão para acessar este produto.'
        );

        $product->load([
            'category',
            'media',
            'offers',
            'couponsDiscount',
            'affiliates',
            'coproducers',
        ]);

        return response()->json(new ProductResource($product));
    }

}
