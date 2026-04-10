<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'code'                    => 'SL',
                'label'                   => 'Sick Leave (10-Day)',
                'is_paid'                 => true,
                'annual_days'             => 10,
                'reset_type'              => 'january',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'SL_X',
                'label'                   => 'Extra Sick Leave',
                'is_paid'                 => true,
                'annual_days'             => null,
                'reset_type'              => 'none',
                'requires_attachment'     => true,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'SL_M',
                'label'                   => 'Major Sick Leave',
                'is_paid'                 => true,
                'annual_days'             => null,
                'reset_type'              => 'none',
                'requires_attachment'     => true,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'ML',
                'label'                   => 'Maternity Leave (105 Days)',
                'is_paid'                 => true,
                'annual_days'             => 105,
                'reset_type'              => 'none',
                'requires_attachment'     => true,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'PL',
                'label'                   => 'Paternity Leave (7 Days)',
                'is_paid'                 => true,
                'annual_days'             => 7,
                'reset_type'              => 'none',
                'requires_attachment'     => true,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'VL',
                'label'                   => 'Vacation Leave',
                'is_paid'                 => true,
                'annual_days'             => null,
                'reset_type'              => 'anniversary',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'LWOP',
                'label'                   => 'Leave Without Pay',
                'is_paid'                 => false,
                'annual_days'             => null,
                'reset_type'              => 'none',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'SYL',
                'label'                   => 'Sympathetic/Bereavement',
                'is_paid'                 => true,
                'annual_days'             => null,
                'reset_type'              => 'none',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'SPL',
                'label'                   => 'Single Parent Leave',
                'is_paid'                 => true,
                'annual_days'             => 7,
                'reset_type'              => 'january',
                'requires_attachment'     => false,
                'solo_parent_only'        => true,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'BL',
                'label'                   => 'Birthday Leave',
                'is_paid'                 => true,
                'annual_days'             => 1,
                'reset_type'              => 'birth_month',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => false,
            ],
            [
                'code'                    => 'CAL',
                'label'                   => 'Calamity Leave',
                'is_paid'                 => true,
                'annual_days'             => null,
                'reset_type'              => 'none',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => true,
            ],
            [
                'code'                    => 'STL',
                'label'                   => 'Study Leave',
                'is_paid'                 => true,
                'annual_days'             => null,
                'reset_type'              => 'none',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => true,
            ],
            [
                'code'                    => 'MWL',
                'label'                   => 'Mental Wellness Leave',
                'is_paid'                 => true,
                'annual_days'             => null,
                'reset_type'              => 'none',
                'requires_attachment'     => false,
                'solo_parent_only'        => false,
                'requires_admin_approval' => true,
            ],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
