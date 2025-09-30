<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
            ],
            'email' => [
                'sometimes',
                'required',
                'unique:users,email,' . request()->user()->id,
            ],
            'phone_number' => [
                'sometimes',
                'required',
                'string',
            ],
            'document_number' => [
                'sometimes',
                'required',
            ],
            'player_onesignal' => [
                'sometimes',
                'required',
            ],
            'password' => [
                'sometimes',
                'required',
                'min:8',
                'confirmed',
            ],
            'birthday' => ['required', 'date', function ($attribute, $value, $fail) {
                $dataNascimento = new \DateTime($value);
                $idadeMinima = new \DateTime();
                $idadeMinima->modify('-18 years');

                if ($dataNascimento > $idadeMinima) {
                    $fail('Você deve ter no mínimo 18 anos de idade.');
                }
            }],
        ];
    }

}
