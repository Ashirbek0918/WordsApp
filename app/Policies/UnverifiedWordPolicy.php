<?php

namespace App\Policies;

use App\Models\Unverified;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UnverifiedWordPolicy
{
   
    public function control(User $user, Unverified $unverified): bool
    {
        return auth()->user()->id === $unverified->user_id;
    }

}
