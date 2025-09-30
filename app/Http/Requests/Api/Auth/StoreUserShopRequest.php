<?php

namespace App\Http\Requests\Api\Auth;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserShopRequest extends FormRequest
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
                'unique:users,email',
            ],
            'user.phone_number' => [
                'string',
                'required',
                'unique:users,phone_number',
            ],
            'user.document_number' => [
                'string',
                'required',
                'unique:users,document_number',
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
                'unique:shops,username_banking',
            ],
            'shop.description' => [
                'nullable',
            ],
            'shop.link' => [
                'nullable',
                'url',
            ]
        ];
    }

    public function prepareForValidation(): void
    {
        $shopLink = $this->input('shop.link');
        if ($shopLink && !preg_match('/^https?:\/\//', $shopLink)) {
            $shopLink = 'https://' . $shopLink;
        }

        $this->merge([
            'user' => array_merge($this->input('user', []), [
                'document_number' => preg_replace('/[^0-9]/', '', $this->input('user.document_number')),
            ]),
            'shop' => array_merge($this->input('shop', []), [
                'link' => $shopLink,
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
                'address' => [
                    'name' => "Endereço " . $this->input('user.name'),
                ],
            ],
        ]));
    }

    public function authorize(): bool
    {
        return true;
    }

}
