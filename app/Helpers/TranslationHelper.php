<?php

if (!function_exists('trans_choice_msg')) {
    /**
     * Translate a message with plural support
     */
    function trans_choice_msg(string $key, int $count = 1, array $replace = [], ?string $locale = null): string
    {
        return trans_choice("messages.{$key}", $count, $replace, $locale);
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get current application locale
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL
     */
    function is_rtl(): bool
    {
        return in_array(current_locale(), ['ar', 'he', 'fa', 'ur']);
    }
}

if (!function_exists('get_direction')) {
    /**
     * Get text direction for current locale
     */
    function get_direction(): string
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

if (!function_exists('translate_model')) {
    /**
     * Get translated attribute from model
     */
    function translate_model($model, string $attribute, ?string $locale = null)
    {
        if (!$model) {
            return null;
        }

        $locale = $locale ?? current_locale();

        if (method_exists($model, 'getTranslation')) {
            return $model->getTranslation($attribute, $locale);
        }

        return $model->$attribute ?? null;
    }
}

if (!function_exists('available_locales')) {
    /**
     * Get available locales
     */
    function available_locales(): array
    {
        return config('app.available_locales', ['en', 'ar']);
    }
}

if (!function_exists('locale_name')) {
    /**
     * Get locale display name
     */
    function locale_name(?string $locale = null): string
    {
        $locale = $locale ?? current_locale();

        $names = [
            'en' => 'English',
            'ar' => 'العربية',
        ];

        return $names[$locale] ?? $locale;
    }
}

if (!function_exists('trans_api_response')) {
    /**
     * Create translated API response
     */
    function trans_api_response(string $messageKey, array $data = [], int $status = 200, array $replace = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $status >= 200 && $status < 300,
            'message' => __("messages.{$messageKey}", $replace),
            'data' => $data,
        ], $status);
    }
}

if (!function_exists('trans_validation_error')) {
    /**
     * Create translated validation error response
     */
    function trans_validation_error(\Illuminate\Contracts\Validation\Validator $validator): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => __('validation.failed'),
            'errors' => $validator->errors(),
        ], 422);
    }
}
