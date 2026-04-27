<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $table = 'assets';
    
    protected $fillable = [
        'asset_code',
        'name',
        'category',
        'department_id',
        'location_id',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'status',
        'condition_status',
        'notes',
        'image',
        'item_type_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}