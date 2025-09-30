<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreApiTokenRequest;
use Illuminate\Http\RedirectResponse;

class ApiController extends Controller
{

    public function store(StoreApiTokenRequest $request): RedirectResponse
    {
        $token = $request->user()->createToken($request->name);

        return back()->with('token', $token->plainTextToken);
    }

    public function destroy(string $id): RedirectResponse
    {
        auth()->user()->tokens()->where('id', $id)->delete();

        return to_route('dashboard.apps.index')->with('success', 'Token deletado com sucesso');
    }

}
