<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction|Model;
}
