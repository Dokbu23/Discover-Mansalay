<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

class ProductPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEnterpriseOwner();
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->isEnterpriseOwner()) {
            return false;
        }

        if (is_null($product->uploaded_by_user_id)) {
            return Vendor::where('id', $product->vendor_id)
                ->where('user_id', $user->id)
                ->exists();
        }

        return (int) $product->uploaded_by_user_id === (int) $user->id;
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->update($user, $product);
    }
}