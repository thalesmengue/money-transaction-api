<?php

namespace App\Actions\Transaction;

use App\Clients\AuthorizationClient;
use App\Clients\NotificationClient;
use App\Exceptions\TransactionException;
use App\Exceptions\UserException;
use App\Exceptions\WalletException;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Wallet\WalletRepository;
use Illuminate\Support\Facades\DB;

class MakeTransactionAction
{
    public function __construct(
        private readonly NotificationClient $notification,
        private readonly AuthorizationClient $authorization,
        private readonly WalletRepository $walletRepository,
        private readonly TransactionRepository $transactionRepository
    )
    {
    }

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            /** @var User $payer */
            $payer = $this->walletRepository->findById($data['payer_id']);

            /** @var User $receiver */
            $receiver = $this->walletRepository->findById($data['receiver_id']);
            $amount = $data['amount'];

            if ($payer->id === $receiver->id) {
                throw TransactionException::cantSendTransactionToYourself();
            }

            if ($payer->user->role === 'shopkeeper') {
                throw UserException::cantSendTransaction();
            }

            if ($payer->balance < $amount) {
                throw WalletException::insufficientBalance();
            }

            if ($this->authorization->authorize()->json('message') != "Autorizado") {
                throw TransactionException::transactionUnauthorized();
            }

            $payer->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            $transaction = $this->transactionRepository->create([
                'payer_id' => $payer->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
            ]);

            if ($this->notification->notify()->json('message') != "Success") {
                throw TransactionException::unavailabilityToSendEmail();
            }

            return $transaction;
        });
    }
}
