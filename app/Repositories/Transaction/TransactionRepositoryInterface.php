<?php

namespace App\Repositories\Transaction;

use App\DataTransferObjects\Transaction\TransactionData;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

interface TransactionRepositoryInterface
{
    public function create(TransactionData $data): Transaction|Model;
}
