<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    public function update(User $user, Vendor $vendor): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isEnterpriseOwner() && (int) $vendor->user_id === (int) $user->id;
    }

    public function delete(User $user, Vendor $vendor): bool
    {
        return $this->update($user, $vendor);
    }
}
