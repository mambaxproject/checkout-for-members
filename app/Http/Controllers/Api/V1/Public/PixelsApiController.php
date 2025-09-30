<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PixelResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class PixelsApiController extends Controller
{

    public function index(Product $product): JsonResponse
    {
        $pixels = QueryBuilder::for($product->pixels())
            ->with('pixelService:id,name')
            ->allowedFilters([
                AllowedFilter::exact('mark_billet'),
                AllowedFilter::exact('mark_pix'),
                AllowedFilter::exact('pixelService.id'),
            ])
            ->active()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => PixelResource::collection($pixels),
        ], Response::HTTP_OK);
    }

}
