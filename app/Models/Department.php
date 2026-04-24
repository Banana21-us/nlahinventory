<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    
    protected $fillable = [
        'name',
        'code',
        'dept_head_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'department_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'department_id');
    }


    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
