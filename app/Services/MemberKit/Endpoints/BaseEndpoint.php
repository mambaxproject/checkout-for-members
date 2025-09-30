<?php

namespace App\Services\MemberKit\Endpoints;

use App\Services\MemberKit\MemberKitService;

class BaseEndpoint
{

    protected MemberKitService $service;

    public function __construct()
    {
        $this->service = new MemberKitService();
    }

}
