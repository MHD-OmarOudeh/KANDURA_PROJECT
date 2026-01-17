<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());

            return $this->success([
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ], 'User registered successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Registration failed', $e->getMessage(), 500);
        }
    }
    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->login($request->validated(), 'sanctum');

            return $this->success([
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ], 'Login successful');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationError($e->errors(), 'Invalid credentials');
        } catch (\Exception $e) {
            return $this->error('Login failed', $e->getMessage(), 500);
        }
    }
    public function logout(User $user)
    {
        try {
            $this->authService->logout($user, 'sanctum');
            return $this->success(null, 'Logged out successfully');
        } catch (\Exception $e) {
            return $this->error('Logout failed', $e->getMessage(), 500);
        }
    }
}
