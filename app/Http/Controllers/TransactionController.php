<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Exceptions\UserException;
use App\Exceptions\WalletException;
use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService
    )
    {
    }

    public function transaction(TransactionRequest $request): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->transactionService->transaction($request->validated())
            ], Response::HTTP_CREATED);
        } catch (UserException|TransactionException|WalletException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
