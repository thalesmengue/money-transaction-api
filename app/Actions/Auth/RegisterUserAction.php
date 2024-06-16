<?php

namespace App\Actions\Auth;

use App\Events\Registered;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    public function execute(array $data): void
    {
        $role = match (strlen($data['document'])) {
            11 => 'common',
            14 => 'shopkeeper'
        };

        $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'document' => $data['document'],
            'role' => $role,
        ]);
    }
}
