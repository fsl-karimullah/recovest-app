<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankMutation extends Model
{
    use HasUuids;

    protected $fillable = [
        'bank_connection_id',
        'transaction_date',
        'mutation_type',
        'amount',
        'description',
        'balance_after',
        'is_reconciled',
        'reconciled_transaction_id',
        'raw_payload',
    ];

    protected $casts = [
        'id' => 'string',
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'is_reconciled' => 'boolean',
        'raw_payload' => 'array',
    ];

    public function bankConnection(): BelongsTo
    {
        return $this->belongsTo(BankConnection::class);
    }

    public function reconciledTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'reconciled_transaction_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
