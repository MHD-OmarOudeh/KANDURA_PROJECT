<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,

            // User info (only for admin)
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],

            // Address
            'address' => $this->when($this->relationLoaded('address'), function () {
                return [
                    'id' => $this->address->id,
                    'street' => $this->address->street,
                    'city' => $this->address->city,
                    'state' => $this->address->state,
                    'country' => $this->address->country,
                    'postal_code' => $this->address->postal_code,
                ];
            }),

            // Pricing
            'pricing' => [
                'subtotal' => (float) $this->subtotal,
                'tax' => (float) $this->tax,
                'shipping_cost' => (float) $this->shipping_cost,
                'discount' => (float) $this->discount,
                'total' => (float) $this->total,
            ],

            // Payment
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'notes' => $this->notes,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'confirmed_at' => $this->confirmed_at?->toIso8601String(),
            'processing_at' => $this->processing_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'cancelled_at' => $this->cancelled_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            // Invoice if exists
            'invoice' => $this->when($this->invoice, function () {
                return [
                    'invoice_number' => $this->invoice->invoice_number,
                    'total' => (float) $this->invoice->total,
                    'pdf_url' => $this->invoice->pdf_url ? url($this->invoice->pdf_url) : null,
                    'created_at' => $this->invoice->created_at->toIso8601String(),
                ];
            }),

            // Review if exists
            'review' => $this->when($this->relationLoaded('review') && $this->review, function () {
                return [
                    'rating' => $this->review->rating,
                    'comment' => $this->review->comment,
                ];
            }),
        ];
    }
}
