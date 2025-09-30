<?php

namespace App\Http\Controllers\Api\V2\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shops\V2\UpdateShopRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\{JsonResponse};

class ShopsApiController extends Controller
{
    public function update(UpdateShopRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update($request->validated('user'));

        if ($request->filled('user.address.zipcode')) {
            $user->address()->updateOrCreate(
                [
                    'addressable_type' => get_class($user),
                    'addressable_id'   => $user->id,
                    'zipcode'          => $request->input('user.address.zipcode'),
                ],
                $request->input('user.address')
            );
        }

        if ($request->filled('user.shop')) {
            $shopUser = $user->shopUser()->updateOrCreate(['owner_id' => $user->id], $request->input('user.shop'));

            if ($attributes = $request->input('user.shop.attributes')) {
                $shopUser->attributes->set($attributes);
                $shopUser->save();
            }
        }

        return response()->json([
            'success' => true,
            'data'    => new UserResource($user),
        ]);
    }

}
