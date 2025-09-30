<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ExternalLoginRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class ExternalLoginApiController extends Controller
{

    public function __invoke(ExternalLoginRequest $request): RedirectResponse|JsonResponse
    {
        $originDomain = preg_replace('/^.*?([^\.]+\.[^\.]+)$/', '$1', parse_url($request->headers->get('origin'), PHP_URL_HOST));
        $validDomain  =  preg_replace('/^.*?([^\.]+\.[^\.]+)$/', '$1', parse_url(config('app.url'), PHP_URL_HOST));

        if ($originDomain !== $validDomain) {
            return response()->json(['success' => false, 'message' => 'Invalid origin.'], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = PersonalAccessToken::findToken($request->validated('_token'));

        if (!$accessToken || $accessToken->tokenable->document_number !== $request->validated('document_number')) {
            return response()->json(['success' => false, 'message' => 'Invalid token or document number.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $accessToken->tokenable;

        Auth::login($user, true);

        $response = [
            'success'       => true,
            'urlToRedirect' => route('dashboard.home.index'),
            'user'          => new UserResource($accessToken->tokenable)
        ];

        return $request->ajax()
            ? response()->json($response)
            : to_route('dashboard.home.index');
    }
}
