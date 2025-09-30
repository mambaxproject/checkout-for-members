<?php

namespace App\Services\SuitPay\Endpoints;

use App\Services\SuitPay\SuitpayService;

class BaseEndpoint
{

    protected SuitpayService $service;

    public function __construct(string $ci, string $cs)
    {
        $this->service = new SuitpayService($ci, $cs);
    }

}
