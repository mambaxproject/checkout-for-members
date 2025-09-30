<?php

namespace App\Services\SuitPay\Endpoints;

use Illuminate\Http\Client\Response;

class SuitpayCRMService extends BaseEndpoint
{
    public function __construct(string $ci, string $cs)
    {
        parent::__construct($ci, $cs);
    }

    public function getPipelines(): array
    {
        return $this->service->init()
            ->get('/api/v1/crm/sales/pipeline')
            ->json();
    }

    public function sendOrder($data): Response
    {
        return $this->service->init()
            ->post('api/v1/crm/sales/createOpportunitySales', $data);
    }
}
