<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationAreaPart extends Model
{
    public $timestamps = false;

    protected $fillable = ['location_id', 'area_part_id', 'frequency'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function areaPart(): BelongsTo
    {
        return $this->belongsTo(AreaPart::class, 'area_part_id', 'id');
    }
}
