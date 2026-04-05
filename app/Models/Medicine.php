<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'quantity',
        'generic_name',
        'brand_name',
        'dosage_form',
        'strength',
        'unit',
        'reorder_level',
        'description',
        'status',
    ];
}
