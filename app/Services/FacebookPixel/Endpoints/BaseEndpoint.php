<?php

namespace App\Services\FacebookPixel\Endpoints;

use App\Services\FacebookPixel\FacebookPixelService;

class BaseEndpoint
{
    protected FacebookPixelService $service;

    public function __construct() {
        $this->service = new FacebookPixelService();
    }
}