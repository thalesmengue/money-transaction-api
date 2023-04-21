<?php

namespace App\Observers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TransactionObserver
{
    public function creating(Transaction $transaction)
    {
        $transaction->id = Str::uuid();
        $transaction->transaction_date = Carbon::now();
    }
}
