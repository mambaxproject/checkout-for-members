<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Http;

class Webhookn8nApiController extends Controller
{
    public function webhookn8n(Request $request): JsonResponse
    {
        $webhookn8nUrl = config('services.webhookn8n.url');
        $response = Http::accept('application/json')->post($webhookn8nUrl, $request->toArray())->json();
        return response()->json($response);
    }
}