<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationResultMail;
use App\Mail\LeaveHRResultMail;
use App\Mail\LeaveStatusUpdateMail;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\PayrollAndLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return Leave::with(['user.employmentDetail.department'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('username', 'like', '%'.$this->search.'%')
                        ->orWhere('name', 'like', '%'.$this->search.'%');
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
    public function cancellationCount()
    {
        // Only show cancellations that have passed the dept head stage
        return Leave::where('hr_status', 'cancellation_requested')
            ->where('cancellation_dhead_status', 'approved')
            ->count();
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
            ? Leave::with(['user.employmentDetail.department'])->find($this->selectedLeaveId)
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
            'hr_status' => 'approved',
            'remarks' => $this->hrRemarks,
            'hr_approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        $fresh = $leave->fresh(['user.employmentDetail.department', 'deptHead']);
        $this->notifyEmployee($fresh);
        $this->notifyDeptHead($fresh);

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
            'hr_status' => 'rejected',
            'rejection_reason' => $this->hrRemarks,
            'remarks' => $this->hrRemarks,
        ]);

        $fresh = $leave->fresh(['user.employmentDetail.department', 'deptHead']);
        $this->notifyEmployee($fresh);
        $this->notifyDeptHead($fresh);

        session()->flash('message', 'Leave request rejected.');
        $this->closeModal();
    }

    public function approveCancellation()
    {
        $leave = Leave::findOrFail($this->selectedLeaveId);

        DB::transaction(function () use ($leave) {
            $leave->update([
                'hr_status' => 'cancelled',
                'remarks' => $this->hrRemarks ?: 'Cancellation approved by HR.',
                'approved_by' => Auth::id(),
            ]);

            $this->restoreConsumed($leave->user_id, $leave->leave_type, (float) $leave->total_days);
        });

        $fresh = $leave->fresh(['user.employmentDetail.department', 'deptHead']);
        $this->notifyCancellationResult($fresh);

        session()->flash('message', "Cancellation approved for {$leave->user->name}. Credits have been restored.");
        $this->closeModal();
    }

    private function restoreConsumed(int $userId, string $leaveType, float $days): void
    {
        if ($days <= 0) {
            return;
        }

        // Resolves by code first, then falls back to legacy label strings
        $lt = LeaveType::resolve($leaveType);
        $key = $lt?->getPayrollKey();

        if (! $key) {
            return;
        }

        $payroll = PayrollAndLeave::where('user_id', $userId)->first();

        if (! $payroll) {
            return;
        }

        $payroll->decrement("{$key}_consumed", $days);
    }

    public function rejectCancellation()
    {
        $this->validate([
            'hrRemarks' => 'required|min:5',
        ], [
            'hrRemarks.required' => 'Please explain why the cancellation is denied.',
        ]);

        $leave = Leave::findOrFail($this->selectedLeaveId);

        $leave->update([
            'hr_status' => 'approved',
            'remarks' => $this->hrRemarks,
        ]);

        $fresh = $leave->fresh(['user.employmentDetail.department', 'deptHead']);
        $this->notifyCancellationResult($fresh);

        session()->flash('message', "Cancellation denied for {$leave->user->name}. Leave remains active.");
        $this->closeModal();
    }

    private function notifyCancellationResult(Leave $leave): void
    {
        // Notify the employee (staff or DHead who filed the leave)
        $staffEmail = $leave->user?->email;
        if ($staffEmail) {
            try {
                Mail::to($staffEmail)->send(new LeaveCancellationResultMail($leave, 'staff'));
            } catch (\Exception $e) {
                Log::error('LeaveCancellationResultMail (staff) failed', [
                    'leave_id' => $leave->id,
                    'email' => $staffEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Notify the dept head (FYI — skip if the leave owner IS the dept head)
        $deptHeadEmail = $leave->deptHead?->email;
        if ($deptHeadEmail && $deptHeadEmail !== $staffEmail) {
            try {
                Mail::to($deptHeadEmail)->send(new LeaveCancellationResultMail($leave, 'dhead'));
            } catch (\Exception $e) {
                Log::error('LeaveCancellationResultMail (dhead) failed', [
                    'leave_id' => $leave->id,
                    'email' => $deptHeadEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }
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
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function notifyDeptHead(Leave $leave): void
    {
        $email = $leave->deptHead?->email;
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new LeaveHRResultMail($leave));
        } catch (\Exception $e) {
            Log::error('LeaveHRResultMail failed', [
                'leave_id' => $leave->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('pages.HR.hr-leave-management');
    }
}
