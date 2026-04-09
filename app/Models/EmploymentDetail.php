<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentDetail extends Model
{
    protected $fillable = [
        'employee_id', 'department_id', 'position', 'access_key_id', 'rank',
        'employment_status', 'hiring_date', 'regularization_date',
        'license_no', 'license_expiry', 're_membership',
        'philhealth_no', 'pagibig_no', 'tin_no', 'sss_no', 'gsis_no',
    ];

    protected $casts = [
        'hiring_date' => 'date',
        'regularization_date' => 'date',
        'license_expiry' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function accessKey()
    {
        return $this->belongsTo(AccessKey::class);
    }
}
