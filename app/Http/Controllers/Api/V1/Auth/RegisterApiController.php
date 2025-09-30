<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterApiController extends Controller
{

    public function store(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());

            event(new Registered($user));

            return response()->json([
                'message' => "Cadastro feito com sucesso. Verifique seu e-mail para ativar sua conta.",
                'data' => [
                    'user' => $user,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
