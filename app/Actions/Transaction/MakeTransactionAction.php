<?php

namespace App\Actions\Transaction;

use App\Clients\AuthorizationClient;
use App\Clients\NotificationClient;
use App\Exceptions\TransactionException;
use App\Exceptions\UserException;
use App\Exceptions\WalletException;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class MakeTransactionAction
{
    public function __construct(
        private readonly NotificationClient $notification,
        private readonly AuthorizationClient $authorization
    )
    {
    }

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            $payer = Wallet::query()->with('user')->where('owner_id', '=', $data['payer_id'])->first();
            $receiver = Wallet::query()->where('owner_id', '=', $data['receiver_id'])->first();
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

            if ($this->authorization->authorize()['message'] != "Authorized") {
                throw TransactionException::transactionUnauthorized();
            }

            $payer->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            $transaction = Transaction::create([
                'payer_id' => $payer->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
            ]);

            if ($this->notification->notify()['message'] != "Success") {
                throw TransactionException::unavailabilityToSendEmail();
            }

            return $transaction;
        });
    }
}
