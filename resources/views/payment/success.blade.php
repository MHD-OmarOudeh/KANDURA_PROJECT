<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: #22c55e;
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease;
        }

        .success-icon svg {
            width: 60px;
            height: 60px;
            stroke: white;
            stroke-width: 3;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        h1 {
            color: #1e293b;
            font-size: 2em;
            margin-bottom: 15px;
        }

        .message {
            color: #64748b;
            font-size: 1.1em;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #64748b;
            font-weight: 600;
        }

        .detail-value {
            color: #1e293b;
            font-weight: 600;
        }

        .amount {
            color: #22c55e;
            font-size: 1.3em;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .close-message {
            color: #94a3b8;
            font-size: 0.9em;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <svg viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h1>✅ Payment Successful!</h1>
        <p class="message">Your payment has been processed successfully. Thank you for your order!</p>

        <div class="order-details">
            <div class="detail-row">
                <span class="detail-label">Order Number:</span>
                <span class="detail-value">{{ $order->order_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount Paid:</span>
                <span class="detail-value amount">{{ number_format($order->total, 2) }} SAR</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value" style="color: #22c55e;">{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order Status:</span>
                <span class="detail-value" style="color: #3b82f6;">{{ ucfirst($order->status) }}</span>
            </div>
            @if($sessionId)
            <div class="detail-row">
                <span class="detail-label">Session ID:</span>
                <span class="detail-value" style="font-size: 0.85em; word-break: break-all;">{{ $sessionId }}</span>
            </div>
            @endif
        </div>

        <p class="close-message">You can close this window now.</p>
    </div>
</body>
</html>
