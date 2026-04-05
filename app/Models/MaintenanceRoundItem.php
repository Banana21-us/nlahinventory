<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRoundItem extends Model
{
    protected $fillable = [
        'round_id',
        'location_area_id',
        'location_area_part_id',
        'status',
        'photo_path',
        'skip_reason',
        'completed_at',
        'order_number',
        'verified_by',
        'verified_at',
        'verification_status',
        'verification_comment',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function round(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRound::class, 'round_id', 'id');
    }

    /** location_area_id references locations.id */
    public function locationArea(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_area_id', 'id');
    }

    /** location_area_part_id references location_area_parts.id */
    public function part(): BelongsTo
    {
        return $this->belongsTo(LocationAreaPart::class, 'location_area_part_id', 'id');
    }

    public function isCR(): bool
    {
        $name = $this->locationArea?->name ?? '';

        return str_contains($name, '| CR')
            || str_ends_with(trim($name), 'CR');
    }

    public function isSkippable(): bool
    {
        $skippable = ['bed', 'table', 'chair', 'chairs', 'bedside table', 'bedside'];
        $partName = $this->part?->areaPart?->name ?? '';

        return in_array(strtolower(trim($partName)), $skippable, true);
    }

    public function requiresPhoto(): bool
    {
        return ! $this->isCR();
    }
}
