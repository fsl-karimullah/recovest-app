<?php

namespace App\Services;

use App\Models\BankMutation;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    /**
     * Create cash flow transaction with double-entry journal balance inside DB Transaction.
     */
    public function createTransaction(array $data, string $orgId, string $userId): Transaction
    {
        return DB::transaction(function () use ($data, $orgId, $userId) {
            $transactionNumber = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $transaction = Transaction::create([
                'organization_id' => $orgId,
                'transaction_number' => $transactionNumber,
                'transaction_date' => $data['transaction_date'],
                'type' => $data['type'],
                'amount' => $data['amount'],
                'chart_of_account_id' => $data['chart_of_account_id'],
                'bank_connection_id' => $data['bank_connection_id'] ?? null,
                'contact_name' => $data['contact_name'] ?? null,
                'reference_number' => $data['reference_number'] ?? null,
                'category' => $data['category'],
                'description' => $data['description'] ?? null,
                'proof_attachment_path' => $data['proof_attachment_path'] ?? null,
                'status' => 'COMPLETED',
                'created_by' => $userId,
            ]);

            $this->applyJournalEntriesAndBalances($transaction, $data, $orgId);

            return $transaction->load('journalEntries');
        });
    }

    /**
     * Update an existing transaction and re-balance journal entries & accounts.
     */
    public function updateTransaction(Transaction $transaction, array $data): Transaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            // 1. Revert previous journal entry balance impacts
            $this->revertJournalEntriesAndBalances($transaction);

            // 2. Delete old journal entries
            JournalEntry::where('transaction_id', $transaction->id)->delete();

            // 3. Update transaction record
            $transaction->update([
                'transaction_date' => $data['transaction_date'],
                'type' => $data['type'],
                'amount' => $data['amount'],
                'chart_of_account_id' => $data['chart_of_account_id'],
                'bank_connection_id' => $data['bank_connection_id'] ?? $transaction->bank_connection_id,
                'contact_name' => $data['contact_name'] ?? null,
                'reference_number' => $data['reference_number'] ?? null,
                'category' => $data['category'],
                'description' => $data['description'] ?? null,
                'proof_attachment_path' => $data['proof_attachment_path'] ?? $transaction->proof_attachment_path,
            ]);

            // 4. Apply new journal entries & update balances
            $this->applyJournalEntriesAndBalances($transaction, $data, $transaction->organization_id);

            return $transaction->load('journalEntries');
        });
    }

    /**
     * Delete (soft-delete) transaction and revert balance impacts.
     */
    public function deleteTransaction(Transaction $transaction): bool
    {
        return DB::transaction(function () use ($transaction) {
            // 1. Revert journal entry impacts
            $this->revertJournalEntriesAndBalances($transaction);

            // 2. Delete journal entries
            JournalEntry::where('transaction_id', $transaction->id)->delete();

            // 3. Unlink any reconciled mutations
            BankMutation::where('reconciled_transaction_id', $transaction->id)->update([
                'is_reconciled' => false,
                'reconciled_transaction_id' => null,
            ]);

            // 4. Soft delete transaction
            return $transaction->delete();
        });
    }

    /**
     * Link bank mutation with internal recorded transaction.
     */
    public function matchMutationWithTransaction(string $mutationId, string $transactionId): bool
    {
        return DB::transaction(function () use ($mutationId, $transactionId) {
            $mutation = BankMutation::findOrFail($mutationId);
            $transaction = Transaction::findOrFail($transactionId);

            $mutation->update([
                'is_reconciled' => true,
                'reconciled_transaction_id' => $transaction->id,
            ]);

            return true;
        });
    }

    /**
     * Helper to apply double-entry journal balance logic.
     */
    protected function applyJournalEntriesAndBalances(Transaction $transaction, array $data, string $orgId): void
    {
        $targetCoa = ChartOfAccount::findOrFail($data['chart_of_account_id']);
        $amount = (float) $data['amount'];

        if ($data['type'] === 'INCOME') {
            // Debit Bank/Cash Account (Asset +)
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'chart_of_account_id' => $targetCoa->id,
                'debit' => $amount,
                'credit' => 0,
                'entry_date' => now(),
            ]);
            $targetCoa->increment('balance', $amount);

            // Credit Revenue/Sales Account (Revenue +)
            $revenueCoa = ChartOfAccount::where('organization_id', $orgId)
                ->where('account_type', 'REVENUE')
                ->first() ?? $targetCoa;

            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'chart_of_account_id' => $revenueCoa->id,
                'debit' => 0,
                'credit' => $amount,
                'entry_date' => now(),
            ]);
            if ($revenueCoa->id !== $targetCoa->id) {
                $revenueCoa->increment('balance', $amount);
            }

        } elseif ($data['type'] === 'EXPENSE') {
            // Debit Expense Account (Expense +)
            $expenseCoa = ChartOfAccount::where('organization_id', $orgId)
                ->where('account_type', 'EXPENSE')
                ->first() ?? $targetCoa;

            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'chart_of_account_id' => $expenseCoa->id,
                'debit' => $amount,
                'credit' => 0,
                'entry_date' => now(),
            ]);
            if ($expenseCoa->id !== $targetCoa->id) {
                $expenseCoa->increment('balance', $amount);
            }

            // Credit Bank/Cash Account (Asset -)
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'chart_of_account_id' => $targetCoa->id,
                'debit' => 0,
                'credit' => $amount,
                'entry_date' => now(),
            ]);
            $targetCoa->decrement('balance', $amount);
        }
    }

    /**
     * Helper to revert double-entry journal balance impact.
     */
    protected function revertJournalEntriesAndBalances(Transaction $transaction): void
    {
        $entries = JournalEntry::where('transaction_id', $transaction->id)->get();

        foreach ($entries as $entry) {
            $coa = ChartOfAccount::find($entry->chart_of_account_id);
            if (!$coa) continue;

            if ($entry->debit > 0) {
                $coa->decrement('balance', (float) $entry->debit);
            }
            if ($entry->credit > 0) {
                $coa->decrement('balance', (float) $entry->credit);
            }
        }
    }
}
