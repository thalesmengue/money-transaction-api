<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function all(): User|Collection;

    public function find(string $id): User|null;

    public function destroy(string $id): bool;

    public function create(array $data): User;
}
