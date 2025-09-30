<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Data\Product\CategoryProductResource;
use App\Models\CategoryProduct;
use Illuminate\Http\{JsonResponse};

class CategoriesProductApiController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = CategoryProduct::active()->toBase()->get(['id', 'name', 'description']);

        return response()->json(CategoryProductResource::collection($categories));
    }

}
