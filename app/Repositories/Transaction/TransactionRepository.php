<?php

namespace App\Repositories\Transaction;

use App\DataTransferObjects\Transaction\TransactionData;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(TransactionData $data): Transaction|Model
    {
        return Transaction::query()
            ->create($data->toArray());
    }
}
