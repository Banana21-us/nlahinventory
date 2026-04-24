<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $table = 'assets';
    
    protected $fillable = [
        'asset_code', 'name', 'category', 'department_id', 'location_id',
        'brand', 'model', 'serial_number', 'purchase_date', 'purchase_cost',
        'status', 'condition_status', 'notes', 'image'  // Added 'image' here
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    // Scope for available assets (not assigned to any department/location)
    public function scopeAvailable($query)
    {
        return $query->whereNull('department_id')
                     ->whereNull('location_id')
                     ->where('status', 'active');
    }

    // Scope for assigned assets
    public function scopeAssigned($query)
    {
        return $query->whereNotNull('department_id')
                     ->whereNotNull('location_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(AssetMovement::class, 'asset_id');
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class, 'asset_id');
    }
}