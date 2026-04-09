<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessKey extends Model
{
    protected $fillable = [
        'name',
        'description',
        'redirect_to',
        'is_super',
        'permissions',
    ];

    protected $casts = [
        'is_super' => 'boolean',
        'permissions' => 'array',
    ];

    /**
     * Check if this access key grants the given gate/permission slug.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
