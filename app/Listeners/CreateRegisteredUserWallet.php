<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class CreateRegisteredUserWallet implements ShouldQueue
{
    public function handle(Registered $event): void
    {
        $event->user->wallet()->create([
            'id' => Str::uuid(),
            'balance' => rand(10, 1000),
            'keeper_id' => $event->user->id
        ]);
    }
}
