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

    public function find(string $id): User|null
    {
        return User::find($id);
    }

    public function destroy(string $id): bool
    {
        return User::destroy($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }
}
