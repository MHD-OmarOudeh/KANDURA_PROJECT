<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function show()
    {
        try {
            $user = auth()->user();

            return $this->success(
                new UserResource($user),
                'User data retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve user data', $e->getMessage(), 500);
        }
    }
    public function update(UpdateUserRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();

            // Handle profile image upload if exists
            if ($request->hasFile('profile_image')) {
                $path = $request->file('profile_image')->store('profiles', 'public');
                $data['profile_image'] = $path;
            }

            $updatedUser = $this->authService->updateProfile($user, $data);

            return $this->success(
                new UserResource($updatedUser),
                'User updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update user', $e->getMessage(), 500);
        }
    }
}
