<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Seed positions and access keys first (referenced by other seeders)
        $this->call([
            PositionSeeder::class,
            AccessKeySeeder::class,
        ]);

        // 1. Create Departments
        $deptMIS = Department::create([
            'name' => 'Management Information Systems',
            'code' => 'MIS',
        ]);

        $deptNursing = Department::create([
            'name' => 'Nursing Service',
            'code' => 'NSG',
        ]);

        // 2. Define specialized test users
        $roles = [
            [
                'role' => 'HR',
                'prefix' => 'HR',
                'first' => 'Glen',
                'last' => 'Lozada',
                'email' => 'glenlozada0@gmail.com',
                'dept' => $deptMIS,
                'pos' => 'HR Manager',
            ],
            [
                'role' => 'Staff', // This will be our Dept Head for Nursing
                'prefix' => 'HEAD',
                'first' => 'Sarah',
                'last' => 'Chief',
                'email' => 'head@example.com',
                'dept' => $deptNursing,
                'pos' => 'Chief Nurse',
            ],
            [
                'role' => 'Staff', // This is the Nurse
                'prefix' => 'NRS',
                'first' => 'Juan',
                'last' => 'Dela Cruz',
                'email' => 'lozada.glen@yahoo.com',
                'dept' => $deptNursing,
                'pos' => 'Staff Nurse',
            ],
            [
                'role' => 'Maintenance',
                'prefix' => 'MNT',
                'first' => 'Pedro',
                'last' => 'Penduko',
                'email' => 'gatrasis930@gmail.com',
                'dept' => $deptMIS,
                'pos' => 'Maintenance Lead',
            ],
        ];

        foreach ($roles as $index => $data) {
            $empNumber = $data['prefix'].'-2026-'.str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            // Create the User
            $user = User::create([
                'employee_number' => $empNumber,
                'name' => $data['first'].' '.$data['last'],
                'username' => strtolower($data['prefix']), // Using prefix as username (e.g., hr, nrs)
                'email' => $data['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ]);

            // Create the Employee Profile
            $emp = Employee::create([
                'employee_number' => $empNumber,
                'user_id' => $user->id,
                'first_name' => $data['first'],
                'last_name' => $data['last'],
                'birth_date' => '1990-05-15',
                'gender' => ($data['prefix'] === 'HEAD') ? 'Female' : 'Male',
                'citizenship' => 'Filipino',
                'email_add' => $data['email'],
            ]);

            // Create Employment Details — keyed on employee_id (employee table PK)
            EmploymentDetail::create([
                'employee_id' => $emp->id,
                'department_id' => $data['dept']->id,
                'position' => $data['pos'],
                'employment_status' => 'Regular',
                'hiring_date' => '2024-01-15',
                'philhealth_no' => '12-345678901-2',
                'pagibig_no' => '1234-5678-9012',
                'tin_no' => '123-456-789-000',
                'sss_no' => '12-3456789-0',
            ]);

            // Logic to assign Dept Heads
            if ($data['prefix'] === 'HR') {
                $deptMIS->update(['dept_head_id' => $user->id]);
            }

            if ($data['prefix'] === 'HEAD') {
                $deptNursing->update(['dept_head_id' => $user->id]);
            }
        }
    }
}
