<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Download the invoice for an order as HTML.
     */
    public function download(Order $order): Response
    {
        $order->load('items.product');

        $html = view('invoices.order', compact('order'))->render();

        $filename = "invoice-order-{$order->id}.html";

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
