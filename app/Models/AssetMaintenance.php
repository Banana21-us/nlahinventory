<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenance extends Model
{
    protected $table = 'asset_maintenance';

    protected $fillable = [
        'asset_id',
        'issue_description',
        'repair_action',
        'status',
        'maintenance_department_id',
        'cost',
        'reported_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function maintenanceDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'maintenance_department_id');
    }
}
