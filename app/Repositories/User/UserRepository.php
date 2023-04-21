<?php

namespace App\Repositories\User;

use App\Models\User;
use \Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function all(): User|Collection
    {
        return User::all();
    }

    public function find($id): User|null
    {
        return User::find($id);
    }

    public function destroy($id): int
    {
        return User::destroy($id);
    }
}
