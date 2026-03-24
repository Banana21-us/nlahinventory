<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HRLeaveApplications extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $department = '';
    public $leaveType = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    
    // Sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Leave action modals
    public $showViewModal = false;
    public $showActionModal = false;
    public $selectedLeave = null;
    public $actionRemarks = '';
    public $actionType = ''; // 'approved' or 'rejected'

    protected $queryString = ['search', 'department', 'leaveType', 'status', 'dateFrom', 'dateTo'];

    public function mount()
    {
        // Initialize with default values if needed
    }

    public function render()
    {
        $leaves = $this->getLeaves();
        $departments = $this->getDepartments();
        $leaveTypes = $this->getLeaveTypes();
        $stats = $this->getStats();

        return view('pages.HR.leave-applications', [
            'leaves' => $leaves,
            'departments' => $departments,
            'leaveTypes' => $leaveTypes,
            'stats' => $stats
        ]);
    }

    private function getLeaves()
    {
        return Leave::query()
            ->with('user') // Eager load user relationship
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                })
                ->orWhere('reason', 'like', '%' . $this->search . '%');
            })
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->when($this->leaveType, function ($query) {
                $query->where('leavetype', $this->leaveType);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('startdate', '>=', Carbon::parse($this->dateFrom));
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('enddate', '<=', Carbon::parse($this->dateTo));
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    private function getDepartments()
    {
        return Leave::distinct('department')
            ->whereNotNull('department')
            ->pluck('department')
            ->toArray();
    }

    private function getLeaveTypes()
    {
        return Leave::distinct('leavetype')
            ->whereNotNull('leavetype')
            ->pluck('leavetype')
            ->toArray();
    }

    private function getStats()
    {
        try {
            return [
                'total' => Leave::count(),
                'pending' => Leave::where('status', 'pending')->count(),
                'approved' => Leave::where('status', 'approved')->count(),
                'rejected' => Leave::where('status', 'rejected')->count(),
                'total_days' => Leave::sum('totaldays'),
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
                'total_days' => 0,
            ];
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewLeave($leaveId)
    {
        $this->selectedLeave = Leave::with(['user', 'approver'])->find($leaveId);
        $this->showViewModal = true;
    }

    public function openActionModal($leaveId, $action)
    {
        $this->selectedLeave = Leave::find($leaveId);
        $this->actionType = $action;
        $this->actionRemarks = '';
        $this->showActionModal = true;
    }

    public function processLeaveAction()
    {
        if (!$this->selectedLeave) {
            session()->flash('error', 'Leave request not found.');
            $this->closeModal();
            return;
        }

        try {
            DB::beginTransaction();
            
            $this->selectedLeave->status = $this->actionType;
            $this->selectedLeave->approved_by = auth()->user()->id; // Store user ID, not name
            $this->selectedLeave->remarks = $this->actionRemarks;
            $this->selectedLeave->save();

            DB::commit();

            session()->flash('message', 'Leave ' . $this->actionType . 'd successfully.');
            
            $this->closeModal();
            $this->resetPage(); // Refresh pagination
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error processing leave: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showViewModal = false;
        $this->showActionModal = false;
        $this->selectedLeave = null;
        $this->actionRemarks = '';
        $this->actionType = '';
    }

    public function resetFilters()
    {
        $this->reset(['search', 'department', 'leaveType', 'status', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}