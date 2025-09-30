<?php

namespace App\Http\Controllers\Auth\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\V2\ExternalLoginRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\{JsonResponse, RedirectResponse, Response};
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class ExternalLoginApiController extends Controller
{
    public function __invoke(ExternalLoginRequest $request): RedirectResponse|JsonResponse
    {
        $originDomain = preg_replace('/^.*?([^\.]+\.[^\.]+)$/', '$1', parse_url($request->headers->get('origin'), PHP_URL_HOST));
        $validDomain  = preg_replace('/^.*?([^\.]+\.[^\.]+)$/', '$1', parse_url(config('app.url'), PHP_URL_HOST));

        if ($originDomain !== $validDomain) {
            return response()->json(['success' => false, 'message' => 'Invalid origin.'], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = PersonalAccessToken::findToken($request->validated('_token'));

        if (! $accessToken || $accessToken->tokenable->email !== $request->validated('email')) {
            return response()->json(['success' => false, 'message' => 'Invalid token or email.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $accessToken->tokenable;

        Auth::login($user, true);

        $response = [
            'success'       => true,
            'urlToRedirect' => route('dashboard.home.index'),
            'user'          => new UserResource($accessToken->tokenable),
        ];

        return $request->ajax()
            ? response()->json($response)
            : to_route('dashboard.home.index');
    }
}
