<?php

namespace App\Policies;

use App\Models\Design;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DesignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view designs') ||
               $user->hasPermissionTo('manage all designs');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Design $design): bool
    {
       return $user->id === $design->user_id ||
               $user->hasPermissionTo('manage all designs');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create designs');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Design $design): bool
    {
        return ($user->id === $design->user_id && $user->hasPermissionTo('edit designs')) ||
               $user->hasPermissionTo('manage all designs');


    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Design $design): bool
    {
        return ($user->id === $design->user_id && $user->hasPermissionTo('delete designs')) ||
               $user->hasPermissionTo('manage all designs');


    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Design $design): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Design $design): bool
    {
        return false;
    }
}
