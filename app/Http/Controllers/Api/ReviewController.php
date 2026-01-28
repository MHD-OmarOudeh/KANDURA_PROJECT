<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Add review to completed order
     * POST /api/orders/{order}/review
     */
    public function store(Request $request, Order $order)
    {
        try {
            // Check if order belongs to user
            if ($order->user_id !== $request->user()->id) {
                return $this->forbidden('You cannot review this order');
            }

            // Check if order is completed
            if ($order->status !== 'completed') {
                return $this->error('You can only review completed orders', null, 400);
            }

            // Check if already reviewed
            if ($order->review) {
                return $this->error('You have already reviewed this order', null, 400);
            }

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            $review = Review::create([
                'user_id' => $request->user()->id,
                'order_id' => $order->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            return $this->success([
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toIso8601String(),
                ]
            ], 'Review added successfully', 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to add review', $e->getMessage(), 500);
        }
    }

    /**
     * Get user's reviews
     * GET /api/reviews
     */
    public function index(Request $request)
    {
        try {
            $reviews = Review::with(['order:id,order_number,total,created_at'])
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(10);

            return $this->success([
                'reviews' => $reviews->items(),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'total' => $reviews->total(),
                    'per_page' => $reviews->perPage(),
                    'last_page' => $reviews->lastPage(),
                ]
            ], 'Reviews retrieved successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve reviews', $e->getMessage(), 500);
        }
    }

    /**
     * Get review for specific order
     * GET /api/orders/{order}/review
     */
    public function show(Order $order)
    {
        try {
            $review = $order->review;

            if (!$review) {
                return $this->error('No review found for this order', null, 404);
            }

            return $this->success([
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'user' => [
                        'id' => $review->user->id,
                        'name' => $review->user->name,
                    ],
                    'created_at' => $review->created_at->toIso8601String(),
                ]
            ], 'Review retrieved successfully');

        } catch (\Exception $e) {
            return $this->error('Failed to retrieve review', $e->getMessage(), 500);
        }
    }
}
