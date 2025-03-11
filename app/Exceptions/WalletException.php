<?php

namespace App\Exceptions;

use Exception;

class WalletException extends Exception
{
    public static function causeOfAlreadyExistsForUserId(int $user_id)
    {
        throw new static(trans('exceptions.wallet_already_exists_for_user', [
            'user_id' => $user_id,
        ]));
    }

    public static function causeOfWithDrawAmountBiggerThanWalletBalance()
    {
        throw new static(trans('exceptions.withdraw_amount_is_bigger_than_wallet_balance'));
    }
}
