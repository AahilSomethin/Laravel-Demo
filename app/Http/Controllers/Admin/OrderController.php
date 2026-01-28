<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order (for AJAX modal).
     */
    public function show(Order $order)
    {
        $order->load('items.product');

        return response()->json([
            'id' => $order->id,
            'name' => $order->name,
            'email' => $order->email,
            'phone' => $order->phone,
            'payment_method' => $order->payment_method,
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'receipt_path' => $order->receipt_path,
            'receipt_url' => $order->receipt_path ? asset('storage/' . $order->receipt_path) : null,
            'created_at' => $order->created_at->format('M d, Y H:i'),
            'items' => $order->items->map(function ($item) {
                return [
                    'product_name' => $item->product?->name ?? 'Product Deleted',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ];
            }),
        ]);
    }
}
