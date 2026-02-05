<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
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

        .cancel-icon {
            width: 100px;
            height: 100px;
            background: #f59e0b;
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease;
        }

        .cancel-icon svg {
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
            background: #fef3c7;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #fde68a;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #92400e;
            font-weight: 600;
        }

        .detail-value {
            color: #78350f;
            font-weight: 600;
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
        <div class="cancel-icon">
            <svg viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>

        <h1>⚠️ Payment Cancelled</h1>
        <p class="message">Your payment was cancelled. No charges were made to your account.</p>

        <div class="order-details">
            <div class="detail-row">
                <span class="detail-label">Order Number:</span>
                <span class="detail-value">{{ $order->order_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount:</span>
                <span class="detail-value">{{ number_format($order->total, 2) }} SAR</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value">{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order Status:</span>
                <span class="detail-value">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        <p class="close-message">You can close this window and try again.</p>
    </div>
</body>
</html>
