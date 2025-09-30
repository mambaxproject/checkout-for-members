<?php

namespace App\Services\FacebookPixel;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FacebookPixelService
{
    public PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::baseUrl(config('services.facebook_pixel.base_url'));
    }

    public function init(): PendingRequest
    {
        return $this->api;
    }
}