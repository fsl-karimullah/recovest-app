<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedAsset extends Model
{
    use HasUuids;

    protected $fillable = [
        'organization_id',
        'asset_code',
        'asset_name',
        'purchase_date',
        'purchase_cost',
        'salvage_value',
        'useful_life_years',
        'accumulated_depreciation',
        'book_value',
    ];

    protected $casts = [
        'id' => 'string',
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'book_value' => 'decimal:2',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
