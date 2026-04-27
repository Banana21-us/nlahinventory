<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NurseScheduleEntry extends Model
{
    protected $fillable = [
        'schedule_date',
        'section',
        'slot',
        'period',
        'employee_id',
        'custom_name',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
