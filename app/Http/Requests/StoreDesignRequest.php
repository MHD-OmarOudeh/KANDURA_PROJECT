<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create designs');
    }

    public function rules(): array
    {
        return [
            // Name (translatable)
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],

            // Description (translatable)
            'description' => ['required', 'array'],
            'description.en' => ['required', 'string'],
            'description.ar' => ['required', 'string'],

            // Price
            'price' => ['required', 'numeric', 'min:0'],

            // Images (at least one required)
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

            // Measurements (at least one size required)
            'measurements' => ['required', 'array', 'min:1'],
            'measurements.*' => ['required', 'integer', 'exists:measurements,id'],

            // Design Options (optional, can select multiple)
            'design_options' => ['nullable', 'array'],
            'design_options.*' => ['required', 'integer', 'exists:design_options,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Design name is required',
            'name.en.required' => 'Design name in English is required',
            'name.ar.required' => 'Design name in Arabic is required',
            'description.required' => 'Description is required',
            'description.en.required' => 'Description in English is required',
            'description.ar.required' => 'Description in Arabic is required',
            'price.required' => 'Price is required',
            'price.min' => 'Price must be at least 0',
            'images.required' => 'At least one image is required',
            'images.min' => 'At least one image is required',
            'measurements.required' => 'At least one size is required',
            'measurements.min' => 'At least one size is required',
        ];
    }
}
