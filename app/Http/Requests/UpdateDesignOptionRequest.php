<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage design options');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'array'],
            'name.en' => ['sometimes', 'required_with:name', 'string', 'max:255'],
            'name.ar' => ['sometimes', 'required_with:name', 'string', 'max:255'],
            'type' => ['sometimes', 'in:color,dome_type,fabric_type,sleeve_type'],
            'color_code' => ['nullable', 'required_if:type,color', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
