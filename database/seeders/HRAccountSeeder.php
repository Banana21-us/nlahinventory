<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HRAccountSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the HR department exists
        $deptHR = Department::firstOrCreate(
            ['code' => 'HR'],
            ['name' => 'Human Resources']
        );

        $empNumber = 'HR-2024-001';

        // Create (or update) the HR user — match on email so duplicates never happen
        $user = User::updateOrCreate(
            ['email' => 'rcpanelo@adventisthealth-pan.com'],
            [
                'employee_number' => $empNumber,
                'name' => 'Ryniel Panelo',
                'username' => 'hradmin',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ]
        );

        // Employee profile (biometric / 201 file data)
        Employee::updateOrCreate(
            ['employee_number' => $empNumber],
            [
                'user_id' => $user->id,
                'last_name' => 'Panelo',
                'first_name' => 'Ryniel',
                'middle_name' => '',
                'birth_date' => '1990-05-15',
                'place_of_birth' => 'Sison, Pangasinan',
                'gender' => 'Male',
                'civil_status' => 'Single',
                'citizenship' => 'Filipino',
                'religion' => 'Seventh-day Adventist',
                'blood_type' => 'O+',
                'height' => '170',
                'weight' => '68',
                'mobile_no' => '09171234567',
                'email_add' => 'rcpanelo@adventisthealth-pan.com',
                'p_address' => 'Brgy. Center, Sison, Pangasinan',
                'c_address' => 'Brgy. Center, Sison, Pangasinan',
                'contact_person' => 'Mrs. Panelo',
                'contact_number' => '09281234567',
            ]
        );

        // Employment details — keyed on employee_id (employee table PK)
        $employeeRecord = Employee::where('employee_number', $empNumber)->first();
        EmploymentDetail::updateOrCreate(
            ['employee_id' => $employeeRecord->id],
            [
                'department_id' => $deptHR->id,
                'position' => 'HR Manager',
                'rank' => 'SG-18',
                'employment_status' => 'Regular',
                'hiring_date' => '2020-01-06',
                'regularization_date' => '2020-07-06',
                'license_no' => null,
                'license_expiry' => null,
                're_membership' => false,
                'philhealth_no' => '12-345678901-2',
                'pagibig_no' => '1234-5678-9012',
                'tin_no' => '123-456-789-000',
                'sss_no' => '12-3456789-0',
                'gsis_no' => null,
            ]
        );

        // Make this user the dept head of HR
        $deptHR->update(['dept_head_id' => $user->id]);

        $this->command->info('HR account ready.');
        $this->command->line('  Username : hradmin');
        $this->command->line('  Password : password123');
        $this->command->line('  Email    : rcpanelo@adventisthealth-pan.com');
    }
}
