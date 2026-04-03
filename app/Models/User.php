<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'employee_number',
        'email_verified_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Derive role from employment_details.position (exact match).
     * Positions mapped here must match what is stored in employment_details.
     * Add position strings below as new roles are defined.
     */
    public function getRoleAttribute(): string
    {
        return match($this->employmentDetail?->position) {
            'HR Manager'  => 'HR',
            'Maintenance' => 'Maintenance',
            'Inspector'   => 'Inspector',
            'Cashier'     => 'Cashier',
            'Staff'       => 'Staff',
            default       => 'pending',
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === 'HR';
    }

    public function isStaff(): bool
    {
        return $this->role === 'Staff';
    }
    public function isMaintenance(): bool
    {
        return $this->role === 'Maintenance';
    }
    public function isInspector(): bool
    {
        return $this->role === 'Inspector';
    }
    public function isDisabled(): bool
    {
        return $this->role === 'Disable';
    }
    public function employee()
    {
        return $this->hasOne(\App\Models\Employee::class, 'user_id');
    }

    /**
     * Employment details via the employee record linked to this user account.
     */
    public function employmentDetail()
    {
        return $this->hasOneThrough(
            \App\Models\EmploymentDetail::class,
            \App\Models\Employee::class,
            'user_id',       // FK on employee → users
            'employee_id',   // FK on employment_details → employee
            'id',            // local key on users
            'id'             // local key on employee
        );
    }

}
