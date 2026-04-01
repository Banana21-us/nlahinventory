<?php

namespace App\Livewire;

use App\Models\Leave; // Ensure your model name is correct
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class HrLeaveManagement extends Component
{
    use WithPagination;

    // Filter & Search States
    public $search = '';
    public $statusFilter = 'all';
    
    // Modal & Review States
    public $selectedLeaveId = null;
    public $hrRemarks = '';
    public $isReviewing = false;

    /**
     * Get the filtered list of leave applications.
     * Access in Blade via: $this->leaves
     */
    #[Computed]
    public function leaves()
    {
        return Leave::with('user.employee')
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('username', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function($query) {
                $query->where('hr_status', $this->statusFilter);
            })
            ->latest()
            ->get();
    }

    /**
     * Dashboard Stats
     */
    #[Computed]
    public function pendingCount()
    {
        return Leave::where('hr_status', 'pending')->count();
    }

    #[Computed]
    public function approvedTodayCount()
    {
        return Leave::where('hr_status', 'approved')
            ->whereDate('updated_at', now())
            ->count();
    }

    /**
     * Opens the review slide-over/modal
     */
    public function viewDetails($id)
    {
        $this->selectedLeaveId = $id;
        $leave = Leave::find($id);
        $this->hrRemarks = $leave->hr_remarks; // Pre-fill if exists
        $this->isReviewing = true;
    }

    /**
     * Computed property for the single leave being reviewed
     */
    #[Computed]
    public function selectedLeave()
    {
        return $this->selectedLeaveId ? Leave::with('user.employee')->find($this->selectedLeaveId) : null;
    }

    public function closeModal()
    {
        $this->isReviewing = false;
        $this->reset(['selectedLeaveId', 'hrRemarks']);
    }

    /**
     * HR Approval Logic
     */
    public function approve()
    {
        $leave = Leave::findOrFail($this->selectedLeaveId);
        
        $leave->update([
            'hr_status' => 'approved',
            'hr_remarks' => $this->hrRemarks,
            'approved_by_hr_at' => now(),
        ]);

        session()->flash('message', "Leave for {$leave->user->username} has been approved.");
        $this->closeModal();
    }

    /**
     * HR Rejection Logic
     */
    public function reject()
    {
        $this->validate([
            'hrRemarks' => 'required|min:5' // Good practice to require a reason for rejection
        ], [
            'hrRemarks.required' => 'Please provide a reason for the rejection.'
        ]);

        $leave = Leave::findOrFail($this->selectedLeaveId);
        
        $leave->update([
            'hr_status' => 'rejected',
            'hr_remarks' => $this->hrRemarks,
        ]);

        session()->flash('message', "Leave request rejected.");
        $this->closeModal();
    }

    public function render()
    {
        return view('pages.HR.hr-leave-management');
    }
}