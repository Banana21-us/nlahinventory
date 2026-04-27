<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'name',
        'comment',
        'rating',
        'feedback_date',
    ];

    protected $casts = [
        'feedback_date' => 'datetime',
        'rating' => 'integer',
    ];
}
