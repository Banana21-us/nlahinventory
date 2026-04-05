<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceSlot extends Model
{
    protected $fillable = [
        'user_id',
        'slot_number',
        'slot_name',
        'last_used_at',
        'is_active',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function slotLocations(): HasMany
    {
        return $this->hasMany(MaintenanceSlotLocation::class, 'slot_id', 'id')
            ->orderBy('order_number');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(MaintenanceRound::class, 'slot_id', 'id');
    }
}
