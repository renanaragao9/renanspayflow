<?php

namespace App\Services\Auth;

use App\Models\User;

class LogoutService
{
    public function run(User $user): void
    {
        $user->tokens()->delete();
    }
}
