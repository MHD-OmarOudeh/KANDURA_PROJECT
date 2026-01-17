<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'size' => $this->size,
            'quantity' => $this->whenPivotLoaded('design_measurement', function () {
                return $this->pivot->quantity;
            }),
        ];
    }
}
