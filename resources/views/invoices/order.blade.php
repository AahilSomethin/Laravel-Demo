<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #0a0a0a;
            color: #e5e5e5;
            line-height: 1.6;
            padding: 40px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #171717;
            border-radius: 12px;
            padding: 40px;
            border: 1px solid #262626;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #262626;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .invoice-number {
            font-size: 14px;
            color: #a3a3a3;
        }

        .invoice-date {
            text-align: right;
            color: #a3a3a3;
            font-size: 14px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #a3a3a3;
            margin-bottom: 12px;
        }

        .customer-info p {
            margin-bottom: 4px;
            color: #e5e5e5;
        }

        .customer-info .label {
            color: #737373;
            display: inline-block;
            width: 60px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            padding: 12px 0;
            border-bottom: 1px solid #262626;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #a3a3a3;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }

        .items-table td {
            padding: 16px 0;
            border-bottom: 1px solid #262626;
            color: #e5e5e5;
        }

        .items-table .product-name {
            color: #ffffff;
            font-weight: 500;
        }

        .items-table .product-details {
            color: #737373;
            font-size: 14px;
        }

        .totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #262626;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .totals-row.total {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            padding-top: 16px;
            margin-top: 8px;
            border-top: 1px solid #262626;
        }

        .totals-row.total .amount {
            color: #34d399;
        }

        .payment-info {
            background-color: #262626;
            border-radius: 8px;
            padding: 16px;
            margin-top: 30px;
        }

        .payment-info .label {
            color: #a3a3a3;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .payment-info .value {
            color: #ffffff;
            font-weight: 500;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #262626;
            text-align: center;
            color: #737373;
            font-size: 12px;
        }

        /* Print styles */
        @media print {
            body {
                background-color: #ffffff;
                color: #171717;
                padding: 20px;
            }

            .invoice-container {
                background-color: #ffffff;
                border: none;
                box-shadow: none;
            }

            .invoice-title,
            .items-table .product-name,
            .totals-row.total,
            .payment-info .value {
                color: #171717;
            }

            .section-title,
            .invoice-number,
            .invoice-date,
            .items-table th,
            .customer-info .label,
            .items-table .product-details,
            .payment-info .label,
            .footer {
                color: #525252;
            }

            .customer-info p,
            .items-table td {
                color: #171717;
            }

            .totals-row.total .amount {
                color: #059669;
            }

            .invoice-header,
            .items-table th,
            .items-table td,
            .totals,
            .totals-row.total,
            .footer {
                border-color: #e5e5e5;
            }

            .payment-info {
                background-color: #f5f5f5;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div>
                <h1 class="invoice-title">Invoice</h1>
                <p class="invoice-number">Order #{{ $order->id }}</p>
            </div>
            <div class="invoice-date">
                <p>Date: {{ $order->created_at->format('F d, Y') }}</p>
                <p>Time: {{ $order->created_at->format('h:i A') }}</p>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Customer Information</h3>
            <div class="customer-info">
                <p><span class="label">Name:</span> {{ $order->name }}</p>
                <p><span class="label">Email:</span> {{ $order->email }}</p>
                <p><span class="label">Phone:</span> {{ $order->phone }}</p>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="product-name">{{ $item->product?->name ?? 'Product Deleted' }}</div>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <div class="totals-row total">
                <span>Total</span>
                <span class="amount">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="payment-info">
            <p class="label">Payment Method</p>
            <p class="value">{{ $order->payment_method === 'cash' ? 'Cash on Delivery' : 'Receipt Uploaded' }}</p>
        </div>

        <div class="footer">
            <p>Thank you for your order!</p>
            <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>
</body>
</html>
