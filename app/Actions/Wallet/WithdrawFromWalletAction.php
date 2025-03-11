<?php

namespace App\Actions\Wallet;

use App\Actions\Credit_transaction\StoreCreditTransactionAction;
use App\Exceptions\WalletException;

class WithdrawFromWalletAction
{
    public function __construct(
        private ShowWalletAction $showWalletAction,
        private StoreCreditTransactionAction $storeCreditTransactionAction,
    ) {}

    public function __invoke(array $data)
    {
        $wallet = ($this->showWalletAction)($data['user_id'], true);

        if ($wallet->balance < $data['amount']) {
            throw WalletException::causeOfWithDrawAmountBiggerThanWalletBalance();
        }

        // credit_transaction amount refund via bankgateway interface like: AsanPardakhtRefundService

        $creditTransactions = ($this->storeCreditTransactionAction)([
            'wallet_id' => $wallet->id,
            'amount' => $data['amount'] * -1,
        ]);

        $wallet = ($this->showWalletAction)($data['user_id'], true);

        return $wallet;
    }
}
