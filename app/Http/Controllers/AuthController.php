<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterUserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request, RegisterUserAction $action): JsonResponse
    {
        $data = $action->execute($request->all());

        return response()->json([
            'data' => $data['data'],
            'access_token' => $data['token'],
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request, LoginAction $action): JsonResponse
    {
        $data = $action->execute($request->all());

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
