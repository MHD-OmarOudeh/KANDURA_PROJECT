<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .invoice-details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .total { font-weight: bold; font-size: 18px; text-align: right; margin-top: 20px; padding: 10px; background-color: #f5f5f5; }
        .info-row { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p><strong>{{ $invoice->invoice_number }}</strong></p>
    </div>

    <div class="invoice-details">
        <div class="info-row"><strong>Customer:</strong> {{ $invoice->order->user->name }}</div>
        <div class="info-row"><strong>Email:</strong> {{ $invoice->order->user->email }}</div>
        <div class="info-row"><strong>Date:</strong> {{ $invoice->created_at->format('Y-m-d H:i') }}</div>
        <div class="info-row"><strong>Order Number:</strong> {{ $invoice->order->order_number }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->orderItems as $item)
            <tr>
                <td>Design #{{ $item->design_id }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>${{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <div>Subtotal: ${{ number_format($invoice->order->subtotal, 2) }}</div>
        <div>Tax: ${{ number_format($invoice->order->tax, 2) }}</div>
        <div>Shipping: ${{ number_format($invoice->order->shipping_cost, 2) }}</div>
        <div style="font-size: 20px; margin-top: 10px;">TOTAL: ${{ number_format($invoice->total, 2) }}</div>
    </div>
</body>
</html>
