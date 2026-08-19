<?php

namespace App\Http\Controllers;

use App\Models\BankConnection;
use App\Models\BankMutation;
use App\Models\Organization;
use App\Models\Transaction;
use App\Services\DummyBankService;
use Illuminate\Http\Request;

class BankConnectionController extends Controller
{
    protected DummyBankService $bankService;

    public function __construct(DummyBankService $bankService)
    {
        $this->bankService = $bankService;
    }

    public function index(Request $request)
    {
        $connections = BankConnection::with(['chartOfAccount', 'mutations' => function ($q) {
            $q->latest('transaction_date');
        }])->get();

        $mutationsQuery = BankMutation::with(['bankConnection', 'reconciledTransaction'])->latest('transaction_date');

        if ($request->filled('bank_connection_id')) {
            $mutationsQuery->where('bank_connection_id', $request->bank_connection_id);
        }

        if ($request->filled('type')) {
            $mutationsQuery->where('mutation_type', $request->type);
        }

        $mutations = $mutationsQuery->paginate(15);

        $organization = Organization::first();
        $transactions = Transaction::where('organization_id', $organization->id ?? '')
            ->latest('transaction_date')
            ->take(30)
            ->get();

        return view('dashboard.bank-mutations', compact('connections', 'mutations', 'transactions'));
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
