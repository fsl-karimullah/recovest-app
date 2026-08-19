<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    use HasUuids;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'organization_id',
        'account_code',
        'account_name',
        'account_type',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'id' => 'string',
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function bankConnections(): HasMany
    {
        return $this->hasMany(BankConnection::class);
    }
}
