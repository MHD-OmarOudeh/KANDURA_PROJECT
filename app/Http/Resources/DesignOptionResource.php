<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignOptionResource extends JsonResource
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
            'type' => $this->type,
            'type_name' => $this->type_name,
            'color_code' => $this->when($this->type === 'color', $this->color_code),
            'image' => $this->when($this->image, asset('storage/' . $this->image)),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
