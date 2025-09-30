<?php

namespace App\Http\Requests\Api\Auth\V2;

use App\Models\User;
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
                'required',
                'string',
                'unique:users,phone_number',
            ],
            'user.document_number' => [
                'sometimes',
                'nullable',
                'unique:users,document_number',
            ],
            'user.birthday' => [
                'sometimes',
                'nullable',
                'date',
            ],
            'user.address.zipcode' => [
                'string',
                'sometimes',
                'nullable',
                'regex:/^\d{5}-\d{3}$/', // 99999-999
            ],
            'user.address.street_address' => [
                'string',
                'sometimes',
                'nullable',
                'min:3',
                'max:255',
            ],
            'user.address.neighborhood' => [
                'string',
                'sometimes',
                'nullable',
                'min:3',
                'max:255',
            ],
            'user.address.number' => [
                'string',
                'sometimes',
                'nullable',
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
                'sometimes',
                'nullable',
                'min:3',
                'max:255',
            ],
            'user.address.state' => [
                'string',
                'sometimes',
                'nullable',
                'min:2',
                'max:2',
            ],
            'shop.name' => [
                'string',
                'sometimes',
                'nullable',
                'min:3',
                'max:255',
            ],
            'shop.username_banking' => [
                'sometimes',
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
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $shopLink = $this->input('shop.link');

        if ($shopLink && ! preg_match('/^https?:\/\//', $shopLink)) {
            $shopLink = 'https://' . $shopLink;
        }

        $dataFormatted = [
            'user' => array_merge($this->input('user', []), [
                'document_number' => $this->input('user.document_number') ? preg_replace('/[^0-9]/', '', $this->input('user.document_number')) : null,
            ]),
            'shop' => array_merge($this->input('shop', []), [
                'link' => $shopLink ?? null,
            ]),
        ];

        $dataFormatted = array_filter(array_map(fn ($item) => is_array($item) ? array_filter($item) : $item, $dataFormatted));

        $this->resolveUniqueConflicts($dataFormatted);

        $this->merge($dataFormatted);
    }

    protected function resolveUniqueConflicts(array &$dataFormatted): void
    {
        $shopUsername = $this->input('shop.username_banking');

        if (! $shopUsername) {
            return;
        }

        $suffix = '_' . $shopUsername;

        if (isset($dataFormatted['user']['email'])) {
            $originalEmail = $dataFormatted['user']['email'];

            if (User::where('email', $originalEmail)->exists()) {
                $dataFormatted['user']['email'] = $originalEmail . $suffix;
            }
        }

        if (isset($dataFormatted['user']['phone_number'])) {
            $originalPhone = $dataFormatted['user']['phone_number'];

            if (User::where('phone_number', $originalPhone)->exists()) {
                $dataFormatted['user']['phone_number'] = $originalPhone . $suffix;
            }
        }

        if (isset($dataFormatted['user']['document_number']) && $dataFormatted['user']['document_number']) {
            $originalDocument = $dataFormatted['user']['document_number'];

            if (User::where('document_number', $originalDocument)->exists()) {
                $dataFormatted['user']['document_number'] = $originalDocument . $suffix;
            }
        }
    }

    public function passedValidation(): void
    {
        $defaultPassword = preg_replace('/[^0-9]/', '', $this->input('user.phone_number'));

        $this->merge(array_merge_recursive($this->all(), [
            'user' => [
                'verified' => true,
                'password' => bcrypt(preg_replace('/[^0-9]/', '', $defaultPassword)),
                'address'  => $this->input('user.address') ? [
                    'name' => 'Endereço ' . $this->input('user.name'),
                ] : null,
            ],
        ]));
    }

    public function authorize(): bool
    {
        return true;
    }

}
