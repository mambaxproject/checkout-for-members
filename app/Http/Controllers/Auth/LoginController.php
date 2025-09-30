<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\{RedirectResponse, Request};
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        auth()->user()->refresh();

        $role = auth()->user()->roles->pluck('title')->first();

        return match ($role) {
            'Admin' => route('admin.products.index'),
            default => route('dashboard.home.index'),
        };
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->two_factor) {
            $user->generateTwoFactorCode();
            $user->notify(new TwoFactorCodeNotification);
        }
    }

    protected function loggedOut(Request $request)
    {
        return redirect('https://web.suitpay.app');
    }

    public function handleProviderCallbackSocialLogin(string $driver): RedirectResponse
    {
        try {
            $user = Socialite::driver($driver)->stateless()->user();

            if (! $user->getEmail()) {
                return to_route('login')->with('info', 'Não foi possível obter o seu e-mail do Google. Tente novamente, por favor.');
            }

            $authUser = User::firstOrCreate(
                ['email' => $user->getEmail()],
                [
                    'name'     => $user->getName(),
                    'email'    => $user->getEmail(),
                    'password' => bcrypt($user->getId()),
                    'approved' => true,
                    'verified' => true,
                ]
            );

            $authUser->attributes = [
                'provider'    => $driver,
                'provider_id' => $user->getId(),
            ];

            $authUser->save();

            auth()->login($authUser, true);

            return redirect($this->redirectTo());
        } catch (\Exception $e) {
            return to_route('login')->with('error', 'Ocorreu um erro inesperado. Tente novamente, por favor.');
        }
    }

}
