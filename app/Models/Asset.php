<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $table = 'assets';

    protected $fillable = [
        'asset_code',
        'name',
        'category',
        'department_id',
        'maintenance_department_id',
        'location_id',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'lifespan_years',
        'end_of_life',
        'status',
        'condition_status',
        'notes',
        'image',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'end_of_life' => 'date',
        'lifespan_years' => 'integer',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function maintenanceDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'maintenance_department_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(AssetMovement::class, 'asset_id');
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class, 'asset_id');
    }
}
