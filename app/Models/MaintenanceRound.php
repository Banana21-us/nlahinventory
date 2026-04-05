<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceRound extends Model
{
    protected $fillable = [
        'user_id',
        'slot_id',
        'started_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSlot::class, 'slot_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaintenanceRoundItem::class, 'round_id', 'id')
            ->orderBy('order_number');
    }

    public function isComplete(): bool
    {
        return $this->items()
            ->where('status', 'pending')
            ->doesntExist();
    }
}
