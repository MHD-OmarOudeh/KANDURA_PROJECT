<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StripeRedirectController extends Controller
{
    /**
     * Handle successful payment redirect from Stripe
     */
    public function showSuccess(Request $request)
    {
        $orderId = $request->query('order_id');
        $sessionId = $request->query('session_id');

        $order = Order::find($orderId);

        if (!$order) {
            return view('payment.error', [
                'message' => 'Order not found',
            ]);
        }

        // Check session status from Stripe
        if ($sessionId) {
            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                // If payment is complete, update order
                if ($session->payment_status === 'paid' && $order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                        'stripe_payment_intent_id' => $session->payment_intent,
                    ]);

                    $order->refresh();
                }
            } catch (\Exception $e) {
                // Continue even if session retrieval fails
            }
        }

        return view('payment.success', [
            'order' => $order,
            'sessionId' => $sessionId,
        ]);
    }

    /**
     * Handle cancelled payment redirect from Stripe
     */
    public function showCancel(Request $request)
    {
        $orderId = $request->query('order_id');

        $order = Order::find($orderId);

        if (!$order) {
            return view('payment.error', [
                'message' => 'Order not found',
            ]);
        }

        return view('payment.cancel', [
            'order' => $order,
        ]);
    }
}
