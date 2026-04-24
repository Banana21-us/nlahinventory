<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'item_type_id',
        'location_id',
        'status',
        'brand',
        'purchase_date',
        'sku',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(AssetLocation::class, 'location_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AssetTransaction::class, 'asset_id');
    }
}
