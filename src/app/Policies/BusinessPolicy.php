<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isBusinessUser();
    }

    public function view(User $user, Business $business): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return $user->isBusinessUser() && (int) $business->owner_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isBusinessUser();
    }

    public function update(User $user, Business $business): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return $user->isBusinessUser() && (int) $business->owner_id === (int) $user->id;
    }

    public function delete(User $user, Business $business): bool
    {
        return $user->isAdmin();
    }
}
