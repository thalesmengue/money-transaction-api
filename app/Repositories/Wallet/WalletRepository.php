<?php

namespace App\Repositories\Wallet;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

class WalletRepository implements WalletRepositoryInterface
{
    public function findByOwnerId(string $id): Wallet|Model
    {
        return Wallet::query()
            ->where('owner_id', '=', $id)
            ->first();
    }
}
