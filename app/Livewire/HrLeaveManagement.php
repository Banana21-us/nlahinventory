<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationResultMail;
use App\Mail\LeaveHRResultMail;
use App\Mail\LeaveStatusUpdateMail;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\PayoffCreditConsumption;
use App\Models\PayoffLeaveCredit;
use App\Models\User;
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

    // Balance Modal States
    public $showBalanceModal = false;

    public $balanceUserId = null;

    public $balanceName = '';

    public $balanceYear;

    public function mount(): void
    {
        $this->balanceYear = now()->year;
    }

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
        return Leave::where('cancellation_status', 'dhead_approved')->count();
    }

    #[Computed]
    public function approvedTodayCount()
    {
        return Leave::where('hr_status', 'approved')
            ->whereDate('hr_approved_at', now())
            ->count();
    }

    public function openBalanceModal(int $userId, string $name): void
    {
        $this->balanceUserId = $userId;
        $this->balanceName = $name;
        $this->showBalanceModal = true;
    }

    public function closeBalanceModal(): void
    {
        $this->showBalanceModal = false;
        $this->balanceUserId = null;
        $this->balanceName = '';
    }

    #[Computed]
    public function balanceData(): array
    {
        if (! $this->balanceUserId) {
            return [];
        }

        $balances = LeaveBalance::with('leaveType')
            ->where('user_id', $this->balanceUserId)
            ->whereNull('deleted_at')
            ->get();

        // Approved leaves in the selected year for this user
        $yearLeaves = Leave::where('user_id', $this->balanceUserId)
            ->where('hr_status', 'approved')
            ->whereYear('start_date', $this->balanceYear)
            ->get();

        // Group year-leaves by canonical leave_type_id
        $yearConsumed = [];
        foreach ($yearLeaves as $leave) {
            $lt = LeaveType::resolve($leave->leave_type);
            if (! $lt) {
                continue;
            }
            $canonical = $lt->getCanonicalLeaveType();
            if (! $canonical) {
                continue;
            }
            $yearConsumed[$canonical->id] = ($yearConsumed[$canonical->id] ?? 0) + (float) $leave->total_days;
        }

        $result = [];
        foreach ($balances as $balance) {
            $lt = $balance->leaveType;
            if (! $lt) {
                continue;
            }
            $result[] = [
                'label'         => $lt->label,
                'total'         => (float) $balance->total,
                'consumed_year' => $yearConsumed[$lt->id] ?? 0,
                'consumed_all'  => (float) $balance->consumed,
                'remaining'     => max(0, (float) $balance->total - (float) $balance->consumed),
            ];
        }

        return $result;
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
        $staffNotified = $this->notifyEmployee($fresh);
        $this->notifyDeptHead($fresh);

        if (! $staffNotified) {
            session()->flash('warning', "Leave approved but the notification email to {$leave->user->name} could not be sent. Please inform them manually.");
        } else {
            session()->flash('message', "Leave for {$leave->user->name} has been approved.");
        }
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

        DB::transaction(function () use ($leave) {
            $leave->update([
                'hr_status' => 'rejected',
                'rejection_reason' => $this->hrRemarks,
                'remarks' => $this->hrRemarks,
            ]);

            $this->restoreConsumed($leave->user_id, $leave->leave_type, (float) $leave->total_days);
        });

        $fresh = $leave->fresh(['user.employmentDetail.department', 'deptHead']);
        $staffNotified = $this->notifyEmployee($fresh);
        $this->notifyDeptHead($fresh);

        if (! $staffNotified) {
            session()->flash('warning', "Leave rejected but the notification email to {$leave->user->name} could not be sent. Please inform them manually.");
        } else {
            session()->flash('message', 'Leave request rejected.');
        }
        $this->closeModal();
    }

    public function approveCancellation()
    {
        $leave = Leave::findOrFail($this->selectedLeaveId);

        DB::transaction(function () use ($leave) {
            $leave->update([
                'cancellation_status' => 'cancelled',
                'hr_status' => 'cancelled',
                'dept_head_status' => 'cancelled',
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

        $lt = LeaveType::resolve($leaveType);

        if (! $lt) {
            return;
        }

        // POL: restore FIFO credits from the consumption trail
        if ($lt->isPOL()) {
            // Leave ID is not directly available here — resolve via the leave record
            $leave = Leave::where('user_id', $userId)
                ->where('leave_type', $leaveType)
                ->where('total_days', $days)
                ->where('hr_status', 'rejected')
                ->latest()
                ->first();

            if ($leave) {
                $map = PayoffCreditConsumption::where('leave_id', $leave->id)
                    ->pluck('hours_consumed', 'payoff_leave_credit_id')
                    ->toArray();
                PayoffLeaveCredit::restoreFromMap($map);
            }

            return;
        }

        if (! $lt->getPayrollKey()) {
            return;
        }

        $canonical = $lt->getCanonicalLeaveType();

        if (! $canonical) {
            return;
        }

        $balance = LeaveBalance::where('user_id', $userId)
            ->where('leave_type_id', $canonical->id)
            ->first();

        if (! $balance) {
            return;
        }

        $balance->update(['consumed' => max(0, (float) $balance->consumed - $days)]);
    }

    public function rejectCancellation()
    {
        $this->validate([
            'hrRemarks' => 'nullable|string',
        ]);

        $leave = Leave::findOrFail($this->selectedLeaveId);

        $leave->update([
            'cancellation_status' => 'hr_rejected',
            'remarks' => $this->hrRemarks ?: 'Cancellation denied by HR.',
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
                Mail::to($staffEmail)->queue(new LeaveCancellationResultMail($leave, 'staff'));
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
                Mail::to($deptHeadEmail)->queue(new LeaveCancellationResultMail($leave, 'dhead'));
            } catch (\Exception $e) {
                Log::error('LeaveCancellationResultMail (dhead) failed', [
                    'leave_id' => $leave->id,
                    'email' => $deptHeadEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function notifyEmployee(Leave $leave): bool
    {
        $email = $leave->user?->email;

        if (! $email) {
            Log::warning('LeaveStatusUpdateMail: user has no email', ['leave_id' => $leave->id]);

            return false;
        }

        try {
            Mail::to($email)->queue(new LeaveStatusUpdateMail($leave));

            return true;
        } catch (\Exception $e) {
            Log::error('LeaveStatusUpdateMail failed', [
                'leave_id' => $leave->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function notifyDeptHead(Leave $leave): bool
    {
        $email = $leave->deptHead?->email
            ?? $leave->user?->employmentDetail?->department?->deptHead?->email;

        if (! $email) {
            return false;
        }

        try {
            Mail::to($email)->queue(new LeaveHRResultMail($leave));

            return true;
        } catch (\Exception $e) {
            Log::error('LeaveHRResultMail failed', [
                'leave_id' => $leave->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function render()
    {
        return view('pages.HR.hr-leave-management');
    }
}
