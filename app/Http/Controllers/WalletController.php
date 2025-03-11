<?php

namespace App\Http\Controllers;

use App\Actions\Wallet\DepositWalletAction;
use App\Actions\Wallet\ShowWalletAction;
use App\Actions\Wallet\StoreWalletAction;
use App\Actions\Wallet\WithdrawFromWalletAction;
use App\Http\Requests\Wallet\DepositWalletRequest;
use App\Http\Requests\Wallet\WithdrawFromWalletRequest;
use App\Http\Resources\WalletResource;

class WalletController extends Controller
{
    public function store(StoreWalletAction $storeWalletAction)
    {
        $wallet = $storeWalletAction(auth()->user()->id);

        return WalletResource::make($wallet->load('creditTransactions', 'user'));
    }

    public function deposit(DepositWalletRequest $request, DepositWalletAction $depositWalletAction)
    {
        $data = array_merge($request->validated(), [
            'user_id' => auth()->user()->id,
        ]);

        $wallet = $depositWalletAction($data);

        return WalletResource::make($wallet->load('creditTransactions', 'user'));
    }

    public function withdraw(WithdrawFromWalletRequest $request, WithdrawFromWalletAction $withdrawFromWalletAction)
    {
        $data = array_merge($request->validated(), [
            'user_id' => auth()->user()->id,
        ]);

        $wallet = $withdrawFromWalletAction($data);

        return WalletResource::make($wallet->load('creditTransactions', 'user'));
    }

    public function show(ShowWalletAction $showWalletAction)
    {
        $wallet = $showWalletAction(auth()->id(), true);

        return WalletResource::make($wallet->load('creditTransactions', 'user'));
    }
}
