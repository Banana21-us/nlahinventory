<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';

    protected $fillable = [
        'employee_number', 'user_id', 'biometric_id',
        'last_name', 'first_name', 'middle_name', 'extension',
        'birth_date', 'place_of_birth', 'gender', 'civil_status',
        'citizenship', 'religion', 'blood_type', 'height', 'weight',
        'mobile_no', 'telephone', 'email_add', 'p_address', 'c_address',
        'contact_person', 'contact_number', 'contact_relationship',
        'signature', 'picture', 'id_no',
        'is_solo_parent',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_solo_parent' => 'boolean',
    ];

    public function dependencies()
    {
        return $this->hasMany(Dependency::class, 'employee_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employmentDetail()
    {
        return $this->hasOne(EmploymentDetail::class, 'employee_id', 'id');
    }
}
