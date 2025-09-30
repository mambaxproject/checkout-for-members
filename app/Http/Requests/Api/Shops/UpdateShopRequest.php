<?php

namespace App\Http\Requests\Api\Shops;

use Carbon\Carbon;
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
            ],
            'user.document_number' => [
                'string',
                'required',
                'unique:users,document_number,' . auth()->id(),
            ],
            'user.birthday' => [
                'required',
                'date',
            ],
            'user.address.zipcode' => [
                'string',
                'required',
                'regex:/^\d{5}-\d{3}$/', // 99999-999
            ],
            'user.address.street_address' => [
                'string',
                'required',
                'min:3',
                'max:255',
            ],
            'user.address.neighborhood' => [
                'string',
                'required',
                'min:3',
                'max:255',
            ],
            'user.address.number' => [
                'string',
                'required',
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
                'required',
                'min:3',
                'max:255',
            ],
            'user.address.state' => [
                'string',
                'required',
                'min:2',
                'max:2',
            ],
            'shop.name' => [
                'string',
                'required',
                'min:3',
                'max:255',
            ],
            'shop.username_banking' => [
                'nullable',
                'string',
                'unique:shops,username_banking,' . auth()->user()->shop()->id,
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
                'unique:shops,client_id_banking,' . auth()->user()->shop()->id,
            ],
            'shop.client_secret_banking' => [
                'nullable',
                'string',
                'unique:shops,client_secret_banking,' . auth()->user()->shop()->id,
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user' => array_merge($this->input('user', []), [
                'document_number' => preg_replace('/[^0-9]/', '', $this->input('user.document_number')),
            ]),
        ]);
    }

    public function passedValidation(): void
    {
        $defaultPassword = Carbon::parse($this->input('user.birthday'))->format('dmY');

        $this->merge(array_merge_recursive($this->all(), [
            'user' => [
                'verified' => true,
                'password' => bcrypt(preg_replace('/[^0-9]/', '', $defaultPassword)),
                'address'  => [
                    'name' => 'Endereço ' . $this->input('user.name'),
                ],
            ],
        ]));
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

}
