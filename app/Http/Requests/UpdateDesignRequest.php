<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        $design = $this->route('design');
        return $this->user()->can('update', $design);
    }

    public function rules(): array
    {
        return [
            // Name (optional in update)
            'name' => ['sometimes', 'array'],
            'name.en' => ['sometimes', 'required_with:name', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'required_with:name', 'string', 'max:255'],

            // Description (optional)
            'description' => ['sometimes', 'array'],
            'description.en' => ['sometimes', 'required_with:description', 'string'],
            'description.ar' => ['sometimes', 'required_with:description', 'string'],

            // Price
            'price' => ['sometimes', 'numeric', 'min:0'],

            // Images (optional in update)
            'images' => ['sometimes', 'array'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

            // Measurements
            'measurements' => ['sometimes', 'array'],
            'measurements.*' => ['required', 'integer', 'exists:measurements,id'],

            // Design Options
            'design_options' => ['sometimes', 'array'],
            'design_options.*' => ['required', 'integer', 'exists:design_options,id'],
        ];
    }
}
