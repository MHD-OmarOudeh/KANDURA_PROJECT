<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_translations' => [
                'en' => $this->getTranslation('name', 'en'),
                'ar' => $this->getTranslation('name', 'ar'),
            ],
            'description' => $this->description,
            'description_translations' => [
                'en' => $this->getTranslation('description', 'en'),
                'ar' => $this->getTranslation('description', 'ar'),
            ],
            'price' => $this->price,

            // User info
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            // Images
            'images' => DesignImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new DesignImageResource($this->whenLoaded('primaryImage')),

            // Measurements (Sizes)
            'measurements' => MeasurementResource::collection($this->whenLoaded('measurements')),

            // Design Options
            'design_options' => DesignOptionResource::collection($this->whenLoaded('designOptions')),

            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
