<?php

namespace Modules\User\Policies;

use Modules\User\Models\User;

class UserPolicy
{
    public function update(User $user): bool
    {
        return $user->id === auth()->id();
    }
}
