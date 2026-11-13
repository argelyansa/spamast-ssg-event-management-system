<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Admin')
            && $user->id !== $model->id;
    }
}
