<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Important: Match this to your 'desc employee' table name
    protected $table = 'employee'; 

    protected $fillable = [
        'employee_number', 'user_id', 'first_name', 'last_name', 
        'birth_date', 'gender', 'citizenship', 'email_add', 
        'p_address', 'mobile_no'
    ];
}