<?php

namespace App\Services\MemberKit;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MemberKitService
{
    public PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::baseUrl(config('services.memberKit.base_url'))->asJson();
    }

    public function init(): PendingRequest
    {
        return $this->api;
    }

}