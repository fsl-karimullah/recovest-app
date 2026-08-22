<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Organization;
use App\Models\User;
use App\Services\DummyBankService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyBankDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Organization
        $org = Organization::create([
            'id' => Str::uuid(),
            'name' => 'PT Sinergi Berkah Nusantara',
            'currency' => 'IDR',
        ]);

        // 2. Create Default Admin User
        $user = User::create([
            'id' => Str::uuid(),
            'name' => 'Admin Recovest',
            'email' => 'admin@recovest.id',
            'password' => Hash::make('password123'),
        ]);

        // 3. Create Default Chart of Accounts (All initial balances start clean at 0.00)
        $coaBca = ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '101-BCA',
            'account_name' => 'Bank BCA Corporate',
            'account_type' => 'ASSET',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        $coaMandiri = ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '102-MND',
            'account_name' => 'Bank Mandiri Operasional',
            'account_type' => 'ASSET',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '401-SALES',
            'account_name' => 'Pendapatan Penjualan SaaS & QRIS',
            'account_type' => 'REVENUE',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $org->id,
            'account_code' => '501-OPS',
            'account_name' => 'Beban Operasional & Server',
            'account_type' => 'EXPENSE',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        // 4. Create Bank Connections with 0 initial mutations (Clean Slate)
        $bankService = new DummyBankService();

        $bankService->connectBank(
            'BCA',
            '8820192771',
            'PT Sinergi Berkah Nusantara',
            $org->id,
            $coaBca->id
        );

        $bankService->connectBank(
            'Mandiri',
            '131009822301',
            'PT Sinergi Berkah Nusantara',
            $org->id,
            $coaMandiri->id
        );

        // NO DUMMY MUTATIONS OR SEEDED TRANSACTIONS ARE GENERATED.
        // App starts with 100% clean data driven strictly by user actions.
    }
}
