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

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $organization = Organization::first();

        // Auto-provision organization if missing for new registered user
        if (!$organization) {
            $organization = Organization::create([
                'id' => Str::uuid(),
                'name' => 'PT ' . ($user->name ?? 'Perusahaan Saya'),
                'currency' => 'IDR',
            ]);

            // Create default Chart of Accounts
            $coaBca = ChartOfAccount::create([
                'id' => Str::uuid(),
                'organization_id' => $organization->id,
                'account_code' => '101-BCA',
                'account_name' => 'Bank BCA Corporate',
                'account_type' => 'ASSET',
                'balance' => 0.00,
                'is_active' => true,
            ]);

            $coaSales = ChartOfAccount::create([
                'id' => Str::uuid(),
                'organization_id' => $organization->id,
                'account_code' => '401-SALES',
                'account_name' => 'Pendapatan Penjualan',
                'account_type' => 'REVENUE',
                'balance' => 0.00,
                'is_active' => true,
            ]);

            $bankService = new DummyBankService();
            $conn = $bankService->connectBank('BCA', '8820192xxx', $user->name ?? 'Pemilik', $organization->id, $coaBca->id);
            $bankService->simulateSyncMutations($conn->id, 10);
        }

        // Metrics calculations
        $bankConnections = BankConnection::where('organization_id', $organization->id)
            ->with('chartOfAccount')
            ->get();

        $totalBankBalance = $bankConnections->sum(function ($conn) {
            return $conn->chartOfAccount ? (float) $conn->chartOfAccount->balance : 0;
        });

        $totalIncome = Transaction::where('organization_id', $organization->id)
            ->where('type', 'INCOME')
            ->where('status', 'COMPLETED')
            ->sum('amount');

        $totalExpense = Transaction::where('organization_id', $organization->id)
            ->where('type', 'EXPENSE')
            ->where('status', 'COMPLETED')
            ->sum('amount');

        $netCashFlow = $totalIncome - $totalExpense;

        $recentMutations = BankMutation::whereIn('bank_connection_id', $bankConnections->pluck('id'))
            ->latest('transaction_date')
            ->take(10)
            ->get();

        $chartOfAccounts = ChartOfAccount::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->get();

        return view('dashboard.index', compact(
            'organization',
            'bankConnections',
            'totalBankBalance',
            'totalIncome',
            'totalExpense',
            'netCashFlow',
            'recentMutations',
            'chartOfAccounts'
        ));
    }
}
