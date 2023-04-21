<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $user
    )
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->user->all()
        ], Response::HTTP_OK);
    }

    public function show($id): JsonResponse
    {
        return response()->json([
            'data' => $this->user->find($id)
        ], Response::HTTP_FOUND);
    }

    public function destroy($id): JsonResponse
    {
        return response()->json([
            'data' => $this->user->destroy($id)
        ], Response::HTTP_NO_CONTENT);
    }
}
