<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentDetail extends Model
{
    protected $fillable = [
        'user_id', 'department', 'dept_code', 'position', 'rank',
        'employment_status', 'hiring_date', 'regularization_date',
        'license_no', 'license_expiry', 're_membership',
        'philhealth_no', 'pagibig_no', 'tin_no', 'sss_no', 'gsis_no',
    ];

    protected $casts = [
        'hiring_date'         => 'date',
        'regularization_date' => 'date',
        'license_expiry'      => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}