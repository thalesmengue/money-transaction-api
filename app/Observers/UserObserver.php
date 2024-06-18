<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user): void
    {
        $user->wallet()->create([
            'balance' => 0
        ]);
    }
}
