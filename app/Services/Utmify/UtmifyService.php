<?php

namespace App\Services\Utmify;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class UtmifyService
{
    public PendingRequest $api;

    public function __construct(string $api_token)
    {
        $this->api = Http::withHeaders([
            'x-api-token' => $api_token,
        ])->baseUrl(config('services.utmify.base_url'));

        $this->api->acceptJson();
    }

    public function init(): PendingRequest
    {
        return $this->api;
    }
}