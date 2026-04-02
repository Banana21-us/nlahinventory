<?php

namespace App\Livewire;

use App\Mail\LeaveStatusUpdateMail;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Component;

class HrLeaveManagement extends Component
{
    // Filter & Search States
    public $search = '';
    public $statusFilter = 'all';

    // Modal & Review States
    public $selectedLeaveId = null;
    public $hrRemarks = '';
    public $isReviewing = false;

    #[Computed]
    public function leaves()
    {
        return Leave::with(['user.department'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('username', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('hr_status', $this->statusFilter);
            })
            ->latest()
            ->get();
    }

    #[Computed]
    public function pendingCount()
    {
        return Leave::where('hr_status', 'pending')->count();
    }

    #[Computed]
    public function approvedTodayCount()
    {
        return Leave::where('hr_status', 'approved')
            ->whereDate('hr_approved_at', now())
            ->count();
    }

    public function viewDetails($id)
    {
        $this->selectedLeaveId = $id;
        $leave = Leave::find($id);
        $this->hrRemarks = $leave->remarks ?? '';
        $this->isReviewing = true;
    }

    #[Computed]
    public function selectedLeave()
    {
        return $this->selectedLeaveId
            ? Leave::with(['user.department', 'user.employmentDetail'])->find($this->selectedLeaveId)
            : null;
    }

    public function closeModal()
    {
        $this->isReviewing = false;
        $this->reset(['selectedLeaveId', 'hrRemarks']);
    }

    public function approve()
    {
        $leave = Leave::findOrFail($this->selectedLeaveId);

        $leave->update([
            'hr_status'      => 'approved',
            'remarks'        => $this->hrRemarks,
            'hr_approved_at' => now(),
            'approved_by'    => Auth::id(),
        ]);

        $this->notifyEmployee($leave->fresh(['user.department']));

        session()->flash('message', "Leave for {$leave->user->name} has been approved.");
        $this->closeModal();
    }

    public function reject()
    {
        $this->validate([
            'hrRemarks' => 'required|min:5',
        ], [
            'hrRemarks.required' => 'Please provide a reason for the rejection.',
        ]);

        $leave = Leave::findOrFail($this->selectedLeaveId);

        $leave->update([
            'hr_status'        => 'rejected',
            'rejection_reason' => $this->hrRemarks,
            'remarks'          => $this->hrRemarks,
        ]);

        $this->notifyEmployee($leave->fresh(['user.department']));

        session()->flash('message', 'Leave request rejected.');
        $this->closeModal();
    }

    private function notifyEmployee(Leave $leave): void
    {
        $email = $leave->user?->email;
        if (! $email) {
            Log::warning('LeaveStatusUpdateMail: user has no email', ['leave_id' => $leave->id]);
            return;
        }

        try {
            Mail::to($email)->send(new LeaveStatusUpdateMail($leave));
        } catch (\Exception $e) {
            Log::error('LeaveStatusUpdateMail failed', [
                'leave_id' => $leave->id,
                'email'    => $email,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('pages.HR.hr-leave-management');
    }
}
