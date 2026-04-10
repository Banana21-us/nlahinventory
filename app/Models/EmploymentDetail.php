<?php

namespace App\Models;

use App\Services\LeaveAccrualService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmploymentDetail extends Model
{
    /**
     * Automatically fire the VL transition grant when regularization_date is
     * set for the first time (e.g. HR updates the employee's status to Regular).
     */
    protected static function booted(): void
    {
        static::updated(function (EmploymentDetail $detail) {
            if (
                $detail->wasChanged('regularization_date')
                && $detail->regularization_date !== null
            ) {
                $userId = DB::table('employee')
                    ->where('id', $detail->employee_id)
                    ->value('user_id');

                $user = $userId ? User::find($userId) : null;

                if ($user) {
                    try {
                        app(LeaveAccrualService::class)->onRegularization($user);
                    } catch (\Throwable $e) {
                        Log::error('LeaveAccrualService::onRegularization failed on EmploymentDetail update', [
                            'employee_id' => $detail->employee_id,
                            'error'       => $e->getMessage(),
                        ]);
                    }
                }
            }
        });
    }

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
