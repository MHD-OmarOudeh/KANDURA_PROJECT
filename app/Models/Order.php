<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\OrderCompleted;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'total',
        'payment_method',
        'stripe_payment_intent_id',
        'payment_status',
        'paid_at',
        'status',
        'coupon_id',
        'notes',
        'confirmed_at',
        'processing_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'processing_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        $lastOrder = self::latest('id')->first();
        $number = $lastOrder ? intval(substr($lastOrder->order_number, 4)) + 1 : 1;

        return 'ORD-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Boot method - Auto trigger invoice generation
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            // Check if status was changed to 'completed'
            if ($order->isDirty('status') && $order->status === 'completed') {
                \Log::info('Order completed, dispatching event', ['order_id' => $order->id]);
                OrderCompleted::dispatch($order);
            }
        });
    }
}
