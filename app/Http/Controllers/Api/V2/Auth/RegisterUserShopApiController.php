<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Requests\Api\Auth\V2\StoreUserShopRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterUserShopApiController
{
    public function __invoke(StoreUserShopRequest $request): JsonResponse
    {
        $user = User::create($request->input('user'));

        $user->roles()->sync([3]); // 3 = role 'Shop' from seed

        if ($request->filled('user.address.zipcode')) {
            $user->address()->create($request->input('user.address'));
        }

        if ($shopData = $request->input('shop')) {
            $shop = $user->shops()->create($shopData + ['owner_id' => $user->id]);

            if ($attributes = $shopData['attributes'] ?? null) {
                $shop->attributes->set($attributes);
                $shop->save();
            }
        }

        $user->load('roles', 'shops', 'address');

        return response()->json([
            'success' => true,
            'token'   => $user->createToken(User::NAME_TOKEN_FROM_BANKING)->plainTextToken,
            'data'    => new UserResource($user),
        ]);
    }

}
