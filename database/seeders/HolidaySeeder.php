<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        // Last Monday of August
        $lastMondayAugust = Carbon::create($year, 8, 31)->startOfMonth()->modify('last Monday of August');

        $holidays = [
            // ── Regular holidays ──────────────────────────────────────────────
            [
                'name'         => "New Year's Day",
                'date'         => Carbon::create($year, 1, 1)->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'Araw ng Kagitingan',
                'date'         => Carbon::create($year, 4, 9)->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => 'Day of Valor',
            ],
            [
                'name'         => 'Labor Day',
                'date'         => Carbon::create($year, 5, 1)->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'Independence Day',
                'date'         => Carbon::create($year, 6, 12)->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'National Heroes Day',
                'date'         => $lastMondayAugust->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => 'Last Monday of August',
            ],
            [
                'name'         => 'Bonifacio Day',
                'date'         => Carbon::create($year, 11, 30)->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'Christmas Day',
                'date'         => Carbon::create($year, 12, 25)->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'Rizal Day',
                'date'         => Carbon::create($year, 12, 30)->toDateString(),
                'type'         => 'regular',
                'is_recurring' => true,
                'remarks'      => null,
            ],

            // ── Special non-working holidays ──────────────────────────────────
            [
                'name'         => 'Ninoy Aquino Day',
                'date'         => Carbon::create($year, 8, 21)->toDateString(),
                'type'         => 'special_non_working',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'All Saints Day',
                'date'         => Carbon::create($year, 11, 1)->toDateString(),
                'type'         => 'special_non_working',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'All Souls Day',
                'date'         => Carbon::create($year, 11, 2)->toDateString(),
                'type'         => 'special_non_working',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'Immaculate Conception',
                'date'         => Carbon::create($year, 12, 8)->toDateString(),
                'type'         => 'special_non_working',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => 'Christmas Eve',
                'date'         => Carbon::create($year, 12, 24)->toDateString(),
                'type'         => 'special_non_working',
                'is_recurring' => true,
                'remarks'      => null,
            ],
            [
                'name'         => "New Year's Eve",
                'date'         => Carbon::create($year, 12, 31)->toDateString(),
                'type'         => 'special_non_working',
                'is_recurring' => true,
                'remarks'      => null,
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['name' => $holiday['name'], 'date' => $holiday['date']],
                $holiday
            );
        }
    }
}
