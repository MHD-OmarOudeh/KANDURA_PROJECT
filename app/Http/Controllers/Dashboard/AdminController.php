<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index()
    {
        $admins = User::role(['admin', 'super_admin', 'limited_admin'])
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        // Only allow creating admin and limited_admin roles
        // Super admin should be unique
        $roles = Role::whereIn('name', ['admin', 'limited_admin'])->get();
        $allPermissions = \Spatie\Permission\Models\Permission::all();
        return view('dashboard.admins.create', compact('roles', 'allPermissions'));
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            $admin = User::create($data);

            // Assign role to admin
            if ($request->role) {
                $admin->assignRole($request->role);
            }

            // ALWAYS give access dashboard permission to all admins
            $admin->givePermissionTo('access dashboard');

            
            if ($request->permissions) {
                foreach ($request->permissions as $permission) {
                    if (!$admin->hasPermissionTo($permission)) {
                        $admin->givePermissionTo($permission);
                    }
                }
            }

            return redirect()->route('dashboard.admins.index')
                ->with('success', 'Admin added successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while adding the admin'])
                ->withInput();
        }
    }

    /**
     * Display the specified admin.
     */
    public function show(User $admin)
    {
        $admin->load('roles', 'permissions');
        return view('dashboard.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(User $admin)
    {
        // Ensure the user is an admin
        if (!$admin->hasAnyRole(['admin', 'super_admin', 'limited_admin'])) {
            return redirect()->route('dashboard.admins.index')
                ->withErrors(['error' => 'User is not an admin']);
        }

        $admin->load('roles', 'permissions');
        // Only allow editing to admin and limited_admin roles
        // Prevent changing super_admin role
        $roles = Role::whereIn('name', ['admin', 'limited_admin'])->get();
        $allPermissions = \Spatie\Permission\Models\Permission::all();

        return view('dashboard.admins.edit', compact('admin', 'roles', 'allPermissions'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(UpdateAdminRequest $request, User $admin)
    {
        try {
            $data = $request->validated();

            // Only update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            $admin->update($data);

            // Update role
            if ($request->role) {
                $admin->syncRoles([$request->role]);
            }

            // Update permissions - ALWAYS include access dashboard
            $permissions = $request->has('permissions') ? $request->permissions : [];

            // Ensure access dashboard is always included
            if (!in_array('access dashboard', $permissions)) {
                $permissions[] = 'access dashboard';
            }

            $admin->syncPermissions($permissions);

            return redirect()->route('dashboard.admins.index')
                ->with('success', 'Admin updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating the admin: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(User $admin)
    {
        try {
            // Prevent super admin from deleting themselves
            if ($admin->id === auth()->id()) {
                return back()->withErrors(['error' => 'You cannot delete your own account']);
            }

            // Prevent deletion of super admin by regular admin
            if ($admin->hasRole('super_admin') && !auth()->user()->hasRole('super_admin')) {
                return back()->withErrors(['error' => 'You cannot delete Super Admin']);
            }

            $admin->delete();

            return redirect()->route('dashboard.admins.index')
                ->with('success', 'Admin deleted successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while deleting the admin']);
        }
    }

    /**
     * Toggle admin active status.
     */
    public function toggleStatus(User $admin)
    {
        try {
            // Prevent super admin from deactivating themselves
            if ($admin->id === auth()->id()) {
                return back()->withErrors(['error' => 'You cannot deactivate your own account']);
            }

            $admin->is_active = !$admin->is_active;
            $admin->save();

            $status = $admin->is_active ? 'activated' : 'deactivated';

            return redirect()->route('dashboard.admins.index')
                ->with('success', "Admin {$status} successfully");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while changing admin status']);
        }
    }
}
