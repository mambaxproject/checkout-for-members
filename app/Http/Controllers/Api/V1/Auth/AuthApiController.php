<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->getCredentials();

            if (!Auth::validate($credentials)) {
                return response()->json(['message' => 'Usuário ou senha inválidos.'], Response::HTTP_UNAUTHORIZED);
            }

            $user = Auth::getProvider()->retrieveByCredentials($credentials);

            $token = $user->createToken($request->header('User-Agent'))->plainTextToken;

            return response()->json([
                'token' => $token,
                'user'  => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso',
        ], Response::HTTP_NO_CONTENT);
    }

    public function recoveryPassword(): JsonResponse
    {
        return response()->json([
            'link' => route('password.request', ['hideBackButton' => true]),
            'message' => 'Redirecionar o usuário par o link para a página recuperação de senha.',
        ], Response::HTTP_OK);
    }

}
