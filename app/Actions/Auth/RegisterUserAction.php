<?php

namespace App\Actions\Auth;

use App\Events\Registered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $data): array
    {
        $role = match (strlen($data['document'])) {
            11 => 'common',
            14 => 'shopkeeper'
        };

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'document' => $data['document'],
            'role' => $role,
        ]);

        event(new Registered($user));

        $token = $user->createToken('access_token')->accessToken;

        return [
            'data' => $user,
            'token' => $token
        ];
    }
}
