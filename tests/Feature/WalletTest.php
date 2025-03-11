<?php

namespace Tests\Feature;

use App\Models\CreditTransaction;
use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use Database\Factories\CreditTransactionFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_authenticated_user_create_wallet(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('wallet.store'));

        $response->assertStatus(201);

        $this->assertDatabaseHas('wallets',[
            'user_id' => $user->id
        ]);
    }

    public function test_unAuthenticated_user_can_not_create_wallet(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('wallet.store'));

        $response->assertStatus(401);
    }

    public function test_user_can_not_have_two_wallets(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->postJson(route('wallet.store'));

        $response->assertStatus(500);

        $wallets = Wallet::where('user_id',$user->id)->count();
        $this->assertEquals(1,$wallets);
    }

    public function test_can_user_view_walllet(): void
    {
        $user = User::factory()->create();

        $wallet = Wallet::factory()->create([
            'user_id' => $user->id
        ]);

        $creditTransaction = CreditTransaction::factory()->create([
            'wallet_id' => $wallet->id
        ]);

        $response = $this->actingAs($user)->getJson(
            route('wallet.show',[
                'wallet_id' => $wallet->id
            ]));

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($creditTransaction,$user,$wallet){
            $json
                ->has('data.credit_transactions')
                ->where('data.user_id',$user->id)
                ->where('data.id',$wallet->id)
                ->etc();
        });

    }

    public function test_can_user_deposit_into_wallet(): void
    {
        $user = User::factory()->create();

        $wallet = Wallet::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->postJson(
            route('wallet.deposit'),[
                'amount' => 200
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('credit_transactions',[
            'wallet_id' => $wallet->id,
            'amount' => 200
        ]);

        $this->assertEquals($wallet->refresh()->balance,200);

    }

    public function test_can_user_withdraw_from_wallet(): void
    {
        $user = User::factory()->create();

        $wallet = Wallet::factory()->create([
            'user_id' => $user->id
        ]);

        $creditTransaction = CreditTransaction::Factory()->create([
            'wallet_id' => $wallet->id,
            'amount' => 200
        ]);

        $response = $this->actingAs($user)->postJson(
            route('wallet.withdraw'),[
                'amount' => 200
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('credit_transactions',[
            'wallet_id' => $wallet->id,
            'amount' => -200
        ]);

        $this->assertEquals($wallet->refresh()->balance,0);
    }

}
