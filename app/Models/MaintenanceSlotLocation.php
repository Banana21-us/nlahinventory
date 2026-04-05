<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSlotLocation extends Model
{
    protected $fillable = [
        'slot_id',
        'location_area_id',
        'order_number',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSlot::class, 'slot_id', 'id');
    }

    /** location_area_id references locations.id */
    public function locationArea(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_area_id', 'id');
    }
}
