<?php

namespace Tests\Feature;

use App\Models\ChartOfAccount;
use App\Models\Organization;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_transaction_creates_double_entry_journals_and_updates_balance(): void
    {
        $this->seed();

        $org = Organization::first();
        $user = User::first();
        $coaBca = ChartOfAccount::where('account_code', '101-BCA')->first();

        $initialBalance = (float) $coaBca->balance;
        $incomeAmount = 5000000.00;

        $service = new TransactionService();
        $transaction = $service->createTransaction([
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'INCOME',
            'amount' => $incomeAmount,
            'chart_of_account_id' => $coaBca->id,
            'category' => 'Penjualan Test',
            'description' => 'Test Transaction',
        ], $org->id, $user->id);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => $incomeAmount,
            'type' => 'INCOME',
        ]);

        $this->assertCount(2, $transaction->journalEntries);

        $updatedCoa = $coaBca->fresh();
        $this->assertEquals($initialBalance + $incomeAmount, (float) $updatedCoa->balance);
    }

    public function test_update_and_delete_transaction_reverts_journal_entry_balances(): void
    {
        $this->seed();

        $org = Organization::first();
        $user = User::first();
        $coaBca = ChartOfAccount::where('account_code', '101-BCA')->first();

        $service = new TransactionService();
        $transaction = $service->createTransaction([
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'INCOME',
            'amount' => 10000000.00,
            'chart_of_account_id' => $coaBca->id,
            'category' => 'Test Income',
        ], $org->id, $user->id);

        $balanceAfterCreate = (float) $coaBca->fresh()->balance;

        // Update transaction
        $service->updateTransaction($transaction, [
            'transaction_date' => now()->format('Y-m-d'),
            'type' => 'INCOME',
            'amount' => 15000000.00,
            'chart_of_account_id' => $coaBca->id,
            'category' => 'Updated Income',
        ]);

        $balanceAfterUpdate = (float) $coaBca->fresh()->balance;
        $this->assertEquals($balanceAfterCreate + 5000000.00, $balanceAfterUpdate);

        // Delete transaction
        $service->deleteTransaction($transaction);
        $balanceAfterDelete = (float) $coaBca->fresh()->balance;
        $this->assertEquals($balanceAfterCreate - 10000000.00, $balanceAfterDelete);
    }
}
