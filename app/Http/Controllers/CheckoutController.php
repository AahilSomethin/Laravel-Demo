<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        // Generate a unique checkout token for idempotency
        $checkoutToken = session('checkout_token');
        if (!$checkoutToken) {
            $checkoutToken = Str::random(64);
            session(['checkout_token' => $checkoutToken]);
        }

        return view('checkout.index', [
            'items' => $cartDetails['items'],
            'total' => $cartDetails['total'],
            'checkoutToken' => $checkoutToken,
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

        $checkoutToken = $request->input('checkout_token');

        // Idempotency check: if order with this token exists, return success
        if ($checkoutToken) {
            $existingOrder = Order::where('checkout_token', $checkoutToken)->first();
            if ($existingOrder) {
                // Clear session token and cart, return success for existing order
                session()->forget('checkout_token');
                $this->cartService->clear();
                return redirect()->route('checkout.success')
                    ->with('success', 'Your order has been placed successfully!')
                    ->with('order_id', $existingOrder->id);
            }
        }

        $cart = $this->cartService->getCart();

        // Handle receipt upload before transaction (file I/O should not be in transaction)
        $receiptPath = null;
        if ($request->payment_method === 'receipt' && $request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $order = null;
        $inactiveProducts = [];

        try {
            $order = DB::transaction(function () use ($request, $cart, $checkoutToken, $receiptPath, &$inactiveProducts) {
                // Re-fetch products inside transaction to get fresh is_active status
                $productIds = array_keys($cart);
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                // Check for inactive products
                foreach ($cart as $productId => $quantity) {
                    if (!$products->has($productId) || !$products[$productId]->is_active) {
                        $productName = $products->has($productId) ? $products[$productId]->name : "Product #{$productId}";
                        $inactiveProducts[] = $productName;
                    }
                }

                if (!empty($inactiveProducts)) {
                    throw new \Exception('inactive_products');
                }

                // Calculate total with fresh prices
                $total = 0;
                foreach ($cart as $productId => $quantity) {
                    $total += $products[$productId]->price * $quantity;
                }

                // Create the order
                $order = Order::create([
                    'checkout_token' => $checkoutToken,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'payment_method' => $request->payment_method,
                    'total_amount' => $total,
                    'status' => 'pending',
                    'receipt_path' => $receiptPath,
                ]);

                // Create order items with price at time of purchase
                foreach ($cart as $productId => $quantity) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $products[$productId]->price,
                    ]);
                }

                return $order;
            });
        } catch (\Exception $e) {
            if ($e->getMessage() === 'inactive_products') {
                $productList = implode(', ', $inactiveProducts);
                return redirect()->route('cart.index')
                    ->with('error', "The following products are no longer available: {$productList}. Please remove them from your cart.");
            }
            throw $e;
        }

        // Clear cart and checkout token
        $this->cartService->clear();
        session()->forget('checkout_token');

        // Send order confirmation email
        $this->sendOrderConfirmationEmail($order);

        return redirect()->route('checkout.success')
            ->with('success', 'Your order has been placed successfully!')
            ->with('order_id', $order->id);
    }

    /**
     * Send order confirmation email.
     * If sending fails, log the error but don't break the order flow.
     */
    protected function sendOrderConfirmationEmail(Order $order): void
    {
        try {
            Mail::to($order->email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'email' => $order->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the order success page.
     */
    public function success()
    {
        $orderId = session('order_id');

        return view('checkout.success', [
            'orderId' => $orderId,
        ]);
    }
}
