<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - {{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .main-content {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 40px;
        }

        .order-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .order-number {
            font-size: 2em;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .order-meta {
            display: flex;
            gap: 30px;
            margin-top: 15px;
        }

        .meta-item {
            font-size: 0.9em;
            color: #718096;
        }

        .meta-item strong {
            color: #2d3748;
            display: block;
            margin-bottom: 3px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 1.3em;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f7fafc;
        }

        .info-label {
            color: #718096;
            font-weight: 500;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending { background: #fef5e7; color: #d68910; }
        .status-confirmed { background: #e8f4f8; color: #1a7f9c; }
        .status-processing { background: #e9f3ff; color: #3182ce; }
        .status-completed { background: #e6f7ed; color: #27ae60; }
        .status-cancelled { background: #fdecea; color: #e74c3c; }

        .status-paid { background: #e6f7ed; color: #27ae60; }
        .status-refunded { background: #fef3c7; color: #92400e; }

        /* Items Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #f7fafc;
            padding: 12px;
            text-align: left;
            font-size: 0.85em;
            text-transform: uppercase;
            color: #718096;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Pricing */
        .pricing-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 0.95em;
        }

        .pricing-row.total {
            font-size: 1.3em;
            font-weight: 700;
            color: #667eea;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            margin-top: 10px;
        }

        /* Status Update Form */
        .status-form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .status-form select {
            flex: 1;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95em;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .status-note {
            background: #e9f3ff;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85em;
            color: #3182ce;
            margin-top: 10px;
        }

        .disabled-option {
            color: #cbd5e0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>📦 Order Details</h1>
            <a href="{{ route('dashboard.orders.index') }}" class="back-btn">← Back to Orders</a>
        </div>
    </div>

    <div class="main-content">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            ✗ {{ $errors->first() }}
        </div>
        @endif
        <!-- Order Header -->
        <div class="order-header">
            <div class="order-number">{{ $order->order_number }}</div>
            <span class="status-badge status-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>

            <div class="order-meta">
                <div class="meta-item">
                    <strong>Order Date</strong>
                    {{ $order->created_at->format('d M Y, H:i') }}
                </div>
                <div class="meta-item">
                    <strong>Payment Method</strong>
                    {{ ucfirst($order->payment_method) }}
                </div>
                <div class="meta-item">
                    <strong>Payment Status</strong>
                    <span class="status-badge status-{{ $order->payment_status }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                    @if($order->payment_status === 'refunded')
                        @if($order->payment_method === 'wallet')
                        <small style="display: block; margin-top: 5px; color: #92400e;">
                            💰 Refunded to wallet
                        </small>
                        @elseif($order->payment_method === 'card')
                        <small style="display: block; margin-top: 5px; color: #1e40af;">
                            💳 Refunded to card (5-10 days)
                        </small>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid-2">
            <!-- Left Column -->
            <div>
                <!-- Order Items -->
                <div class="card">
                    <h2 class="card-title">📦 Order Items ({{ $order->orderItems->count() }})</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Design</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($item->design->images->first())
                                            <img src="{{ asset('storage/' . $item->design->images->first()->image_path) }}"
                                                 class="item-image" alt="Design">
                                        @endif
                                        <strong>{{ $item->design->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} SAR</td>
                                <td><strong>{{ number_format($item->total_price, 2) }} SAR</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Delivery Address -->
                <div class="card" style="margin-top: 25px;">
                    <h2 class="card-title">📍 Delivery Address</h2>
                    <div class="info-row">
                        <span class="info-label">City</span>
                        <span class="info-value">{{ $order->address->city->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">District</span>
                        <span class="info-value">{{ $order->address->district }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Street</span>
                        <span class="info-value">{{ $order->address->street }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Building</span>
                        <span class="info-value">{{ $order->address->building_number }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Customer Info -->
                <div class="card">
                    <h2 class="card-title">👤 Customer</h2>
                    <div class="info-row">
                        <span class="info-label">Name</span>
                        <span class="info-value">{{ $order->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $order->user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $order->user->phone ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card" style="margin-top: 25px;">
                    <h2 class="card-title">💰 Pricing</h2>
                    <div class="pricing-row">
                        <span>Subtotal</span>
                        <span>{{ number_format($order->subtotal, 2) }} SAR</span>
                    </div>
                    <div class="pricing-row">
                        <span>Tax (15%)</span>
                        <span>{{ number_format($order->tax, 2) }} SAR</span>
                    </div>
                    <div class="pricing-row">
                        <span>Shipping</span>
                        <span>{{ number_format($order->shipping_cost, 2) }} SAR</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="pricing-row" style="color: #27ae60;">
                        <span>Discount</span>
                        <span>-{{ number_format($order->discount, 2) }} SAR</span>
                    </div>
                    @endif
                    <div class="pricing-row total">
                        <span>Total</span>
                        <span>{{ number_format($order->total, 2) }} SAR</span>
                    </div>
                </div>

                <!-- Update Status -->
                <div class="card" style="margin-top: 25px;">
                    <h2 class="card-title">⚙️ Update Status</h2>

                    @php
                        $availableStatuses = $order->getAvailableNextStatuses();
                        $statusLabels = [
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'processing' => 'Processing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled'
                        ];
                    @endphp

                    @if(!empty($availableStatuses) && $order->status !== 'completed' && $order->status !== 'cancelled')
                    <form action="{{ route('dashboard.orders.update-status', $order) }}" method="POST" class="status-form">
                        @csrf
                        @method('PUT')
                        <select name="status" required>
                            <option value="{{ $order->status }}" selected>
                                {{ $statusLabels[$order->status] }} (Current)
                            </option>
                            @foreach($availableStatuses as $status)
                                @if($status !== 'cancelled')
                                <option value="{{ $status }}">
                                    {{ $statusLabels[$status] }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                    <div class="status-note">
                        💡 Available transitions:
                        @foreach($availableStatuses as $index => $status)
                            <strong>{{ $statusLabels[$status] }}</strong>{{ $index < count($availableStatuses) - 1 ? ', ' : '' }}
                        @endforeach
                    </div>
                    @else
                    <div class="status-note">
                        ℹ️ This order is {{ $order->status }} and cannot be changed.
                    </div>
                    @endif

                    @if($order->canBeCancelled())
                    <form action="{{ route('dashboard.orders.cancel', $order) }}" method="POST" style="margin-top: 15px;">
                        @csrf
                        @php
                            $confirmMessage = 'Are you sure you want to cancel this order?';
                            if ($order->payment_status === 'paid') {
                                if ($order->payment_method === 'wallet') {
                                    $confirmMessage .= '\n\n💰 The amount (' . number_format($order->total, 2) . ' SAR) will be refunded to the customer\'s wallet immediately.';
                                } elseif ($order->payment_method === 'card') {
                                    $confirmMessage .= '\n\n💳 The amount (' . number_format($order->total, 2) . ' SAR) will be refunded to the customer\'s card within 5-10 business days.';
                                }
                            }
                        @endphp
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('{{ $confirmMessage }}')">
                            ❌ Cancel Order
                        </button>
                    </form>

                    @if($order->payment_status === 'paid')
                        @if($order->payment_method === 'wallet')
                        <div class="status-note" style="background: #fef3c7; color: #92400e; margin-top: 10px;">
                            💰 <strong>Wallet Refund:</strong> Cancelling this order will automatically refund {{ number_format($order->total, 2) }} SAR to the customer's wallet immediately.
                        </div>
                        @elseif($order->payment_method === 'card')
                        <div class="status-note" style="background: #dbeafe; color: #1e40af; margin-top: 10px;">
                            💳 <strong>Card Refund:</strong> Cancelling this order will automatically refund {{ number_format($order->total, 2) }} SAR to the customer's card. The refund will appear within 5-10 business days.
                        </div>
                        @endif
                    @endif
                    @endif

                    <!-- Status History -->
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                        <h3 style="font-size: 0.9em; color: #718096; margin-bottom: 10px;">📋 Status Timeline</h3>
                        <div style="font-size: 0.85em;">
                            @if($order->created_at)
                            <div style="padding: 5px 0;">
                                <span style="color: #718096;">Created:</span>
                                <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong>
                            </div>
                            @endif
                            @if($order->confirmed_at)
                            <div style="padding: 5px 0;">
                                <span style="color: #718096;">Confirmed:</span>
                                <strong>{{ $order->confirmed_at->format('d M Y, H:i') }}</strong>
                            </div>
                            @endif
                            @if($order->processing_at)
                            <div style="padding: 5px 0;">
                                <span style="color: #718096;">Processing:</span>
                                <strong>{{ $order->processing_at->format('d M Y, H:i') }}</strong>
                            </div>
                            @endif
                            @if($order->completed_at)
                            <div style="padding: 5px 0; color: #27ae60;">
                                <span style="color: #718096;">Completed:</span>
                                <strong>{{ $order->completed_at->format('d M Y, H:i') }}</strong>
                            </div>
                            @endif
                            @if($order->cancelled_at)
                            <div style="padding: 5px 0; color: #e74c3c;">
                                <span style="color: #718096;">Cancelled:</span>
                                <strong>{{ $order->cancelled_at->format('d M Y, H:i') }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
