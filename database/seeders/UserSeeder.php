<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'employee_number' => 'EMP-001',
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'role' => 'HR',
            ],
            [
                'employee_number' => 'EMP-002',
                'name' => 'John Staff',
                'email' => 'staff@example.com',
                'role' => 'Staff',
            ],
            [
                'employee_number' => 'EMP-003',
                'name' => 'Jane Head',
                'email' => 'head@example.com',
                'role' => 'Department_Head',
            ],
            [
                'employee_number' => 'EMP-004',
                'name' => 'Mike Tech',
                'email' => 'maintenance@example.com',
                'role' => 'Maintenance',
            ],
            [
                'employee_number' => 'EMP-005',
                'name' => 'Sarah Inspector',
                'email' => 'inspector@example.com',
                'role' => 'Inspector',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'employee_number'   => $user['employee_number'],
                'name'              => $user['name'],
                'email'             => $user['email'],
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'), // Default password
                'role'              => $user['role'],
                'remember_token'    => Str::random(10),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}