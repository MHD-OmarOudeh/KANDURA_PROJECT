<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm(): View
    {
        return view('dashboard.auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $result = $this->authService->login($credentials, 'web');

            $user = $result['user'];

            // Check if user has dashboard access
            if (!$user->hasPermissionTo('access dashboard')) {
                $this->authService->logout($user, 'web');
                return back()->withErrors([
                    'email' => 'You do not have permission to access the dashboard.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('index'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->only('email'));
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'An error occurred during login.',
            ])->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user(), 'web');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
