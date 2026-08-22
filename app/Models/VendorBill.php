<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorBill extends Model
{
    use HasUuids;

    protected $fillable = [
        'organization_id',
        'bill_number',
        'vendor_name',
        'bill_date',
        'due_date',
        'total_amount',
        'category',
        'status',
        'notes',
    ];

    protected $casts = [
        'id' => 'string',
        'bill_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
