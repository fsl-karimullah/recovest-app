<?php

namespace App\Http\Controllers;

use App\Models\BankConnection;
use App\Models\BankMutation;
use App\Models\ChartOfAccount;
use App\Models\Organization;
use App\Models\Transaction;
use App\Services\DummyBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BankConnectionController extends Controller
{
    protected DummyBankService $bankService;

    public function __construct(DummyBankService $bankService)
    {
        $this->bankService = $bankService;
    }

    public function index(Request $request)
    {
        $organization = Organization::first();
        $connections = BankConnection::where('organization_id', $organization->id ?? '')
            ->with(['chartOfAccount', 'mutations' => function ($q) {
                $q->latest('transaction_date');
            }])->get();

        $chartOfAccounts = ChartOfAccount::where('organization_id', $organization->id ?? '')
            ->where('account_type', 'ASSET')
            ->get();

        $mutationsQuery = BankMutation::whereIn('bank_connection_id', $connections->pluck('id'))
            ->with(['bankConnection', 'reconciledTransaction'])
            ->latest('transaction_date');

        if ($request->filled('bank_connection_id')) {
            $mutationsQuery->where('bank_connection_id', $request->bank_connection_id);
        }

        if ($request->filled('type')) {
            $mutationsQuery->where('mutation_type', $request->type);
        }

        $mutations = $mutationsQuery->paginate(15);

        $transactions = Transaction::where('organization_id', $organization->id ?? '')
            ->latest('transaction_date')
            ->take(30)
            ->get();

        return view('dashboard.bank-mutations', compact('connections', 'mutations', 'transactions', 'chartOfAccounts'));
    }

    public function storeConnection(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:255',
            'chart_of_account_id' => 'required|uuid|exists:chart_of_accounts,id',
        ]);

        $organization = Organization::first();

        BankConnection::create([
            'organization_id' => $organization->id,
            'chart_of_account_id' => $request->chart_of_account_id,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder_name' => $request->account_holder_name,
            'connection_status' => 'CONNECTED',
            'last_synced_at' => now(),
            'is_dummy' => false,
        ]);

        return back()->with('success', 'Rekening bank baru berhasil ditambahkan!');
    }

    public function destroyConnection(string $id)
    {
        $connection = BankConnection::findOrFail($id);
        $connection->delete();

        return back()->with('success', 'Rekening bank berhasil dihapus!');
    }

    public function storeMutation(Request $request)
    {
        $request->validate([
            'bank_connection_id' => 'required|uuid|exists:bank_connections,id',
            'transaction_date' => 'required|date',
            'mutation_type' => 'required|in:CR,DB',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
        ]);

        $bankConnection = BankConnection::findOrFail($request->bank_connection_id);
        $lastMutation = BankMutation::where('bank_connection_id', $bankConnection->id)
            ->latest('created_at')
            ->first();

        $currentBalance = $lastMutation ? (float) $lastMutation->balance_after : (float) ($bankConnection->chartOfAccount->balance ?? 0);
        $amount = (float) $request->amount;
        $balanceAfter = $request->mutation_type === 'CR' ? $currentBalance + $amount : $currentBalance - $amount;

        BankMutation::create([
            'bank_connection_id' => $bankConnection->id,
            'transaction_date' => $request->transaction_date,
            'mutation_type' => $request->mutation_type,
            'amount' => $amount,
            'description' => $request->description,
            'balance_after' => $balanceAfter,
            'is_reconciled' => false,
        ]);

        $bankConnection->update(['last_synced_at' => now()]);

        return back()->with('success', 'Mutasi bank baru berhasil ditambahkan secara manual!');
    }

    public function destroyMutation(string $id)
    {
        $mutation = BankMutation::findOrFail($id);
        $mutation->delete();

        return back()->with('success', 'Catatan mutasi bank berhasil dihapus!');
    }

    public function sync(string $id)
    {
        $this->bankService->simulateSyncMutations($id, rand(5, 10));

        return back()->with('success', 'Rekening bank berhasil disinkronkan. Mutasi terbaru telah diimpor!');
    }

    public function simulateWebhook(Request $request, string $id)
    {
        $customData = [
            'amount' => $request->input('amount', rand(500000, 15000000)),
            'mutation_type' => $request->input('type', 'CR'),
            'description' => $request->input('description', '[LIVE WEBHOOK] Transfer Masuk Instant QRIS Sandbox'),
        ];

        $this->bankService->simulateIncomingWebhook($id, $customData);

        return back()->with('success', '⚡ Simulasi Webhook Mutasi Masuk berhasil diterima & dicatat!');
    }
}
