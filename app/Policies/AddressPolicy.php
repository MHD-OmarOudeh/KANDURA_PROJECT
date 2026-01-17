<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;

class AddressPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view addresses');
    }

    public function view(User $user, Address $address): bool
    {
        return $user->id === $address->user_id ||
               $user->hasPermissionTo('manage all addresses');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create addresses');
    }

    public function update(User $user, Address $address): bool
    {
        return ($user->id === $address->user_id && $user->hasPermissionTo('edit addresses')) ||
               $user->hasPermissionTo('manage all addresses');
    }

    public function delete(User $user, Address $address): bool
    {
        return ($user->id === $address->user_id && $user->hasPermissionTo('delete addresses')) ||
               $user->hasPermissionTo('manage all addresses');
    }

    public function viewAllAddresses(User $user): bool
    {
        return $user->hasPermissionTo('manage all addresses');
    }
}
