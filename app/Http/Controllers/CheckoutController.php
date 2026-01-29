<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Display the checkout form.
     */
    public function index()
    {
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartDetails = $this->cartService->getCartWithDetails();

        return view('checkout.index', [
            'items' => $cartDetails['items'],
            'total' => $cartDetails['total'],
        ]);
    }

    /**
     * Process the checkout and create the order.
     */
    public function store(CheckoutRequest $request)
    {
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartDetails = $this->cartService->getCartWithDetails();
        $cart = $this->cartService->getCart();
        $products = $cartDetails['products'];
        $total = $cartDetails['total'];

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
        $this->cartService->clear();

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
