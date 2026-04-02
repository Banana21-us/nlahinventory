<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiometricLog extends Model
{
    protected $fillable = [
        'biometric_id', 'user_id', 'punch_time', 'punch_type', 'is_processed', 'source_file',
    ];

    protected $casts = [
        'punch_time'   => 'datetime',
        'is_processed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
