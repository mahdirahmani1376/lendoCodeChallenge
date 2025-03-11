<?php

namespace App\Actions\Wallet;

use App\Actions\Credit_transaction\StoreCreditTransactionAction;
use App\Models\Wallet;

class DepositWalletAction
{
    public function __construct(
        private ShowWalletAction $showWalletAction,
        private StoreCreditTransactionAction $storeCreditTransactionAction,
    ) {}

    public function __invoke(array $data): Wallet
    {
        $wallet = ($this->showWalletAction)($data['user_id']);

        $creditTransactions = ($this->storeCreditTransactionAction)([
            'wallet_id' => $wallet->id,
            'amount' => $data['amount'],
        ]);

        $wallet = ($this->showWalletAction)($data['user_id'], true);

        return $wallet;

    }
}
