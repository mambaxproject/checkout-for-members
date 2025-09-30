<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shops\{RegenerateTokenRequest, UpdateShopRequest};
use App\Http\Resources\Api\UserResource;
use App\Models\{Shop, User};
use Illuminate\Http\{JsonResponse, Response};

class ShopsApiController extends Controller
{
    public function update(UpdateShopRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update($request->validated('user'));

        $user->address()->update($request->input('user.address'));

        $user->shop()->update($request->input('shop'));

        if ($attributes = $request->input('shop.attributes')) {
            $shop = $user->shop();
            $shop->attributes->set($attributes);
            $shop->save();
        }

        return response()->json([
            'success' => true,
            'data'    => new UserResource($user),
        ]);
    }

    public function regenerateToken(RegenerateTokenRequest $request): JsonResponse
    {
        $originDomain = preg_replace('/^.*?([^\.]+\.[^\.]+)$/', '$1', parse_url($request->headers->get('origin'), PHP_URL_HOST));
        $validDomain  = preg_replace('/^.*?([^\.]+\.[^\.]+)$/', '$1', parse_url(config('app.url'), PHP_URL_HOST));

        if ($originDomain !== $validDomain) {
            return response()->json(['success' => false, 'message' => 'Invalid origin.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->validated('email'))
            ->where('document_number', $request->validated('document_number'))
            ->first();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $shop = Shop::where('username_banking', $request->validated('username_banking'))->first();

        if (! $shop) {
            return response()->json(['success' => false, 'message' => 'Shop not found.'], Response::HTTP_NOT_FOUND);
        }

        if ($shop->owner_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'User is not the owner of the shop.'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken(User::NAME_TOKEN_FROM_BANKING)->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'data'    => new UserResource($user),
        ]);
    }

}
