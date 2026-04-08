<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dependency extends Model
{
    protected $table = 'dependencies';

    protected $fillable = [
        'employee_id',
        'lastname',
        'firstname',
        'middlename',
        'extension',
        'relationship',
        'gender',
        'birthday',
        'age',
    ];

    protected $casts = [
        'birthday' => 'date',
        'age' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
