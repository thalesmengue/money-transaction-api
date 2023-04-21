<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function all(): User|Collection;

    public function find($id): User|null;

    public function destroy($id): int;
}
