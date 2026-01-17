<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage design options');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:color,dome_type,fabric_type,sleeve_type'],
            'color_code' => ['nullable', 'required_if:type,color', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.en.required' => 'Option name in English is required',
            'name.ar.required' => 'Option name in Arabic is required',
            'type.required' => 'Option type is required',
            'type.in' => 'Invalid option type',
            'color_code.required_if' => 'Color code is required for color type',
            'color_code.regex' => 'Color code must be in hex format (e.g., #FF0000)',
        ];
    }
}
