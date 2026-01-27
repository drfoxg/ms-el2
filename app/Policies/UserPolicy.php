<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->is_admin === "Admin";
    }

    public function update(User $user): bool
    {
        return $user->is_admin === "Admin";
    }

    public function delete(User $user): bool
    {
        return $user->is_admin === "Admin";
    }
}
