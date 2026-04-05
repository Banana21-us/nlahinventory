<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispensing extends Model
{
    protected $fillable = [
        'patient_id',
        'medicine_id',
        'quantity_dispensed',
        'dispensed_by',
        'medmission_place',
    ];

    // Relationship to Patient
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // Relationship to Medicine
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
