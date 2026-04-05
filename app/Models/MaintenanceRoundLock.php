<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRoundLock extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'location_area_id',
        'locked_by_user_id',
        'locked_at',
        'released_at',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('released_at');
    }

    public function locker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by_user_id', 'id');
    }

    /** location_area_id references locations.id */
    public function locationArea(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_area_id', 'id');
    }
}
