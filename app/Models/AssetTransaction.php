<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetTransaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'asset_id',
        'location_id_from',
        'location_id_to',
        'type',
        'notes',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(AssetLocation::class, 'location_id_from');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(AssetLocation::class, 'location_id_to');
    }
}
