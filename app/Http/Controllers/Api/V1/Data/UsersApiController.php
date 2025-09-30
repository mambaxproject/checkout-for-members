<?php

namespace App\Http\Controllers\Api\V1\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ExternalLogoutRequest;
use App\Http\Requests\Api\Auth\UpdateUserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class UsersApiController extends Controller
{

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['roles']);

        return response()->json(new UserResource($user));
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update($request->validated());

        if ($request->hasFile('photo')) {
            $user->addMediaFromRequest('photo')
                ->toMediaCollection('photo');
        }

        $user->refresh();

        return response()->json(new UserResource($user));
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = $request->user();

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'Senha atualizada com sucesso.']);
    }

    public function logout(ExternalLogoutRequest $request): JsonResponse
    {
        $user = User::join('shops', 'users.id', 'shops.owner_id')
            ->where('username_banking', $request->validated('username'))
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $user->remember_token = null;
        $user->save();
        DB::table('sessions')->where('user_id', $user->id)->delete();
        return response()->json(['success' => true, 'message' => 'Logout successful.']);
    }
}
