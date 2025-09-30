<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{

    public function index(): View
    {
        return view('dashboard.users.index');
    }

    public function show(): View
    {
        return view('dashboard.users.show');
    }

    public function profile(): View
    {
        return view('dashboard.users.profile');
    }

    public function update(UpdateUserRequest $request): RedirectResponse
    {
        user()->shop()->update($request->input('shop'));

        return back()->with('success', 'Dados atualizados com sucesso!');
    }

}
