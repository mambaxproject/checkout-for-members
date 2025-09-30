<?php

namespace App\Services\MemberKit\Endpoints;

class User extends BaseEndpoint
{

    public function newUser(string $token, array $dataUser): array
    {
        return $this->service->init()
            ->post("/users?api_key={$token}", $dataUser)
            ->json();
    }

}
