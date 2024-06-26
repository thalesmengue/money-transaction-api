<?php

namespace App\Http\Controllers;

use App\Actions\Transaction\MakeTransactionAction;
use App\DataTransferObjects\Transaction\TransactionData;
use App\Exceptions\TransactionException;
use App\Exceptions\UserException;
use App\Exceptions\WalletException;
use App\Http\Requests\TransactionRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function transaction(TransactionRequest $request, MakeTransactionAction $action): JsonResponse
    {
        try {
            return response()->json([
                'data' => $action->execute(
                    TransactionData::fromRequest($request->validated())
                )
            ], Response::HTTP_CREATED);
        } catch (UserException|TransactionException|WalletException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
