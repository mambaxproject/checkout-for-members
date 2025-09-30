<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\Auth\StoreUserShopRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterUserShopApiController
{
    public function __invoke(StoreUserShopRequest $request): JsonResponse
    {
        $user = User::create($request->input('user'));

        $user->roles()->sync([3]); // 3 = role 'Shop' from seed

        $user->address()->create($request->input('user.address'));

        $shop = $user->shops()->create($request->input('shop') + ['owner_id' => $user->id]);

        if ($attributes = $request->input('shop.attributes')) {
            $shop->attributes->set($attributes);
            $shop->save();
        }

        $token = $user->createToken(User::NAME_TOKEN_FROM_BANKING)->plainTextToken;

        $user->load('roles', 'shops', 'address');

        return response()->json([
            'success' => true,
            'token'   => $token,
            'data'    => new UserResource($user),
        ]);
    }

}
