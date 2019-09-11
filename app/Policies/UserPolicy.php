<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends AbstractPolicy
{
    /**
     * @param User $authenticatedUser
     * @param User $userToShow
     * @return bool
     */
    public function show(User $authenticatedUser, User $userToShow): bool
    {
        return $userToShow->isEnabled();
    }
}
