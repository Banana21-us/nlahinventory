<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveBalance extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'total',
        'consumed',
    ];

    protected $casts = [
        'total'    => 'decimal:2',
        'consumed' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function available(): float
    {
        return max(0, (float) $this->total - (float) $this->consumed);
    }
}
