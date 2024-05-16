<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    /**
     * Determine if the given user can create posts.
     */
    public function createQuestion(User $user): bool
    {
        return $user->isAdmin;
    }
    public function updateQuestion(User $user): bool
    {
        return $user->isAdmin;
    }
}
