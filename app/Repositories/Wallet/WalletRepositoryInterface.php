<?php

namespace App\Repositories\Wallet;

interface WalletRepositoryInterface
{
    public function findByOwnerId(string $id);
}
