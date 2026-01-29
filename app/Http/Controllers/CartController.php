<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Display the cart page.
     */
    public function index()
    {
        $cartDetails = $this->cartService->getCartWithDetails();

        return view('cart.index', [
            'items' => $cartDetails['items'],
            'total' => $cartDetails['total'],
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Product $product)
    {
        if (!$product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        if (!$this->cartService->addItem($product)) {
            return back()->with('error', 'Maximum quantity (' . CartService::MAX_QUANTITY_PER_PRODUCT . ') reached for this product.');
        }

        return back()->with('success', "{$product->name} added to cart.");
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . CartService::MAX_QUANTITY_PER_PRODUCT],
        ]);

        $this->cartService->updateQuantity($product, $request->quantity);

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Product $product)
    {
        $this->cartService->removeItem($product);

        return back()->with('success', "{$product->name} removed from cart.");
    }

    /**
     * Get the cart count for display.
     */
    public static function getCartCount(): int
    {
        return app(CartService::class)->getCount();
    }
}
