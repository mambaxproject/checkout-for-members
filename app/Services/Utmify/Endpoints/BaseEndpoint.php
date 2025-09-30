<?php

namespace App\Services\Utmify\Endpoints;

use App\Services\Utmify\UtmifyService;

class BaseEndpoint
{
    protected UtmifyService $service;

    public function __construct(string $api_token)
    {
        $this->service = new UtmifyService($api_token);
    }
}
