<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Organization;
use App\Models\User;
use App\Services\DummyBankService;
use App\Services\TransactionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyBankDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Organization
        $org = Organization::create([
            'id' => Str::uuid(),
            'name' => 'PT Sinergi Berkah Nusantara',
            'currency' => 'IDR',
        ]);

        // 2. Create User
        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'Admin Recovest',
            'email' => 'admin@recovest.id',
            'password' => Hash::make('password123'),
        ]);

        // 3. Create Chart of Accounts (All initial balances start at 0.00 - NO HARDCODED BALANCES)
        $coaBca = ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '101-BCA',
            'account_name' => 'Bank BCA Corporate (8820192xxx)',
            'account_type' => 'ASSET',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        $coaMandiri = ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '102-MND',
            'account_name' => 'Bank Mandiri Operasional (1310098xxx)',
            'account_type' => 'ASSET',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        $coaSales = ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '401-SALES',
            'account_name' => 'Pendapatan Penjualan SaaS & QRIS',
            'account_type' => 'REVENUE',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        $coaOps = ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '501-OPS',
            'account_name' => 'Beban Operasional & Server',
            'account_type' => 'EXPENSE',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        // 4. Create 2 Bank Connections via Sandbox Bank Engine
        $bankService = new DummyBankService();

        $bcaConn = $bankService->connectBank(
            'BCA',
            '8820192771',
            'PT Sinergi Berkah Nusantara',
            $org->id,
            $coaBca->id
        );

        $mandiriConn = $bankService->connectBank(
            'Mandiri',
            '131009822301',
            'PT Sinergi Berkah Nusantara',
            $org->id,
            $coaMandiri->id
        );

        // 5. Seed 30 days of Bank Feeds (Sandbox Mutasi Feed Bank)
        $bankService->simulateSyncMutations($bcaConn->id, 15);
        $bankService->simulateSyncMutations($mandiriConn->id, 10);

        // 6. Create initial REAL internal transactions via TransactionService so journal entries & balances are 100% real
        $trxService = new TransactionService();

        // Transaction 1: Real Income BCA
        $trxService->createTransaction([
            'transaction_date' => now()->subDays(5)->format('Y-m-d'),
            'type' => 'INCOME',
            'amount' => 150000000.00,
            'chart_of_account_id' => $coaBca->id,
            'bank_connection_id' => $bcaConn->id,
            'category' => 'Penjualan QRIS Tokopedia',
            'contact_name' => 'PT Tokopedia Settlement',
            'description' => 'Pemasukan Hasil Settlement QRIS Toko',
        ], $org->id, $user->id);

        // Transaction 2: Real Income Mandiri
        $trxService->createTransaction([
            'transaction_date' => now()->subDays(3)->format('Y-m-d'),
            'type' => 'INCOME',
            'amount' => 85000000.00,
            'chart_of_account_id' => $coaMandiri->id,
            'bank_connection_id' => $mandiriConn->id,
            'category' => 'Pelunasan Invoice Klien',
            'contact_name' => 'CV Sumber Mas',
            'description' => 'Pembayaran Invoice #INV/2026/08/002',
        ], $org->id, $user->id);

        // Transaction 3: Real Expense BCA
        $trxService->createTransaction([
            'transaction_date' => now()->subDays(2)->format('Y-m-d'),
            'type' => 'EXPENSE',
            'amount' => 12500000.00,
            'chart_of_account_id' => $coaBca->id,
            'bank_connection_id' => $bcaConn->id,
            'category' => 'Gaji & Operasional',
            'contact_name' => 'Internal Payroll',
            'description' => 'Gaji Staf Ops & Biaya Server Cloud',
        ], $org->id, $user->id);
    }
}
