<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    /**
     * Maximum quantity allowed per product in cart.
     */
    public const MAX_QUANTITY_PER_PRODUCT = 99;

    /**
     * Get the raw cart data from session.
     *
     * @return array<int, int> Product ID => Quantity
     */
    public function getCart(): array
    {
        return session('cart', []);
    }

    /**
     * Get cart with detailed product information.
     *
     * @return array{items: array, total: float, products: \Illuminate\Database\Eloquent\Collection}
     */
    public function getCartWithDetails(): array
    {
        $cart = $this->getCart();
        $items = [];
        $total = 0;

        if (empty($cart)) {
            return [
                'items' => [],
                'total' => 0,
                'products' => collect(),
            ];
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        foreach ($cart as $productId => $quantity) {
            if ($products->has($productId)) {
                $product = $products[$productId];
                $subtotal = $product->price * $quantity;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return [
            'items' => $items,
            'total' => $total,
            'products' => $products,
        ];
    }

    /**
     * Get the total cart value.
     */
    public function getTotal(): float
    {
        return $this->getCartWithDetails()['total'];
    }

    /**
     * Get the total number of items in cart.
     */
    public function getCount(): int
    {
        return array_sum($this->getCart());
    }

    /**
     * Check if the cart is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }

    /**
     * Add a product to the cart.
     *
     * @return bool True if added successfully, false if max quantity reached
     */
    public function addItem(Product $product): bool
    {
        $cart = $this->getCart();
        $currentQuantity = $cart[$product->id] ?? 0;

        if ($currentQuantity >= self::MAX_QUANTITY_PER_PRODUCT) {
            return false;
        }

        $cart[$product->id] = $currentQuantity + 1;
        session(['cart' => $cart]);

        return true;
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function updateQuantity(Product $product, int $quantity): void
    {
        $cart = $this->getCart();

        if (isset($cart[$product->id])) {
            $cart[$product->id] = min($quantity, self::MAX_QUANTITY_PER_PRODUCT);
            session(['cart' => $cart]);
        }
    }

    /**
     * Remove a product from the cart.
     */
    public function removeItem(Product $product): void
    {
        $cart = $this->getCart();

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
        }
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): void
    {
        session()->forget('cart');
    }
}
