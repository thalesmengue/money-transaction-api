<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction|Model
    {
        return Transaction::query()
            ->create($data);
    }
}
