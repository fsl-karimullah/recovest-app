<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\BankConnection;
use App\Models\BankMutation;
use App\Models\ChartOfAccount;
use App\Models\Organization;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $organization = Organization::first();
        $query = Transaction::where('organization_id', $organization->id)
            ->with(['chartOfAccount', 'bankConnection'])
            ->latest('transaction_date');

        if ($request->filled('type') && in_array($request->type, ['INCOME', 'EXPENSE', 'TRANSFER'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->paginate(15);

        $chartOfAccounts = ChartOfAccount::where('organization_id', $organization->id)->get();
        $bankConnections = BankConnection::where('organization_id', $organization->id)->get();

        return view('dashboard.transactions', compact(
            'transactions',
            'chartOfAccounts',
            'bankConnections'
        ));
    }

    public function store(StoreTransactionRequest $request)
    {
        $organization = Organization::first();
        $user = User::first();

        $data = $request->validated();

        if ($request->hasFile('proof_attachment')) {
            $path = $request->file('proof_attachment')->store('proofs', 'public');
            $data['proof_attachment_path'] = $path;
        }

        $this->transactionService->createTransaction($data, $organization->id, $user->id);

        return back()->with('success', 'Transaksi kas berhasil dicatat dan Buku Besar (Journal Entries) diperbarui secara real-time!');
    }

    public function update(StoreTransactionRequest $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('proof_attachment')) {
            $path = $request->file('proof_attachment')->store('proofs', 'public');
            $data['proof_attachment_path'] = $path;
        }

        $this->transactionService->updateTransaction($transaction, $data);

        return back()->with('success', 'Transaksi kas berhasil diperbarui dan saldo buku besar disesuaikan!');
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);

        $this->transactionService->deleteTransaction($transaction);

        return back()->with('success', 'Transaksi kas berhasil dihapus dan dampak saldo telah dikembalikan!');
    }

    public function matchMutation(Request $request)
    {
        $request->validate([
            'mutation_id' => 'required|uuid|exists:bank_mutations,id',
            'transaction_id' => 'required|uuid|exists:transactions,id',
        ]);

        $this->transactionService->matchMutationWithTransaction(
            $request->mutation_id,
            $request->transaction_id
        );

        return back()->with('success', 'Mutasi bank berhasil direkonsiliasi dengan transaksi internal!');
    }

    public function createFromMutation(Request $request)
    {
        $request->validate([
            'mutation_id' => 'required|uuid|exists:bank_mutations,id',
            'category' => 'required|string|max:100',
        ]);

        $mutation = BankMutation::with('bankConnection')->findOrFail($request->mutation_id);
        $organization = Organization::first();
        $user = User::first();

        $type = $mutation->mutation_type === 'CR' ? 'INCOME' : 'EXPENSE';
        $coaId = $mutation->bankConnection->chart_of_account_id;

        $transaction = $this->transactionService->createTransaction([
            'transaction_date' => $mutation->transaction_date->format('Y-m-d'),
            'type' => $type,
            'amount' => $mutation->amount,
            'chart_of_account_id' => $coaId,
            'bank_connection_id' => $mutation->bank_connection_id,
            'category' => $request->category,
            'description' => $mutation->description,
        ], $organization->id, $user->id);

        $this->transactionService->matchMutationWithTransaction($mutation->id, $transaction->id);

        return back()->with('success', 'Transaksi kas berhasil dibuat dari mutasi bank & langsung direkonsiliasi!');
    }
}
