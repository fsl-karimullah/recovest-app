<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankConnection extends Model
{
    use HasUuids;

    protected $fillable = [
        'organization_id',
        'chart_of_account_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'connection_status',
        'last_synced_at',
        'is_dummy',
        'credentials_payload',
    ];

    protected $casts = [
        'id' => 'string',
        'is_dummy' => 'boolean',
        'last_synced_at' => 'datetime',
        'credentials_payload' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function mutations(): HasMany
    {
        return $this->hasMany(BankMutation::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->connection_status) {
            'CONNECTED' => 'green',
            'SYNCING'   => 'blue',
            'ERROR'     => 'red',
            default     => 'gray',
        };
    }
}
