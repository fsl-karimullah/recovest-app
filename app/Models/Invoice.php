<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasUuids;

    protected $fillable = [
        'organization_id',
        'invoice_number',
        'client_name',
        'client_email',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'id' => 'string',
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
