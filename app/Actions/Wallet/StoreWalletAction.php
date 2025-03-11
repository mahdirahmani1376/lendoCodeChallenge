<?php

namespace App\Actions\Wallet;

use App\Exceptions\WalletException;
use App\Models\Wallet;

class StoreWalletAction
{
    public function __invoke(int $userId): Wallet
    {
        $wallet = Wallet::query()->where('user_id', $userId);
        if ($wallet) {
            throw WalletException::causeOfAlreadyExistsForUserId($userId);
        }

        $wallet = Wallet::query()->create([
            'user_id' => $userId,
        ]);

        return $wallet;

    }
}
