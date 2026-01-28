<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form.
     */
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items = [];
        $total = 0;

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

        return view('checkout.index', compact('items', 'total'));
    }

    /**
     * Process the checkout and create the order.
     */
    public function store(CheckoutRequest $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        // Calculate total
        $total = 0;
        foreach ($cart as $productId => $quantity) {
            if ($products->has($productId)) {
                $total += $products[$productId]->price * $quantity;
            }
        }

        // Handle receipt upload
        $receiptPath = null;
        if ($request->payment_method === 'receipt' && $request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        // Create order within a transaction
        DB::transaction(function () use ($request, $cart, $products, $total, $receiptPath) {
            $order = Order::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'total_amount' => $total,
                'status' => 'pending',
                'receipt_path' => $receiptPath,
            ]);

            // Create order items
            foreach ($cart as $productId => $quantity) {
                if ($products->has($productId)) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $products[$productId]->price,
                    ]);
                }
            }
        });

        // Clear cart
        session()->forget('cart');

        return redirect()->route('checkout.success')->with('success', 'Your order has been placed successfully!');
    }

    /**
     * Display the order success page.
     */
    public function success()
    {
        return view('checkout.success');
    }
}
