<?php

namespace App\Services\SuitPay;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SuitpayService
{

    public PendingRequest $api;

    public function __construct(string $ci, string $cs)
    {
        $this->api = Http::withHeaders([
            'ci'  => $ci,
            'cs' => $cs,
        ])->baseUrl(config('services.suitpay.base_url'));

        $this->api->acceptJson();
    }

    public function init(): PendingRequest
    {
        return $this->api;
    }

}
