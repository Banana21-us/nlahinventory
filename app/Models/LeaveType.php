<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'code',
        'label',
        'is_paid',
        'requires_attachment',
        'solo_parent_only',
        'requires_admin_approval',
        'annual_days',
        'reset_type',
        'is_active',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'requires_attachment' => 'boolean',
        'solo_parent_only' => 'boolean',
        'requires_admin_approval' => 'boolean',
        'is_active' => 'boolean',
        'annual_days' => 'float',
    ];

    /**
     * Map this leave type to its payroll_and_leaves column prefix.
     * e.g. 'vl' → vl_total / vl_consumed
     * Returns null when there is no per-employee running balance.
     */
    public function getPayrollKey(): ?string
    {
        return match ($this->code) {
            'VL'             => 'vl',
            'SL', 'SL_X', 'SL_M' => 'sl',
            'BL'             => 'bl',
            'SPL'            => 'spl',
            'EL'             => 'el',
            'ML'             => 'ml',
            'PL'             => 'pl',
            'SYL'            => 'syl',
            'CAL'            => 'cal',
            'STL'            => 'stl',
            'MWL'            => 'mwl',
            default          => null,
        };
    }

    public function isLWOP(): bool
    {
        return $this->code === 'LWOP';
    }

    /**
     * Some sub-types share a balance row with their parent type.
     * e.g. SL_X (Extended) and SL_M (Maternity-linked SL) draw from the SL bucket.
     */
    public function getCanonicalCode(): string
    {
        return match ($this->code) {
            'SL_X', 'SL_M' => 'SL',
            default         => $this->code,
        };
    }

    public function getCanonicalLeaveType(): ?self
    {
        $canonical = $this->getCanonicalCode();

        return $canonical === $this->code
            ? $this
            : static::where('code', $canonical)->first();
    }

    /**
     * Look up a LeaveType by code first, then fall back to label.
     * Handles records created before the code-based refactor.
     */
    public static function resolve(string $value): ?self
    {
        return static::where('code', $value)
            ->orWhere('label', $value)
            ->first();
    }
}
