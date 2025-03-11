<?php

namespace App\Actions\Credit_transaction;

use App\Models\CreditTransaction;

class StoreCreditTransactionAction
{
    public function __invoke(array $data)
    {
        return CreditTransaction::create([
            'wallet_id' => $data['wallet_id'],
            'amount' => $data['amount'],
        ]);
    }
}
