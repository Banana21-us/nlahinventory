<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayoffLeaveCredit extends Model
{
    protected $fillable = [
        'user_id',
        'payoff_application_id',
        'hours_earned',
        'hours_remaining',
        'earned_at',
        'expires_at',
    ];

    protected $casts = [
        'hours_earned'    => 'float',
        'hours_remaining' => 'float',
        'earned_at'       => 'date',
        'expires_at'      => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payoffApplication(): BelongsTo
    {
        return $this->belongsTo(PayoffApplication::class);
    }

    public function consumptions(): HasMany
    {
        return $this->hasMany(PayoffCreditConsumption::class, 'payoff_leave_credit_id');
    }

    public static function availableHours(int $userId): float
    {
        return (float) static::where('user_id', $userId)
            ->where('expires_at', '>=', now()->toDateString())
            ->where('hours_remaining', '>', 0)
            ->sum('hours_remaining');
    }

    /**
     * Consume hours FIFO (oldest earned_at first).
     * Returns [credit_id => hours_consumed] map for restoration tracking.
     */
    public static function consumeFifo(int $userId, float $hours): array
    {
        $map = [];
        $remaining = $hours;

        $credits = static::where('user_id', $userId)
            ->where('expires_at', '>=', now()->toDateString())
            ->where('hours_remaining', '>', 0)
            ->orderBy('earned_at')
            ->orderBy('id')
            ->get();

        foreach ($credits as $credit) {
            if ($remaining <= 0) {
                break;
            }

            $take = min($credit->hours_remaining, $remaining);
            $credit->decrement('hours_remaining', $take);
            $map[$credit->id] = round($take, 2);
            $remaining = round($remaining - $take, 2);
        }

        return $map;
    }

    /**
     * Restore hours from a [credit_id => hours_consumed] map.
     */
    public static function restoreFromMap(array $map): void
    {
        foreach ($map as $creditId => $hours) {
            static::where('id', $creditId)->increment('hours_remaining', $hours);
        }
    }
}
