<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        /* @var $product Product */
        $product = $this->route('product');
        $shopId = auth()->user()->shop()->id;

        return [
            'product.name' => [
                function ($attribute, $value, $fail) use ($shopId, $product) {
                    if (Product::whereNull('parent_id')
                        ->where('id', '<>', $product->id)
                        ->where('name', $value)
                        ->exists())
                    {
                        $fail('Este nome já está em uso. Tente outro.');
                    }
                },
            ],
            'product.offersPaymentUnique.*.name' => ['sometimes', 'distinct'],
            'product.offersPaymentRecurring.*.name' => ['sometimes', 'distinct'],
            'product' => [function ($attribute, $value, $fail) use ($product) {
                $allOffers = collect(array_merge(($value['offersPaymentUnique'] ?? []), ($value['offersPaymentRecurring'] ?? [])));

                $allOffers = $allOffers->filter(function ($value, $key) {
                    return isset($value['name']);
                });

                $allOffers->each(function ($value, $key) use ($product, $fail) {
                    $query = $product->offers()
                        ->where('name', $value['name'])
                        ->when(isset($value['id']), fn ($query) => $query->where('id', '<>', $value['id']));

                    if ($query->exists()) {
                        $fail('Esse nome já está em uso em outra oferta.');
                    }
                });
            }],
            'product.offersPaymentUnique.*.price' => ['string', function ($attribute, $value, $fail) {
                $price = (float) str_replace(['.', ','], ['', '.'], $value);

                if ($price < 5) {
                    $fail('O valor mínimo para criar uma oferta é R$5.');
                }
            }],
            'product.offersPaymentRecurring.*.price' => ['string', function ($attribute, $value, $fail) {
                $price = (float) str_replace(['.', ','], ['', '.'], $value);

                if ($price < 5) {
                    $fail('O valor mínimo para criar uma oferta é R$5.');
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'product.name' => [
                'unique' => 'Esse nome já está em uso em outro produto.',
            ],
            'product.offersPaymentUnique.*.name' => [
                'distinct' => 'Você está passando duas ofertas com o mesmo nome.',
            ],
            'product.offersPaymentRecurring.*.name' => [
                'distinct' => 'Você está passando duas ofertas com o mesmo nome.',
            ]
        ];
    }
}
