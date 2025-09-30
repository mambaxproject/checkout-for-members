<?php

namespace App\Services\Utmify\Endpoints;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

class UtmifySendOrder extends BaseEndpoint
{
    public function __construct(string $api_token)
    {
        parent::__construct($api_token);
    }

    public function sendOrder($data): PromiseInterface|Response
    {
        return $this->service->init()->post('/api-credentials/orders', $data);
    }
}