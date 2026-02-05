<?php

namespace App\Observers;

use App\Models\Coupon;

class CouponObserver
{
    /**
     * Handle the Coupon "retrieved" event.
     * Auto-deactivate expired coupons when they are retrieved
     */
    public function retrieved(Coupon $coupon): void
    {
        // Check if coupon is expired and still active
        if ($coupon->is_active && $coupon->expires_at && $coupon->expires_at->lt(now())) {
            $coupon->is_active = false;
            $coupon->saveQuietly(); // Save without triggering events
        }
    }

    /**
     * Handle the Coupon "saving" event.
     * Prevent activation of expired coupons
     */
    public function saving(Coupon $coupon): void
    {
        // If trying to activate an expired coupon, keep it inactive
        if ($coupon->is_active && $coupon->expires_at && $coupon->expires_at->lt(now())) {
            $coupon->is_active = false;
        }
    }
}
