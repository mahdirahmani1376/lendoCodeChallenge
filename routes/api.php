<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::group([
    'controller' => UserController::class,
    'prefix' => 'users',
], function () {
    Route::get('/{id}', 'show')->name('users.show')->middleware('auth:sanctum');
    Route::post('/login', 'login')->name('users.login');
    Route::post('/register', 'register')->name('users.register');
    Route::put('/update', 'update')->name('users.update')->middleware('auth:sanctum');
});

Route::controller(WalletController::class)
    ->prefix('/wallet')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/create', 'store')->name('wallet.store');
        Route::post('/deposit', 'deposit')->name('wallet.deposit');
        Route::post('/withdraw', 'withdraw')->name('wallet.withdraw');
        Route::post('/balance/{wallet_id}', 'show')->name('wallet.show');
    });
