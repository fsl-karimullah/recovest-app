<?php

namespace App\Services;

use App\Models\BankConnection;
use App\Models\BankMutation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DummyBankService
{
    /**
     * Connect dummy bank account with status CONNECTED.
     */
    public function connectBank(
        string $bankName,
        string $accountNumber,
        string $accountHolder,
        string $orgId,
        string $coaId
    ): BankConnection {
        return BankConnection::create([
            'organization_id' => $orgId,
            'chart_of_account_id' => $coaId,
            'bank_name' => $bankName,
            'account_number' => $accountNumber,
            'account_holder_name' => $accountHolder,
            'connection_status' => 'CONNECTED',
            'last_synced_at' => now(),
            'is_dummy' => true,
            'credentials_payload' => [
                'client_id' => 'sandbox_' . Str::random(12),
                'token' => 'bearer_' . Str::random(32),
                'connected_at' => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * Simulate fetching / syncing 10-15 realistic dummy mutations.
     */
    public function simulateSyncMutations(string $bankConnectionId, int $count = 12): Collection
    {
        $bankConnection = BankConnection::findOrFail($bankConnectionId);
        
        $sampleDescriptionsCr = [
            'Pembayaran Invoice PT Nusantara Jaya - INV/2026/08/001',
            'Transfer Masuk QRIS Tokopedia Settlement',
            'Pelunasan Tagihan Klien CV Sumber Mas',
            'Transfer Masuk BNI Direct - DP Proyek Website',
            'Pembayaran EDC Mandiri Cashier Toko Utama',
            'Transfer Masuk BCA KlikPay - Order #88219',
        ];

        $sampleDescriptionsDb = [
            'Pembayaran PLN Listrik Kantor Ags 2026',
            'Gaji Karyawan Staf Finance & Ops',
            'Langganan Adobe Creative Cloud Monthly',
            'Bensin & Tol Operasional Kurir',
            'Pembelian Kertas & ATK Kasir Office',
            'Langganan Server AWS & Cloud Infrastructure',
        ];

        $createdMutations = collect();
        $lastMutation = BankMutation::where('bank_connection_id', $bankConnectionId)
            ->latest('transaction_date')
            ->first();

        $runningBalance = $lastMutation ? (float) $lastMutation->balance_after : 150000000.00;

        for ($i = $count; $i >= 1; $i--) {
            $type = rand(0, 1) === 1 ? 'CR' : 'DB';
            $amount = $type === 'CR' 
                ? rand(1500000, 25000000) 
                : rand(250000, 8500000);

            if ($type === 'CR') {
                $runningBalance += $amount;
                $desc = $sampleDescriptionsCr[array_rand($sampleDescriptionsCr)];
            } else {
                $runningBalance -= $amount;
                $desc = $sampleDescriptionsDb[array_rand($sampleDescriptionsDb)];
            }

            $date = now()->subDays($i)->format('Y-m-d');

            $mutation = BankMutation::create([
                'bank_connection_id' => $bankConnectionId,
                'transaction_date' => $date,
                'mutation_type' => $type,
                'amount' => $amount,
                'description' => $desc,
                'balance_after' => $runningBalance,
                'is_reconciled' => false,
                'raw_payload' => [
                    'source' => 'SANDBOX_SYNC_JOB',
                    'reference_code' => 'TRX-' . strtoupper(Str::random(10)),
                ],
            ]);

            $createdMutations->push($mutation);
        }

        $bankConnection->update([
            'last_synced_at' => now(),
            'connection_status' => 'CONNECTED',
        ]);

        return $createdMutations;
    }

    /**
     * Simulate real-time incoming webhook mutation.
     */
    public function simulateIncomingWebhook(string $bankConnectionId, array $customData = []): BankMutation
    {
        $bankConnection = BankConnection::findOrFail($bankConnectionId);
        
        $lastMutation = BankMutation::where('bank_connection_id', $bankConnectionId)
            ->latest('created_at')
            ->first();

        $currentBalance = $lastMutation ? (float) $lastMutation->balance_after : 100000000.00;

        $type = $customData['mutation_type'] ?? 'CR';
        $amount = $customData['amount'] ?? rand(500000, 5000000);
        $desc = $customData['description'] ?? '[LIVE WEBHOOK] Transfer Masuk Instant QRIS Sandbox';

        $balanceAfter = $type === 'CR' ? $currentBalance + $amount : $currentBalance - $amount;

        $mutation = BankMutation::create([
            'bank_connection_id' => $bankConnectionId,
            'transaction_date' => $customData['transaction_date'] ?? now()->format('Y-m-d'),
            'mutation_type' => $type,
            'amount' => $amount,
            'description' => $desc,
            'balance_after' => $balanceAfter,
            'is_reconciled' => false,
            'raw_payload' => array_merge([
                'event' => 'BANK_MUTATION_NOTIFY',
                'webhook_id' => 'WH-' . Str::uuid(),
                'received_at' => now()->toIso8601String(),
            ], $customData),
        ]);

        $bankConnection->update(['last_synced_at' => now()]);

        return $mutation;
    }
}
