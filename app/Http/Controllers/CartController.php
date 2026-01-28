<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = session('cart', []);
        $items = [];
        $total = 0;

        if (!empty($cart)) {
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
        }

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Product $product)
    {
        if (!$product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]++;
        } else {
            $cart[$product->id] = 1;
        }

        session(['cart' => $cart]);

        return back()->with('success', "{$product->name} added to cart.");
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] = $request->quantity;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Product $product)
    {
        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
        }

        return back()->with('success', "{$product->name} removed from cart.");
    }

    /**
     * Get the cart count for display.
     */
    public static function getCartCount(): int
    {
        $cart = session('cart', []);
        return array_sum($cart);
    }
}
