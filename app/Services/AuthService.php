<?php

namespace App\Services;

use App\Events\Registered;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function register($data): array
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

    public function login($data): array|JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = request()->user();

        $token = $user->createToken('access_token')->accessToken;

        return [
            'data' => $user,
            'token' => $token
        ];
    }
}
