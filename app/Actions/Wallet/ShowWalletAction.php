<?php

namespace App\Actions\Wallet;

use App\Models\Wallet;

class ShowWalletAction
{
    public function __invoke(int $walletId, bool $recalculate = false)
    {
        $wallet = Wallet::findOrFail($walletId);

        if ($recalculate) {
            $balance = $wallet->creditTransactions()->sum('amount');

            $wallet->update([
                'balance' => $balance,
            ]);
        }

        return $wallet;

    }
}
