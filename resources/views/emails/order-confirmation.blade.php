<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #0a0a0a;
            color: #e5e5e5;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .email-card {
            background-color: #171717;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid #262626;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .success-icon {
            width: 64px;
            height: 64px;
            background-color: rgba(52, 211, 153, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .success-icon svg {
            width: 32px;
            height: 32px;
            color: #34d399;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 8px;
        }

        .order-number {
            color: #a3a3a3;
            font-size: 14px;
        }

        .section {
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #a3a3a3;
            margin-bottom: 12px;
        }

        .info-box {
            background-color: #262626;
            border-radius: 8px;
            padding: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #737373;
        }

        .info-value {
            color: #e5e5e5;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
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

        .items-table th:last-child {
            text-align: right;
        }

        .items-table td {
            padding: 12px 0;
            border-bottom: 1px solid #262626;
            color: #e5e5e5;
        }

        .items-table td:last-child {
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 16px 0;
            border-top: 1px solid #262626;
            margin-top: 16px;
        }

        .total-label {
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
        }

        .total-amount {
            font-size: 20px;
            font-weight: 600;
            color: #34d399;
        }

        .cta-button {
            display: block;
            width: 100%;
            padding: 14px 24px;
            background-color: #ffffff;
            color: #000000;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            border-radius: 8px;
            margin-top: 24px;
        }

        .footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #262626;
            color: #737373;
            font-size: 12px;
        }

        /* Fallback for email clients that don't support flexbox */
        .info-row {
            overflow: hidden;
        }

        .info-row .info-label {
            float: left;
        }

        .info-row .info-value {
            float: right;
        }

        .total-row {
            overflow: hidden;
        }

        .total-row .total-label {
            float: left;
        }

        .total-row .total-amount {
            float: right;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-card">
            <div class="header">
                <div class="success-icon">
                    <svg fill="none" stroke="#34d399" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1>Thank You for Your Order!</h1>
                <p class="order-number">Order #{{ $order->id }}</p>
            </div>

            <div class="section">
                <h3 class="section-title">Customer Information</h3>
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $order->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $order->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $order->phone }}</span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3 class="section-title">Order Items</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product?->name ?? 'Product Deleted' }}</td>
                                <td>{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span class="total-amount">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="section">
                <h3 class="section-title">Payment Method</h3>
                <div class="info-box">
                    <span class="info-value">{{ $order->payment_method === 'cash' ? 'Cash on Delivery' : 'Receipt Uploaded' }}</span>
                </div>
            </div>

            <a href="{{ $invoiceUrl }}" class="cta-button">Download Invoice</a>

            <div class="footer">
                <p>Thank you for shopping with us!</p>
                <p>Order placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
