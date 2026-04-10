<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationRequestMail;
use App\Mail\LeaveDHeadDecisionMail;
use App\Mail\LeaveHRNotificationMail;
use App\Models\Leave;
use App\Models\PayrollAndLeave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DHead extends Component
{
    // ─── Form Properties for Leave Entry ───
    public $form = [
        'leave_type' => '',
        'start_date' => '',
        'end_date' => '',
        'day_part' => 'Full',
        'total_days' => 0,
        'reason' => '',
        'reliever' => '',
    ];

    public $attachment = null;

    public float $availableCredits = 0;

    protected array $vlTypes = ['Vacation Leave'];

    protected array $slTypes = ['Sick Leave'];

    protected array $blTypes = ['Birthday Leave'];

    protected array $splTypes = ['Single Parent Leave'];

    // ─── Search & Modal State ───
    public $search = '';

    public $mySearch = '';

    public $showReviewModal = false;

    public $selectedLeave = null;

    public $remarks = '';

    // ─── Summary Card Properties (computed in render) ───
    public $pendingCount = 0;

    public $approvedThisMonth = 0;

    public $onLeaveToday = 0;

    // ─── Validation Rules ───
    protected $rules = [
        'form.leave_type' => 'required|string',
        'form.start_date' => 'required|date',
        'form.end_date' => 'required|date|after_or_equal:form.start_date',
        'form.reason' => 'required|string|min:5',
        'form.reliever' => 'nullable|string|max:255',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ];

    public function updatedFormLeaveType(): void
    {
        $this->availableCredits = $this->computeAvailableCredits();
    }

    public function updatedFormStartDate(): void
    {
        $this->calculateTotalDays();
    }

    public function updatedFormEndDate(): void
    {
        $this->calculateTotalDays();
    }

    public function updatedFormDayPart(): void
    {
        $this->calculateTotalDays();
    }

    public function computeAvailableCredits(): float
    {
        $userId    = Auth::id();
        $leaveType = $this->form['leave_type'];
        $payroll   = PayrollAndLeave::where('user_id', $userId)->first();

        if (! $payroll) {
            return 0;
        }

        return match (true) {
            in_array($leaveType, $this->slTypes)  => max(0, $payroll->sl_total  - $payroll->sl_consumed),
            in_array($leaveType, $this->vlTypes)  => max(0, $payroll->vl_total  - $payroll->vl_consumed),
            in_array($leaveType, $this->blTypes)  => max(0, $payroll->bl_total  - $payroll->bl_consumed),
            in_array($leaveType, $this->splTypes) => max(0, $payroll->spl_total - $payroll->spl_consumed),
            $leaveType === 'Leave Without Pay'    => -1,
            default                               => 0,
        };
    }

    private function incrementConsumed(int $userId, string $leaveType, float $days): void
    {
        $payroll = PayrollAndLeave::where('user_id', $userId)->first();

        if (! $payroll || $days <= 0) {
            return;
        }

        match (true) {
            in_array($leaveType, $this->slTypes)  => $payroll->increment('sl_consumed', $days),
            in_array($leaveType, $this->vlTypes)  => $payroll->increment('vl_consumed', $days),
            in_array($leaveType, $this->blTypes)  => $payroll->increment('bl_consumed', $days),
            in_array($leaveType, $this->splTypes) => $payroll->increment('spl_consumed', $days),
            default                               => null,
        };
    }

    private function decrementConsumed(int $userId, string $leaveType, float $days): void
    {
        $payroll = PayrollAndLeave::where('user_id', $userId)->first();

        if (! $payroll || $days <= 0) {
            return;
        }

        match (true) {
            in_array($leaveType, $this->slTypes)  => $payroll->decrement('sl_consumed', $days),
            in_array($leaveType, $this->vlTypes)  => $payroll->decrement('vl_consumed', $days),
            in_array($leaveType, $this->blTypes)  => $payroll->decrement('bl_consumed', $days),
            in_array($leaveType, $this->splTypes) => $payroll->decrement('spl_consumed', $days),
            default                               => null,
        };
    }

    protected function calculateTotalDays(): void
    {
        if ($this->form['start_date'] && $this->form['end_date']) {
            $start = Carbon::parse($this->form['start_date']);
            $end = Carbon::parse($this->form['end_date']);

            if ($start <= $end) {
                $days = $start->diffInDays($end) + 1;
                $this->form['total_days'] = $days * ($this->form['day_part'] === 'Full' ? 1 : 0.5);
            } else {
                $this->form['total_days'] = 0;
            }
        } else {
            $this->form['total_days'] = 0;
        }
    }

    // ─── Submit Leave Application (DHead files own leave) ───
    public function submitLeave(): void
    {
        $this->validate();

        // Enforce credit cap for VL, SL, BL, SPL types
        $leaveType = $this->form['leave_type'];
        $hasCreditCap = in_array($leaveType, $this->vlTypes)
            || in_array($leaveType, $this->slTypes)
            || in_array($leaveType, $this->blTypes)
            || in_array($leaveType, $this->splTypes);

        if ($hasCreditCap && $this->form['total_days'] > $this->availableCredits) {
            $this->addError('form.total_days', "You only have {$this->availableCredits} day(s) remaining for this leave type.");

            return;
        }

        try {
            $attachmentPath = null;
            if ($this->attachment) {
                $attachmentPath = $this->attachment->store('leave-attachments', 'public');
            }

            // DHead bypasses the dept_head approval step — auto-approve it
            $leave = DB::transaction(function () use ($attachmentPath) {
                $leave = Leave::create([
                    'user_id'               => Auth::id(),
                    'leave_type'            => $this->form['leave_type'],
                    'start_date'            => $this->form['start_date'],
                    'end_date'              => $this->form['end_date'],
                    'total_days'            => $this->form['total_days'],
                    'day_part'              => $this->form['day_part'],
                    'reason'                => $this->form['reason'],
                    'reliever'              => $this->form['reliever'] ?: null,
                    'attachment'            => $attachmentPath,
                    'date_requested'        => now()->toDateString(),
                    'dept_head_status'      => 'approved',
                    'dept_head_id'          => Auth::id(),
                    'dept_head_approved_at' => now(),
                    'hr_status'             => 'pending',
                ]);

                $this->incrementConsumed(Auth::id(), $this->form['leave_type'], (float) $this->form['total_days']);

                return $leave;
            });

            $this->notifyHR($leave->load('user.employmentDetail.department'));

            $this->form = [
                'leave_type' => '',
                'start_date' => '',
                'end_date'   => '',
                'day_part'   => 'Full',
                'total_days' => 0,
                'reason'     => '',
                'reliever'   => '',
            ];
            $this->attachment     = null;
            $this->availableCredits = 0;

            session()->flash('message', 'Leave application submitted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    // ─── Cancel / Delete Own Leaves ───
    public function cancelMyLeave(int $id): void
    {
        $leave = Leave::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($leave->hr_status !== 'pending') {
            session()->flash('error', 'This leave can no longer be deleted at this stage.');

            return;
        }

        DB::transaction(function () use ($leave) {
            $this->decrementConsumed($leave->user_id, $leave->leave_type, (float) $leave->total_days);
            $leave->delete();
        });

        session()->flash('message', 'Leave application removed.');
    }

    public function requestCancellation(int $id): void
    {
        $leave = Leave::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($leave->hr_status !== 'approved') {
            session()->flash('error', 'Only fully approved leaves can request cancellation.');

            return;
        }

        $leave->update(['hr_status' => 'cancellation_requested']);
        $this->notifyHROfCancellation($leave->fresh(['user.employmentDetail.department']));
        session()->flash('message', 'Cancellation request submitted. HR will review and confirm.');
    }

    private function notifyHROfCancellation(Leave $leave): void
    {
        $hrUsers = User::whereHas('employmentDetail', fn ($q) => $q->where('position', 'HR Manager'))
            ->whereNotNull('email')
            ->get();

        foreach ($hrUsers as $hr) {
            try {
                Mail::to($hr->email)->send(new LeaveCancellationRequestMail($leave));
            } catch (\Exception) {
                // Mail failure must not block the action
            }
        }
    }

    // ─── Open Review Modal ───
    public function openReviewModal($id): void
    {
        $this->selectedLeave = Leave::with('user')->findOrFail($id);
        $this->remarks = $this->selectedLeave->dept_head_remarks ?? '';
        $this->showReviewModal = true;
    }

    // ─── Process Approval/Rejection ───
    public function process(string $status): void
    {
        if (! $this->selectedLeave) {
            return;
        }

        $this->selectedLeave->update([
            'dept_head_status' => $status,
            'dept_head_remarks' => $this->remarks,
            'dept_head_id' => Auth::id(),
            'dept_head_approved_at' => now(),
        ]);

        $fresh = $this->selectedLeave->fresh(['user.employmentDetail.department']);

        // Always notify the staff member of the dept head's decision
        $this->notifyStaff($fresh);

        // If approved, forward to HR for final action
        if ($status === 'approved') {
            $this->notifyHR($fresh);
        }

        $this->reset(['showReviewModal', 'selectedLeave', 'remarks']);
        session()->flash('message', 'Application has been '.$status.' successfully.');
    }

    // ─── Close Modal ───
    public function closeModal(): void
    {
        $this->reset(['showReviewModal', 'selectedLeave', 'remarks']);
    }

    private function notifyStaff(Leave $leave): void
    {
        $email = $leave->user?->email;
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new LeaveDHeadDecisionMail($leave));
        } catch (\Exception $e) {
            Log::error('LeaveDHeadDecisionMail failed', [
                'leave_id' => $leave->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function notifyHR(Leave $leave): void
    {
        $hrUsers = User::whereHas('employmentDetail', fn ($q) => $q->where('position', 'HR Manager'))
            ->whereNotNull('email')
            ->get();

        foreach ($hrUsers as $hr) {
            try {
                Mail::to($hr->email)->send(new LeaveHRNotificationMail($leave));
            } catch (\Exception) {
                // Mail failure must not block the action
            }
        }
    }

    public function render()
    {
        // Incoming leaves for dept head's team — filter to exclude own leaves
        $leavesQuery = Leave::with('user')
            ->where('user_id', '!=', Auth::id())
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('user', fn ($u) => $u->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhere('leave_type', 'like', '%'.$this->search.'%');
                });
            });

        $leaves = $leavesQuery->latest()->get();

        // My own leave requests
        $myLeaves = Leave::where('user_id', Auth::id())
            ->when($this->mySearch, fn ($q) => $q->where(fn ($sub) => $sub->where('leave_type', 'like', '%'.$this->mySearch.'%')
                ->orWhere('reason', 'like', '%'.$this->mySearch.'%')
            )
            )
            ->latest()
            ->get();

        $this->pendingCount = Leave::where('user_id', '!=', Auth::id())->where('dept_head_status', 'pending')->count();
        $this->approvedThisMonth = Leave::where('dept_head_status', 'approved')
            ->whereMonth('dept_head_approved_at', now()->month)
            ->whereYear('dept_head_approved_at', now()->year)
            ->count();

        $today = now()->toDateString();
        $this->onLeaveToday = Leave::where('dept_head_status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $leaveType = $this->form['leave_type'];
        $isVL  = in_array($leaveType, $this->vlTypes);
        $isSL  = in_array($leaveType, $this->slTypes);
        $isBL  = in_array($leaveType, $this->blTypes);
        $isSPL = in_array($leaveType, $this->splTypes);

        $creditLabel = match (true) {
            $isVL  => 'Available VL Credits',
            $isSL  => 'Available SL Credits',
            $isBL  => 'Available BL Credits',
            $isSPL => 'Available SPL Credits',
            default => 'No Credit Cap',
        };

        return view('pages.users.dhead-leave', [
            'leaves'            => $leaves,
            'myLeaves'          => $myLeaves,
            'pendingCount'      => $this->pendingCount,
            'approvedThisMonth' => $this->approvedThisMonth,
            'onLeaveToday'      => $this->onLeaveToday,
            'availableCredits'  => $this->availableCredits,
            'creditLabel'       => $creditLabel,
            'showCredits'       => $isVL || $isSL || $isBL || $isSPL,
        ])->layout('layouts.app');
    }
}
