<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'design_id' => $this->design_id,
            'design' => $this->when($this->relationLoaded('design'), function () {
                return [
                    'id' => $this->design->id,
                    'name' => $this->design->name ?? null,
                ];
            }),
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'total_price' => (float) $this->total_price,
            'design_snapshot' => $this->design_snapshot,
        ];
    }
}
