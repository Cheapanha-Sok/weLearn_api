<?php

namespace App\Policies;

use App\Models\User;

class QuestionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function remove(User $user){
        
        return $user && $user->is_admin === 1;
    }
}
