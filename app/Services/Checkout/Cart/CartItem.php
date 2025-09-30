<?php

namespace App\Services\Checkout\Cart;

use App\Models\Product;

class CartItem
{
    private Product $product;
    private int $quantity;
    private float $price;
    private bool $isOrderBump;

    public function __construct(Product $product, float $price, int $quantity = 1, bool $isOrderBump = false)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->isOrderBump = $isOrderBump;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function isOrderBump(): bool
    {
        return $this->isOrderBump;
    }
}