<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginAction
{
    public function execute(array $data): array
    {
        $authenticated = true;
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            $authenticated = false;
        }

        $user = request()->user();

        $token = $user->createToken('access_token')->accessToken;

        return [
            'data' => $user,
            'token' => $token,
            'authenticated' => $authenticated
        ];
    }
}
