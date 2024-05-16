<?php

namespace App\Policies;

use App\Models\User;

class TypePolicy
{
    public function createType(User $user): bool
    {
        return $user->isAdmin;
    }
}
