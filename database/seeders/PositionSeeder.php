<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            // Administration / HR
            ['name' => 'HR Manager',         'code' => 'HR-MGR'],
            ['name' => 'HR Staff',            'code' => 'HR-STF'],
            ['name' => 'Department Head',     'code' => 'DEPT-HD'],
            ['name' => 'Bookkeeper',          'code' => 'BKPR'],
            ['name' => 'Finance Officer',     'code' => 'FIN-OFF'],
            ['name' => 'IT Staff',            'code' => 'IT-STF'],

            // Clinical / Medical
            ['name' => 'Doctor',              'code' => 'DR'],
            ['name' => 'Nurse',               'code' => 'RN'],
            ['name' => 'Pharmacist',          'code' => 'PHARM'],
            ['name' => 'Medical Technologist','code' => 'MED-TECH'],
            ['name' => 'Radiologic Tech',     'code' => 'RAD-TECH'],
            ['name' => 'Staff',               'code' => 'STF'],

            // Maintenance / Facilities
            ['name' => 'Housekeeping',        'code' => 'HSKP'],
            ['name' => 'Maintenance Head',    'code' => 'MNT-HD'],
            ['name' => 'Maintenance Staff',   'code' => 'MNT-STF'],
            ['name' => 'Inspector',           'code' => 'INSP'],

            // Point of Sale
            ['name' => 'Cashier',             'code' => 'CASH'],

            // Support
            ['name' => 'Security Guard',      'code' => 'SEC'],
            ['name' => 'Driver',              'code' => 'DRV'],
            ['name' => 'Cook',                'code' => 'COOK'],
            ['name' => 'Utility Worker',      'code' => 'UTL'],
        ];

        foreach ($positions as $pos) {
            Position::firstOrCreate(['name' => $pos['name']], $pos);
        }
    }
}
