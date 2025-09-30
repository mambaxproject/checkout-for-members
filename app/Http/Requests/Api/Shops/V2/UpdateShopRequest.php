<?php

namespace App\Http\Requests\Api\Shops\V2;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user.name' => [
                'string',
                'required',
                'min:3',
                'max:255',
            ],
            'user.email' => [
                'required',
                'email:rfc,dns',
                'unique:users,email,' . auth()->id(),
            ],
            'user.phone_number' => [
                'string',
                'required',
                'unique:users,phone_number,' . auth()->id(),
            ],
            'user.document_number' => [
                'string',
                'nullable',
                'sometimes',
                'unique:users,document_number,' . auth()->id(),
            ],
            'user.birthday' => [
                'nullable',
                'sometimes',
                'date',
            ],
            'user.address.zipcode' => [
                'string',
                'nullable',
                'sometimes',
                'regex:/^\d{5}-\d{3}$/', // 99999-999
            ],
            'user.address.street_address' => [
                'string',
                'nullable',
                'sometimes',
                'min:3',
                'max:255',
            ],
            'user.address.neighborhood' => [
                'string',
                'nullable',
                'sometimes',
                'min:3',
                'max:255',
            ],
            'user.address.number' => [
                'string',
                'nullable',
                'sometimes',
                'min:1',
                'max:10',
            ],
            'user.address.complement' => [
                'nullable',
                'string',
                'max:255',
            ],
            'user.address.city' => [
                'string',
                'nullable',
                'sometimes',
                'min:3',
                'max:255',
            ],
            'user.address.state' => [
                'string',
                'nullable',
                'sometimes',
                'min:2',
                'max:2',
            ],
            'shop.name' => [
                'string',
                'nullable',
                'sometimes',
                'min:3',
                'max:255',
            ],
            'shop.username_banking' => [
                'nullable',
                'string',
                'unique:shops,username_banking,' . optional(auth()->user()->shop())->id,
            ],
            'shop.description' => [
                'nullable',
            ],
            'shop.link' => [
                'nullable',
                'url',
            ],
            'shop.client_id_banking' => [
                'nullable',
                'string',
                'unique:shops,client_id_banking,' . optional(auth()->user()->shop())->id,
            ],
            'shop.client_secret_banking' => [
                'nullable',
                'string',
                'unique:shops,client_secret_banking,' . optional(auth()->user()->shop())->id,
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user' => array_merge($this->input('user', []), [
                'document_number' => $this->input('user.document_number') ? preg_replace('/[^0-9]/', '', $this->input('user.document_number')) : null,
            ]),
        ]);
    }

    public function passedValidation(): void
    {
        $defaultPassword = preg_replace('/[^0-9]/', '', $this->input('user.phone_number'));

        $this->merge(array_merge_recursive($this->all(), [
            'user' => [
                'verified' => true,
                'password' => bcrypt(preg_replace('/[^0-9]/', '', $defaultPassword)),
                'address'  => $this->input('user.address.zipcode') ? [
                    'name' => 'Endereço ' . $this->input('user.name'),
                ] : null,
            ],
        ]));
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

}
