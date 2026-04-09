<?php

namespace Database\Seeders;

use App\Models\AccessKey;
use Illuminate\Database\Seeder;

class AccessKeySeeder extends Seeder
{
    public function run(): void
    {
        $keys = [
            [
                'name'        => 'HR Access',
                'description' => 'Full HR dashboard access with payroll. Bypasses all gate checks.',
                'redirect_to' => 'HR.hrdashboard',
                'is_super'    => true,
                'permissions' => ['access-hr-only', 'access-payroll'],
            ],
            [
                'name'        => 'Maintenance Access',
                'description' => 'Housekeeping / maintenance checklist dashboard.',
                'redirect_to' => 'Maintenance.dashboard',
                'is_super'    => false,
                'permissions' => ['access-maintenance'],
            ],
            [
                'name'        => 'Inspector Access',
                'description' => 'Maintenance verification / inspector view.',
                'redirect_to' => 'Maintenance.checklist.verify',
                'is_super'    => false,
                'permissions' => ['access-verify'],
            ],
            [
                'name'        => 'Cashier Access',
                'description' => 'Point-of-sale system.',
                'redirect_to' => 'pos.dashboard',
                'is_super'    => false,
                'permissions' => ['access-cashier-only'],
            ],
            [
                'name'        => 'Staff Access',
                'description' => 'General staff: leave form, medical mission.',
                'redirect_to' => 'users.leaveform',
                'is_super'    => false,
                'permissions' => ['access-medical'],
            ],
            [
                'name'        => 'Department Head Access',
                'description' => 'Department head leave approval view.',
                'redirect_to' => 'users.leaveform',
                'is_super'    => false,
                'permissions' => ['access-medical'],
            ],
        ];

        foreach ($keys as $key) {
            AccessKey::updateOrCreate(
                ['name' => $key['name']],
                $key
            );
        }
    }
}
