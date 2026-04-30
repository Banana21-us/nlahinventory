<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoffCreditConsumption extends Model
{
    protected $fillable = [
        'leave_id',
        'payoff_leave_credit_id',
        'hours_consumed',
    ];

    protected $casts = [
        'hours_consumed' => 'float',
    ];

    public function leave(): BelongsTo
    {
        return $this->belongsTo(Leave::class);
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(PayoffLeaveCredit::class, 'payoff_leave_credit_id');
    }
}
