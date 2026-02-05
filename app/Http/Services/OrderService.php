<?php

namespace App\Http\Services;

use App\Models\Order;
use App\Models\Design;
use App\Models\Coupon;
use App\Models\User;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Services\PaymentService;
use Stripe\Refund;
use Stripe\Stripe;


class OrderService
{

protected PaymentService $paymentService;

public function __construct(PaymentService $paymentService)
{
    $this->paymentService = $paymentService;

    // Initialize Stripe API
    Stripe::setApiKey(config('services.stripe.secret'));
}

/**
 * Create new order with payment processing
 */
    public function createOrder(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            // Validate items exist
            $this->validateOrderItems($data['items']);

            // Calculate totals (with coupon if provided)
            $calculations = $this->calculateOrderTotals($user, $data['items'], $data['coupon_code'] ?? null);

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'address_id' => $data['address_id'],
                'subtotal' => $calculations['subtotal'],
                'tax' => $calculations['tax'],
                'shipping_cost' => $calculations['shipping_cost'],
                'discount' => $calculations['discount'],
                'total' => $calculations['total'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'payment_status' => 'pending',
                'status' => 'pending',
                'coupon_id' => $calculations['coupon_id'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Attach items
            $this->attachOrderItems($order, $data['items']);

            // Mark coupon as used
            if ($calculations['coupon_id']) {
                $coupon = Coupon::find($calculations['coupon_id']);
                $coupon->markAsUsed($user, $order);
            }

            // Process payment if wallet
            if ($order->payment_method === 'wallet') {
                try {
                    $this->paymentService->processPayment($order);
                } catch (\Exception $e) {
                    throw new \Exception('Wallet payment failed: ' . $e->getMessage());
                }
            }

            // Fire event
            event(new OrderCreated($order));

            return $order->fresh()->load(['orderItems.design.images', 'address.city', 'coupon']);
        });
    }
    /**
     * Get user orders with filters and pagination
     */
    public function getUserOrders(User $user, array $filters = [])
    {
        $query = Order::with(['address.city', 'orderItems.design.images', 'coupon'])
            ->forUser($user->id);

        // Apply filters
        $query = $this->applyFilters($query, $filters);

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get all orders (Admin)
     */
    public function getAllOrders(array $filters = [])
    {
        $query = Order::with(['user', 'address.city', 'orderItems.design', 'coupon']);

        // Apply filters
        $query = $this->applyFilters($query, $filters);

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($query, array $filters)
    {
        // Search
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        // Payment method filter
        if (!empty($filters['payment_method'])) {
            $query->byPaymentMethod($filters['payment_method']);
        }

        // Date range
        if (!empty($filters['start_date']) || !empty($filters['end_date'])) {
            $query->betweenDates(
                $filters['start_date'] ?? null,
                $filters['end_date'] ?? null
            );
        }

        // User filter (for admin)
        if (!empty($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        return $query;
    }

    /**
     * Create new order
     */


    /**
     * Validate order items
     */
    protected function validateOrderItems(array $items)
    {
        foreach ($items as $item) {
            $design = Design::find($item['design_id']);

            if (!$design) {
                throw new \Exception("Design with ID {$item['design_id']} not found");
            }

            if ($item['quantity'] < 1) {
                throw new \Exception("Quantity must be at least 1");
            }
        }
    }

    /**
     * Calculate order totals
     */
    protected function calculateOrderTotals(User $user, array $items, ?string $couponCode = null)
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $design = Design::findOrFail($item['design_id']);
            $subtotal += $design->price * $item['quantity'];
        }

        // Tax (15% - Saudi VAT)
        $tax = $subtotal * 0.15;

        // Shipping cost
        $shippingCost = $this->calculateShippingCost($subtotal);

        // Discount from coupon
        $discount = 0;
        $couponId = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', strtoupper($couponCode))
                ->where('is_active', true)
                ->first();

            if ($coupon) {
                // Validate coupon
                $validation = $coupon->validate($user, $subtotal);
                if ($validation['valid']) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                } else {
                    throw new \Exception($validation['message']);
                }
            } else {
                throw new \Exception('Invalid or inactive coupon code');
            }
        }

        // Total = Subtotal + Tax + Shipping - Discount
        $total = $subtotal + $tax + $shippingCost - $discount;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'shipping_cost' => round($shippingCost, 2),
            'discount' => round($discount, 2),
            'total' => round($total, 2),
            'coupon_id' => $couponId,
        ];
    }

    /**
     * Calculate shipping cost
     */
    protected function calculateShippingCost(float $subtotal): float
    {
        // Free shipping for orders over 500 SAR
        if ($subtotal >= 500) {
            return 0;
        }

        return 50; // Fixed shipping cost
    }

    /**
     * Attach items to order
     */
    protected function attachOrderItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $design = Design::with(['measurements', 'designOptions', 'images'])
                ->findOrFail($item['design_id']);

            $unitPrice = $design->price;
            $totalPrice = $unitPrice * $item['quantity'];

            // Create snapshot of design
            $snapshot = [
                'name' => $design->name,
                'price' => $design->price,
                'fabric_type' => $design->fabric_type,
                'color' => $design->color,
                'embroidery' => $design->embroidery,
                'measurements' => $design->measurements->toArray(),
                'design_options' => $design->designOptions->toArray(),
                'images' => $design->images->pluck('image_path')->toArray(),
            ];

            $order->orderItems()->create([
                'design_id' => $design->id,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'design_snapshot' => $snapshot,
            ]);
        }
    }

    /**
     * Update order status with validation
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        $oldStatus = $order->status;

        // Validate status transition
        if (!$this->isValidStatusTransition($oldStatus, $status)) {
            throw new \Exception($this->getInvalidTransitionMessage($oldStatus, $status));
        }

        $order->status = $status;

        // Set timestamp based on status
        switch ($status) {
            case 'confirmed':
                $order->confirmed_at = now();
                break;
            case 'processing':
                $order->processing_at = now();
                break;
            case 'completed':
                $order->completed_at = now();

                // Auto-mark cash payments as paid when order is completed
                if ($order->payment_method === 'cash' && $order->payment_status === 'pending') {
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                }
                break;
            case 'cancelled':
                $order->cancelled_at = now();

                // Process refund based on payment method (if paid)
                if ($order->payment_status === 'paid') {
                    if ($order->payment_method === 'wallet') {
                        // Wallet refund
                        $wallet = $order->user->wallet;

                        if ($wallet) {
                            $wallet->refund(
                                (float) $order->total,
                                $order->id,
                                "Refund for cancelled order {$order->order_number}"
                            );

                            $order->payment_status = 'refunded';

                            Log::info('💰 OrderService: Wallet refund processed', [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                                'amount' => $order->total,
                                'user_id' => $order->user_id,
                            ]);
                        }
                    } elseif ($order->payment_method === 'card' && $order->stripe_payment_intent_id) {
                        // Stripe refund
                        try {
                            $refund = Refund::create([
                                'payment_intent' => $order->stripe_payment_intent_id,
                                'reason' => 'requested_by_customer',
                            ]);

                            $order->payment_status = 'refunded';

                            Log::info('💳 OrderService: Stripe refund processed', [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                                'amount' => $order->total,
                                'refund_id' => $refund->id,
                                'payment_intent_id' => $order->stripe_payment_intent_id,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('❌ OrderService: Stripe refund failed', [
                                'order_id' => $order->id,
                                'error' => $e->getMessage(),
                            ]);
                            throw new \Exception('Failed to process Stripe refund: ' . $e->getMessage());
                        }
                    }
                }
                break;
        }

        $order->save();

        // 🔔 Fire OrderStatusChanged event for notifications
        if ($oldStatus !== $status) {
            Log::info('🔔 OrderService: Firing OrderStatusChanged event', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $status
            ]);
            event(new OrderStatusChanged($order, $oldStatus, $status));
        }

        return $order;
    }

    /**
     * Validate if status transition is allowed
     */
    protected function isValidStatusTransition(string $from, string $to): bool
    {
        // Same status - no change needed but not an error
        if ($from === $to) {
            return true;
        }

        // Define allowed transitions
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'completed' => [], // Cannot change from completed
            'cancelled' => [], // Cannot change from cancelled
        ];

        return in_array($to, $allowedTransitions[$from] ?? []);
    }

    /**
     * Get error message for invalid transition
     */
    protected function getInvalidTransitionMessage(string $from, string $to): string
    {
        if ($from === 'completed') {
            return 'Cannot change status of completed order';
        }

        if ($from === 'cancelled') {
            return 'Cannot change status of cancelled order';
        }

        $validNextStatuses = match($from) {
            'pending' => 'confirmed or cancelled',
            'confirmed' => 'processing or cancelled',
            'processing' => 'completed or cancelled',
            default => 'unknown',
        };

        return "Cannot change order status from {$from} to {$to}. Valid next statuses: {$validNextStatuses}";
    }

    /**
     * Get status timestamp field name
     */
    protected function getStatusTimestampField(string $status): string
    {
        return match($status) {
            'confirmed' => 'confirmed_at',
            'processing' => 'processing_at',
            'completed' => 'completed_at',
            'cancelled' => 'cancelled_at',
            default => 'updated_at',
        };
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Order $order): Order
    {
        if (!$order->canBeCancelled()) {
            throw new \Exception('This order cannot be cancelled');
        }

        // updateOrderStatus will handle wallet refund automatically
        return $this->updateOrderStatus($order, 'cancelled');
    }

    /**
     * Get order details
     */
    public function getOrderDetails(Order $order): Order
    {
        return $order->load([
            'user',
            'address.city',
            'orderItems.design.images',
            'orderItems.design.measurements',
            'orderItems.design.designOptions',
            'coupon',
            'invoice',
        ]);
    }
}
