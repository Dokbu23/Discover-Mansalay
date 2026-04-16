<?php

namespace App\Policies;

use App\Models\Resort;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResortPolicy
{
    use HandlesAuthorization;

    /**
     * Allow admins to perform any resort action.
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine if the user can update a resort.
     */
    public function update(User $user, Resort $resort): bool
    {
        return $user->id === $resort->owner_id;
    }

    /**
     * Determine if the user can delete a resort.
     */
    public function delete(User $user, Resort $resort): bool
    {
        return $user->id === $resort->owner_id;
    }
}
