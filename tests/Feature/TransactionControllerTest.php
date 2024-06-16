<?php

namespace Tests\Feature;

use App\Exceptions\TransactionException;
use App\Exceptions\UserException;
use App\Exceptions\WalletException;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_make_a_successful_transaction(): void
    {
        $user = User::factory()->create();
        $user->wallet()->update([
            'balance' => 150
        ]);

        $receiver = User::factory()->create(['role' => 'shopkeeper']);
        $receiver->wallet()->update([
            'balance' => 50
        ]);

        Http::fake([
            config('authorization.url') => Http::response([
                'message' => 'Autorizado'
            ], Response::HTTP_OK)
        ]);

        Http::fake([
            config('notification.url') => Http::response([
                'message' => 'Success'
            ], Response::HTTP_OK)
        ]);

        $response = $this->post(route('transaction'), [
            'payer_id' => $user->id,
            'receiver_id' => $receiver->id,
            'amount' => 100
        ]);

        $transaction = $response->collect('data');

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $user->id,
            'balance' => 50
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $receiver->id,
            'balance' => 150
        ]);

        $this->assertDatabaseHas('transactions', [
                'id' => $transaction['id'],
                'amount' => 100
            ]
        );

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertCreated();
    }

    public function test_shopkeeper_cant_send_transaction(): void
    {
        $user = User::factory()->has(Wallet::factory()->state(['balance' => 200]))->state(['role' => 'shopkeeper'])->create();
        $receiver = User::factory()->has(Wallet::factory()->state(['balance' => 0]))->create();

        $response = $this->post(route('transaction'), [
            'payer_id' => $user->id,
            'receiver_id' => $receiver->id,
            'amount' => 100,
        ]);

        $exception = UserException::cantSendTransaction();

        $response
            ->assertUnprocessable()
            ->assertSee($exception->getMessage());
    }

    public function test_user_cant_send_transaction_to_yourself(): void
    {
        $user = User::factory()->create([
            'role' => 'shopkeeper'
        ]);

        $user->wallet()->update([
            'balance' => 150
        ]);

        $response = $this->post(route('transaction'), [
            'payer_id' => $user->id,
            'receiver_id' => $user->id,
            'amount' => 100,
        ]);

        $exception = TransactionException::cantSendTransactionToYourself();

        $response
            ->assertSee($exception->getMessage())
            ->assertStatus($exception->getCode());
    }

    public function test_user_without_enough_balance_cant_send_transaction(): void
    {
        $user = User::factory()->create();
        $user->wallet()->update([
            'balance' => 10
        ]);

        $receiver = User::factory()->create();

        $response = $this->post(route('transaction'), [
            'payer_id' => $user->id,
            'receiver_id' => $receiver->id,
            'amount' => 100,
        ]);

        $exception = WalletException::insufficientBalance();

        $response
            ->assertStatus($exception->getCode())
            ->assertSee($exception->getMessage());
    }

    public function test_cannot_make_transaction_with_unauthorized_service(): void
    {
        $user = User::factory()->create();
        $receiver = User::factory()->create();

        Http::fake([
            config('authorization.url') => Http::response([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED)
        ]);

        $response = $this->post(route('transaction'), [
            'payer_id' => $user->id,
            'receiver_id' => $receiver->id,
            'amount' => 20,
        ]);

        $exception = TransactionException::transactionUnauthorized();

        $response
            ->assertStatus($exception->getCode())
            ->assertSee($exception->getMessage());
    }
}
