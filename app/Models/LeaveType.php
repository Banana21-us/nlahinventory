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
        'is_paid'                 => 'boolean',
        'requires_attachment'     => 'boolean',
        'solo_parent_only'        => 'boolean',
        'requires_admin_approval' => 'boolean',
        'is_active'               => 'boolean',
        'annual_days'             => 'float',
    ];
}
