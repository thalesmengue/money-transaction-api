<?php

namespace App\Actions\Transaction;

use App\Clients\AuthorizationClient;
use App\Clients\NotificationClient;
use App\DataTransferObjects\Transaction\TransactionData;
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
        private readonly NotificationClient $notificationClient,
        private readonly AuthorizationClient $authorizationClient,
        private readonly WalletRepository $walletRepository,
        private readonly TransactionRepository $transactionRepository
    )
    {
    }

    public function execute(TransactionData $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            /** @var User $payer */
            $payer = $this->walletRepository->findByOwnerId($data->payerId);

            /** @var User $receiver */
            $receiver = $this->walletRepository->findByOwnerId($data->receiverId);
            $amount = $data->amount;

            if ($payer->id === $receiver->id) {
                throw TransactionException::cantSendTransactionToYourself();
            }

            if ($payer->user->role === 'shopkeeper') {
                throw UserException::cantSendTransaction();
            }

            if ($payer->balance < $amount) {
                throw WalletException::insufficientBalance();
            }

            if ($this->authorizationClient->authorize() != "Autorizado") {
                throw TransactionException::transactionUnauthorized();
            }

            $payer->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            $transaction = $this->transactionRepository->create($data);

            if ($this->notificationClient->notify() != "Success") {
                throw TransactionException::unavailabilityToSendEmail();
            }

            return $transaction;
        });
    }
}
