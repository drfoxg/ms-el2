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

    public function create(User $user)
    {
        //dd($user);

        if (isset($user->is_admin) && $user->is_admin === "Admin") {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user)
    {
        if (isset($user->is_admin) && $user->is_admin === "Admin") {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user)
    {
        if (isset($user->is_admin) && $user->is_admin === "Admin") {
            return true;
        } else {
            return false;
        }
    }
}
