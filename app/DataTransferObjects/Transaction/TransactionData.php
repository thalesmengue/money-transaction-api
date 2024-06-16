<?php

namespace App\DataTransferObjects\Transaction;

use App\DataTransferObjects\DataTransferObject;

class TransactionData extends DataTransferObject
{
    public function __construct(
        public string $payerId,
        public string $receiverId,
        public int $amount
    )
    {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            payerId: $data['payer_id'],
            receiverId: $data['receiver_id'],
            amount: $data['amount']
        );
    }

    public function toArray(): array
    {
        return [
            'payer_id' => $this->payerId,
            'receiver_id' => $this->receiverId,
            'amount' => $this->amount
        ];
    }
}
