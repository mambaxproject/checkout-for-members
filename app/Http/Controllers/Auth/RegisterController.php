<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeUserProducerNotification;
use App\Providers\RouteServiceProvider;
use App\Rules\AtLeastOneNumberRule;
use App\Rules\AtLeastOneSpecialCharacterRule;
use App\Rules\CapitalLetterRule;
use App\Rules\CpfRule;
use App\Rules\LowerCaseRule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data,
            [
                'name'            => ['required', 'string', 'max:255'],
                'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone_number'    => ['required', 'string', 'max:255', 'unique:users'],
                'document_number' => ['required', 'string', 'max:20', 'unique:users', new CpfRule()],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    new CapitalLetterRule(),
                    new LowerCaseRule(),
                    new AtLeastOneNumberRule(),
                    new AtLeastOneSpecialCharacterRule(),
                ],
            ],
            [
                'name.required'            => 'O campo nome é obrigatório.',
                'name.string'              => 'O campo nome deve ser uma string.',
                'name.max'                 => 'O campo nome deve ter no máximo 255 caracteres.',
                'email.required'           => 'O campo e-mail é obrigatório.',
                'email.string'             => 'O campo e-mail deve ser uma string.',
                'email.email'              => 'O campo e-mail deve ser um e-mail válido.',
                'email.max'                => 'O campo e-mail deve ter no máximo 255 caracteres.',
                'email.unique'             => 'O e-mail informado já está em uso.',
                'document_number.required' => 'O campo CPF é obrigatório.',
                'document_number.string'   => 'O campo CPF deve ser uma string.',
                'document_number.max'      => 'O campo CPF deve ter no máximo 20 caracteres.',
                'document_number.unique'   => 'O CPF informado já está em uso.',
                'phone_number.required'    => 'O campo telefone é obrigatório.',
                'phone_number.string'      => 'O campo telefone deve ser uma string.',
                'phone_number.max'         => 'O campo telefone deve ter no máximo 255 caracteres.',
                'password.required'        => 'O campo senha é obrigatório.',
                'password.string'          => 'O campo senha deve ser uma string.',
                'password.min'             => 'O campo senha deve ter no mínimo 8 caracteres.',
                'password.confirmed'       => 'O campo senha não confere com a confirmação de senha.',
            ]
        );
    }

    protected function create(array $data): User
    {
        $dataUser = [
            'name'            => $data['name'],
            'email'           => $data['email'],
            'phone_number'    => $data['phone_number'],
            'document_number' => $data['document_number'],
            'password'        => Hash::make($data['password']),
            'verified'        => true,
        ];

        if (session('affiliate')) {
            $dataUser['verified'] = true;
        }

        return User::create($dataUser);
    }

    public function registered(Request $request, $user)
    {
        toastr()->success("Cadastro feito com sucesso. Enviamos um e-mail para você confirmar seu cadastro.");

        return back();
    }
}
