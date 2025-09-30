<?php

namespace App\Rules;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ProductBelongsToUserRule implements ValidationRule
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository(new Product());
    }

    public function validate(string $attribute, mixed $productId, Closure $fail): void
    {
        if (!is_numeric($productId)) {
            return;
        }
        $user = Auth::user();
        $product = $this->productRepository->getByIdAndUserId($productId, $user->id)->first();
        if (is_null($product)) {
            $fail('Produto não pertence a esse usuário.');
        }
    }
}
