<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $design = $this->route('design');
        return $this->user()->can('update', $design);
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'At least one image is required',
            'images.*.image' => 'Each file must be an image',
            'images.*.mimes' => 'Images must be jpeg, png, jpg, or webp',
            'images.*.max' => 'Each image must not exceed 2MB',
        ];
    }
}
