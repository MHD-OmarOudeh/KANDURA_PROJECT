<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    /**
     * Get available languages
     */
    public function index(): JsonResponse
    {
        $availableLocales = config('app.available_locales', ['en', 'ar']);
        $currentLocale = app()->getLocale();

        return response()->json([
            'success' => true,
            'data' => [
                'current' => $currentLocale,
                'available' => $availableLocales,
                'languages' => [
                    'en' => [
                        'code' => 'en',
                        'name' => 'English',
                        'native_name' => 'English',
                        'direction' => 'ltr',
                    ],
                    'ar' => [
                        'code' => 'ar',
                        'name' => 'Arabic',
                        'native_name' => 'العربية',
                        'direction' => 'rtl',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Get current language
     */
    public function current(): JsonResponse
    {
        $locale = app()->getLocale();

        $languages = [
            'en' => [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'direction' => 'ltr',
            ],
            'ar' => [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'direction' => 'rtl',
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'locale' => $locale,
                'language' => $languages[$locale] ?? $languages['en'],
            ],
        ]);
    }

    /**
     * Switch language (stores in user preference if authenticated)
     */
    public function switch(Request $request): JsonResponse
    {
        $request->validate([
            'locale' => 'required|string|in:en,ar',
        ]);

        $locale = $request->input('locale');

        // Set application locale
        app()->setLocale($locale);

        // Store in session for web requests
        session(['locale' => $locale]);

        // If user is authenticated, update their preference
        if ($request->user()) {
            $request->user()->update([
                'preferred_locale' => $locale,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.success'),
            'data' => [
                'locale' => $locale,
                'message' => $locale === 'ar'
                    ? 'تم تغيير اللغة بنجاح'
                    : 'Language changed successfully',
            ],
        ]);
    }
}
