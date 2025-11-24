<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get all cart items.
     *
     * @return array<int, array{product: \App\Models\Product, quantity: int}>
     */
    public function getItems(): array
    {
        /** @var array<int, int> $cart */
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return [];
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        /** @var array<int, array{product: Product, quantity: int}> $items */
        $items = collect($cart)
            ->map(function ($quantity, $productId) use ($products) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => (int) $quantity,
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        return $items;
    }

    /**
     * Add product to cart.
     */
    public function add(int $productId, int $quantity = 1): void
    {
        /** @var array<int, int> $cart */
        $cart = Session::get('cart', []);
        $currentQuantity = $cart[$productId] ?? 0;
        $cart[$productId] = $currentQuantity + $quantity;
        Session::put('cart', $cart);
    }

    /**
     * Update product quantity in cart.
     */
    public function update(int $productId, int $quantity): void
    {
        /** @var array<int, int> $cart */
        $cart = Session::get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        Session::put('cart', $cart);
    }

    /**
     * Remove product from cart.
     */
    public function remove(int $productId): void
    {
        /** @var array<int, int> $cart */
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);
    }

    /**
     * Clear the cart.
     */
    public function clear(): void
    {
        Session::forget('cart');
    }

    /**
     * Get cart total.
     */
    public function getTotal(): float
    {
        $items = $this->getItems();

        return collect($items)
            ->sum(fn ($item) => $item['product']->price * $item['quantity']);
    }

    /**
     * Get cart item count.
     */
    public function getItemCount(): int
    {
        /** @var array<int, int> $cart */
        $cart = Session::get('cart', []);

        return (int) array_sum($cart);
    }
}
