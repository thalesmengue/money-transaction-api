<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth
    )
    {
    }

    public function register(Request $request): JsonResponse
    {
        $data = $this->auth->register($request);

        return response()->json([
            'data' => $data['data'],
            'access_token' => $data['token'],
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $this->auth->login($request);

        return response()->json([
            'data' => $data['data'],
            'access_token' => $data['token'],
        ], Response::HTTP_OK);
    }
}
