<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        // إنشاء المستخدم
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        // ✅ إضافة الـ Role بشكل صحيح (بعد إنشاء المستخدم مباشرة)
        $user->assignRole('user');

        // إنشاء Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->fresh(), // ← جيب البيانات الجديدة
            'token' => $token,
        ];
    }

    public function login(array $credentials, string $guard = 'web'): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated.'],
            ]);
        }

        if ($guard === 'sanctum') {
            // امسح الـ tokens القديمة
            $user->tokens()->delete();

            // أنشئ token جديد
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        } else {
            Auth::login($user, $credentials['remember'] ?? false);

            return [
                'user' => $user,
            ];
        }
    }

    public function logout(User $user, string $guard = 'web'): void
    {
        if ($guard === 'sanctum') {
            $user->tokens()->delete();
        } else {
            Auth::logout();
        }
    }

    public function updateProfile(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'] ?? $user->name,
            'phone' => $data['phone'] ?? $user->phone,
        ];

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $updateData['email'] = $data['email'];
            $updateData['email_verified_at'] = null;
        }

        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (isset($data['profile_image'])) {
            $updateData['profile_image'] = $data['profile_image'];
        }

        $user->update($updateData);

        return $user->fresh();
    }
}
