<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name', 'code', 'dept_head_id',
    ];

    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
