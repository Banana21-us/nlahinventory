<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_number',
        'full_name',
        'age',
        'gender',
        'contact_number',
        'medical_history',
    ];

    public function dispensings()
    {
        // This allows us to get a patient's history using $patient->dispensings
        return $this->hasMany(Dispensing::class);
    }
}
