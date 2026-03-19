<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Leave;
use Carbon\Carbon;

class HRCorner extends Component
{
    public $stats = [];
    public $roleDistribution = [];
    public $leaveStats = [];
    public $recentLeaves = [];
    public $upcomingLeaves = [];
    public $pendingLeaves = [];
    public $departmentStats = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->stats = $this->getStats();
        $this->roleDistribution = $this->getRoleDistribution();
        $this->leaveStats = $this->getLeaveStats();
        $this->recentLeaves = $this->getRecentLeaves();
        $this->upcomingLeaves = $this->getUpcomingLeaves();
        $this->pendingLeaves = $this->getPendingLeaves();
        $this->departmentStats = $this->getDepartmentStats();
    }

    /**
     * Get dashboard statistics.
     */
    private function getStats()
    {
        try {
            $totalEmployees = User::count();
            
            // Count users by role
            $staff = User::where('role', 'Staff')->count();
            $hr = User::where('role', 'HR')->count();
            $deptHeads = User::where('role', 'Department_Head')->count();
            $maintenance = User::where('role', 'Maintenance')->count();
            $inspectors = User::where('role', 'Inspector')->count();
            
            // Current employees on leave
            $onLeave = Leave::where('status', 'approved')
                ->whereDate('startdate', '<=', now())
                ->whereDate('enddate', '>=', now())
                ->count();
            
            // New hires this month
            $newHires = User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // Pending leave requests
            $pendingLeaves = Leave::where('status', 'pending')->count();
            
            // Approved leaves this month
            $approvedLeaves = Leave::where('status', 'approved')
                ->whereMonth('created_at', now()->month)
                ->count();
            
            return [
                'total_employees' => $totalEmployees,
                'staff' => $staff,
                'hr' => $hr,
                'dept_heads' => $deptHeads,
                'maintenance' => $maintenance,
                'inspectors' => $inspectors,
                'on_leave' => $onLeave,
                'new_hires' => $newHires,
                'pending_leaves' => $pendingLeaves,
                'approved_leaves' => $approvedLeaves,
            ];
        } catch (\Exception $e) {
            return [
                'total_employees' => 0,
                'staff' => 0,
                'hr' => 0,
                'dept_heads' => 0,
                'maintenance' => 0,
                'inspectors' => 0,
                'on_leave' => 0,
                'new_hires' => 0,
                'pending_leaves' => 0,
                'approved_leaves' => 0,
            ];
        }
    }

    /**
     * Get role distribution data.
     */
    private function getRoleDistribution()
    {
        try {
            $roles = User::select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->get();
            
            $colors = [
                'Staff' => '#3b82f6',
                'HR' => '#10b981',
                'Department_Head' => '#8b5cf6',
                'Maintenance' => '#f59e0b',
                'Inspector' => '#ef4444'
            ];
            
            $labels = [];
            $data = [];
            $colorValues = [];
            
            foreach ($roles as $role) {
                $labels[] = str_replace('_', ' ', $role->role ?? 'Unknown');
                $data[] = $role->total ?? 0;
                $colorValues[] = $colors[$role->role] ?? '#6b7280';
            }
            
            return [
                'labels' => $labels,
                'data' => $data,
                'colors' => $colorValues
            ];
        } catch (\Exception $e) {
            return [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];
        }
    }

    /**
     * Get leave statistics.
     */
    private function getLeaveStats()
    {
        try {
            // Leave by type
            $leaveByType = Leave::select('leavetype', DB::raw('count(*) as total'))
                ->whereYear('created_at', now()->year)
                ->groupBy('leavetype')
                ->get();
            
            // Leave by status
            $leaveByStatus = Leave::select('status', DB::raw('count(*) as total'))
                ->whereYear('created_at', now()->year)
                ->groupBy('status')
                ->get();
            
            // Monthly leave trends (last 6 months)
            $monthlyLeaves = [];
            $months = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');
                
                $monthlyLeaves[] = Leave::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();
            }
            
            // Department wise leave distribution
            $deptLeaves = DB::table('leaves')
                ->join('users', 'leaves.user_id', '=', 'users.id')
                ->select('users.department', DB::raw('count(*) as total'))
                ->whereYear('leaves.created_at', now()->year)
                ->groupBy('users.department')
                ->get();
            
            return [
                'by_type' => [
                    'labels' => $leaveByType->pluck('leavetype')->toArray(),
                    'data' => $leaveByType->pluck('total')->toArray(),
                    'colors' => ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6']
                ],
                'by_status' => [
                    'labels' => $leaveByStatus->pluck('status')->map(function($s) {
                        return $s ? ucfirst($s) : 'Unknown';
                    })->toArray(),
                    'data' => $leaveByStatus->pluck('total')->toArray(),
                ],
                'monthly_trend' => [
                    'labels' => $months,
                    'data' => $monthlyLeaves
                ],
                'by_department' => [
                    'labels' => $deptLeaves->pluck('department')->toArray(),
                    'data' => $deptLeaves->pluck('total')->toArray()
                ]
            ];
        } catch (\Exception $e) {
            return [
                'by_type' => ['labels' => [], 'data' => [], 'colors' => []],
                'by_status' => ['labels' => [], 'data' => []],
                'monthly_trend' => ['labels' => [], 'data' => []],
                'by_department' => ['labels' => [], 'data' => []]
            ];
        }
    }

    /**
     * Get recent leave requests.
     */
    private function getRecentLeaves()
    {
        try {
            $leaves = Leave::with('user')
                ->latest()
                ->take(5)
                ->get();
            
            $result = [];
            foreach ($leaves as $leave) {
                if ($leave->user) {
                    $result[] = [
                        'id' => $leave->id,
                        'employee_name' => $leave->user->name ?? 'Unknown',
                        'employee_number' => $leave->user->employee_number ?? 'N/A',
                        'leavetype' => $leave->leavetype ?? 'Unknown',
                        'department' => $leave->department ?? 'N/A',
                        'startdate' => $leave->startdate ? Carbon::parse($leave->startdate) : now(),
                        'enddate' => $leave->enddate ? Carbon::parse($leave->enddate) : now(),
                        'totaldays' => $leave->totaldays ?? 0,
                        'reason' => $leave->reason ?? '',
                        'status' => $leave->status ?? 'pending',
                        'approved_by' => $leave->approved_by ?? '',
                        'remarks' => $leave->remarks ?? '',
                    ];
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get upcoming leave requests.
     */
    private function getUpcomingLeaves()
    {
        try {
            $leaves = Leave::with('user')
                ->where('status', 'approved')
                ->whereDate('startdate', '>=', now())
                ->whereDate('startdate', '<=', now()->addDays(7))
                ->orderBy('startdate')
                ->take(5)
                ->get();
            
            $result = [];
            foreach ($leaves as $leave) {
                if ($leave->user) {
                    $result[] = [
                        'id' => $leave->id,
                        'employee_name' => $leave->user->name ?? 'Unknown',
                        'employee_number' => $leave->user->employee_number ?? 'N/A',
                        'leavetype' => $leave->leavetype ?? 'Unknown',
                        'department' => $leave->department ?? 'N/A',
                        'startdate' => $leave->startdate ? Carbon::parse($leave->startdate) : now(),
                        'enddate' => $leave->enddate ? Carbon::parse($leave->enddate) : now(),
                        'totaldays' => $leave->totaldays ?? 0,
                    ];
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get pending leave requests.
     */
    private function getPendingLeaves()
    {
        try {
            $leaves = Leave::with('user')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
            
            $result = [];
            foreach ($leaves as $leave) {
                if ($leave->user) {
                    $result[] = [
                        'id' => $leave->id,
                        'employee_name' => $leave->user->name ?? 'Unknown',
                        'employee_number' => $leave->user->employee_number ?? 'N/A',
                        'leavetype' => $leave->leavetype ?? 'Unknown',
                        'department' => $leave->department ?? 'N/A',
                        'startdate' => $leave->startdate ? Carbon::parse($leave->startdate) : now(),
                        'enddate' => $leave->enddate ? Carbon::parse($leave->enddate) : now(),
                        'totaldays' => $leave->totaldays ?? 0,
                        'reason' => $leave->reason ?? '',
                    ];
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get department statistics.
     */
    private function getDepartmentStats()
    {
        try {
            $departments = User::select('department', DB::raw('count(*) as total_employees'))
                ->whereNotNull('department')
                ->groupBy('department')
                ->get();
            
            $result = [];
            foreach ($departments as $dept) {
                $onLeave = Leave::join('users', 'leaves.user_id', '=', 'users.id')
                    ->where('users.department', $dept->department)
                    ->where('leaves.status', 'approved')
                    ->whereDate('leaves.startdate', '<=', now())
                    ->whereDate('leaves.enddate', '>=', now())
                    ->count();
                
                $deptData = new \stdClass();
                $deptData->department = $dept->department;
                $deptData->total_employees = $dept->total_employees;
                $deptData->on_leave = $onLeave;
                $deptData->present = $dept->total_employees - $onLeave;
                $deptData->color = '#' . substr(md5($dept->department ?? 'default'), 0, 6);
                
                $result[] = $deptData;
            }
            
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Approve a leave request.
     */
    public function approveLeave($leaveId)
    {
        try {
            $leave = Leave::find($leaveId);
            if ($leave) {
                $leave->status = 'approved';
                $leave->approved_by = auth()->user()->name ?? 'System';
                $leave->save();
                
                session()->flash('message', 'Leave approved successfully.');
                $this->loadData(); // Refresh data
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error approving leave: ' . $e->getMessage());
        }
    }

    /**
     * Reject a leave request.
     */
    public function rejectLeave($leaveId)
    {
        try {
            $leave = Leave::find($leaveId);
            if ($leave) {
                $leave->status = 'rejected';
                $leave->save();
                
                session()->flash('message', 'Leave rejected successfully.');
                $this->loadData(); // Refresh data
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error rejecting leave: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('pages.HR.hrdashboard')->layout('layouts.app');
    }
}