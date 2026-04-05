<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MaintenanceDashboard extends Component
{
    public bool $showModal = false;

    public string $modalPeriod = '';

    public $modalRows = null;

    public function openModal(string $period): void
    {
        if (! in_array($period, ['daily', 'nightly', 'weekly', 'monthly'], true)) {
            return;
        }

        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        $weekStart = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $weekEnd = $now->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $monthStart = $now->copy()->startOfMonth()->toDateString();
        $monthEnd = $now->copy()->endOfMonth()->toDateString();

        $this->modalPeriod = $period;
        $this->showModal = true;

        $this->modalRows = DB::table('location_area_parts as lap')
            ->join('area_parts as ap', 'ap.id', '=', 'lap.area_part_id')
            ->join('locations as l', 'l.id', '=', 'lap.location_id')
            ->leftJoin('records as r', function ($join) use ($period, $today, $weekStart, $weekEnd, $monthStart, $monthEnd) {
                $join->on('r.location_area_part_id', '=', 'lap.id')
                    ->where('r.period_type', '=', $period);
                if (in_array($period, ['daily', 'nightly'])) {
                    $join->where('r.cleaning_date', '=', $today);
                } elseif ($period === 'weekly') {
                    $join->where('r.cleaning_date', '>=', $weekStart)
                        ->where('r.cleaning_date', '<=', $weekEnd);
                } elseif ($period === 'monthly') {
                    $join->where('r.cleaning_date', '>=', $monthStart)
                        ->where('r.cleaning_date', '<=', $monthEnd);
                }
            })
            ->where('lap.frequency', '=', $period)
            ->select(
                'ap.name as part_name',
                'l.name as location_name',
                'l.floor as location_floor',
                'r.maintenance_name',
                'r.status',
                'r.verifier_status',
                'r.shift',
                'r.cleaning_date',
            )
            ->orderBy('l.name')
            ->orderBy('ap.name')
            ->orderBy('r.shift')
            ->get();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalRows = null;
        $this->modalPeriod = '';
    }

    public function render()
    {
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        $weekStart = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $weekEnd = $now->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $monthStart = $now->copy()->startOfMonth()->toDateString();
        $monthEnd = $now->copy()->endOfMonth()->toDateString();

        // Totals from master list (how many parts exist per period)
        $dailyTotal = DB::table('location_area_parts')->where('frequency', 'daily')->count();
        $nightlyTotal = DB::table('location_area_parts')->where('frequency', 'nightly')->count();
        $weeklyTotal = DB::table('location_area_parts')->where('frequency', 'weekly')->count();
        $monthlyTotal = DB::table('location_area_parts')->where('frequency', 'monthly')->count();

        // Done = distinct parts with at least one YES record for the period (team-wide)
        $dailyDone = DB::table('records')
            ->where('period_type', 'daily')->where('cleaning_date', $today)->where('status', 'YES')
            ->distinct()->count('location_area_part_id');

        $nightlyDone = DB::table('records')
            ->where('period_type', 'nightly')->where('cleaning_date', $today)->where('status', 'YES')
            ->distinct()->count('location_area_part_id');

        $weeklyDone = DB::table('records')
            ->where('period_type', 'weekly')->whereBetween('cleaning_date', [$weekStart, $weekEnd])->where('status', 'YES')
            ->distinct()->count('location_area_part_id');

        $monthlyDone = DB::table('records')
            ->where('period_type', 'monthly')->whereBetween('cleaning_date', [$monthStart, $monthEnd])->where('status', 'YES')
            ->distinct()->count('location_area_part_id');

        // Team recent activity — last 10 records from anyone
        $recentRecords = DB::table('records')
            ->join('location_area_parts', 'records.location_area_part_id', '=', 'location_area_parts.id')
            ->join('locations', 'location_area_parts.location_id', '=', 'locations.id')
            ->join('area_parts', 'location_area_parts.area_part_id', '=', 'area_parts.id')
            ->select(
                'records.cleaning_date',
                'records.shift',
                'records.period_type',
                'records.status',
                'records.verifier_status',
                'records.maintenance_name',
                'records.maintenance_comments',
                'area_parts.name as part_name',
                'locations.name as location_name',
                'locations.floor as location_floor',
            )
            ->whereNotNull('records.maintenance_name')
            ->orderByDesc('records.cleaning_date')
            ->limit(10)
            ->get();

        return view('pages.Maintenance.dashboard', compact(
            'dailyTotal', 'dailyDone',
            'nightlyTotal', 'nightlyDone',
            'weeklyTotal', 'weeklyDone',
            'monthlyTotal', 'monthlyDone',
            'recentRecords',
        ))->layout('layouts.app');
    }
}
