<?php

namespace App\Livewire;

use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HRCorner extends Component
{
    public function render()
    {
        $today = now()->toDateString();

        // ── KPI Cards ──────────────────────────────────────────────────────────
        $totalEmployees = User::whereNotIn('role', ['Disable'])->count();

        $onLeaveToday = Leave::where('hr_status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $pendingHR = Leave::where('hr_status', 'pending')->count();

        $approvedThisMonth = Leave::where('hr_status', 'approved')
            ->whereMonth('hr_approved_at', now()->month)
            ->whereYear('hr_approved_at', now()->year)
            ->count();

        $newHiresThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ── Religion Distribution (from employee table) ─────────────────────
        $religionRows = DB::table('employee')
            ->select('religion', DB::raw('count(*) as total'))
            ->whereNotNull('religion')
            ->where('religion', '!=', '')
            ->groupBy('religion')
            ->orderByDesc('total')
            ->get();

        $totalWithReligion = $religionRows->sum('total');

        // ── Employment Status ───────────────────────────────────────────────
        $employmentStatus = DB::table('employment_details')
            ->select('employment_status', DB::raw('count(*) as total'))
            ->groupBy('employment_status')
            ->orderByDesc('total')
            ->get();

        $totalEmploymentDetails = $employmentStatus->sum('total');

        // ── Role Breakdown ──────────────────────────────────────────────────
        $roleBreakdown = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->get();

        // ── Department Headcount ────────────────────────────────────────────
        $departments = DB::table('departments')
            ->leftJoin('users', 'departments.id', '=', 'users.department_id')
            ->select('departments.name', 'departments.code', DB::raw('count(users.id) as total'))
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderByDesc('total')
            ->get();

        // ── Pending Leaves (HR needs to action) ────────────────────────────
        $pendingLeaves = Leave::with('user.department')
            ->where('hr_status', 'pending')
            ->where('dept_head_status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        // ── Recent Leave Activity ───────────────────────────────────────────
        $recentLeaves = Leave::with('user.department')
            ->latest()
            ->take(7)
            ->get();

        // ── Upcoming Approved Leaves (next 7 days) ──────────────────────────
        $upcomingLeaves = Leave::with('user.department')
            ->where('hr_status', 'approved')
            ->whereDate('start_date', '>=', $today)
            ->whereDate('start_date', '<=', now()->addDays(7)->toDateString())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // ── Leave by Type (this year) ───────────────────────────────────────
        $leaveByType = Leave::select('leave_type', DB::raw('count(*) as total'))
            ->whereYear('created_at', now()->year)
            ->whereIn('hr_status', ['approved', 'pending'])
            ->groupBy('leave_type')
            ->orderByDesc('total')
            ->get();

        $totalLeavesByType = max($leaveByType->sum('total'), 1);

        return view('pages.HR.hrdashboard', compact(
            'totalEmployees',
            'onLeaveToday',
            'pendingHR',
            'approvedThisMonth',
            'newHiresThisMonth',
            'religionRows',
            'totalWithReligion',
            'employmentStatus',
            'totalEmploymentDetails',
            'roleBreakdown',
            'departments',
            'pendingLeaves',
            'recentLeaves',
            'upcomingLeaves',
            'leaveByType',
            'totalLeavesByType',
        ));
    }
}
