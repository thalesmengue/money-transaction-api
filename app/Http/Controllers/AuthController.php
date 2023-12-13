<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $data = $action->execute($request->validated());

        return response()->json([
            'data' => $data['data'],
            'access_token' => $data['token'],
        ], Response::HTTP_CREATED);
    }

    public function login(AuthLoginRequest $request, LoginAction $action): JsonResponse
    {
        $data = $action->execute($request->validated());

        if (!$data['authenticated']) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'data' => $data['data'],
            'access_token' => $data['token'],
        ], Response::HTTP_OK);
    }
}
