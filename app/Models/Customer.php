<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'balance',
        'charges',
        'phone',
        'status',
    ];

    protected $casts = [
        'balance' => 'float',
        'charges' => 'float',
    ];

    // ─── Accessors ───────────────────────────────────────────────────────────

    /**
     * True when the customer owes money (has unpaid charges).
     */
    public function getHasChargesAttribute(): bool
    {
        return (float) $this->charges > 0;
    }

    /**
     * Net position: positive = credit balance, negative = owes money.
     */
    public function getNetBalanceAttribute(): float
    {
        return (float) $this->balance - (float) $this->charges;
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
