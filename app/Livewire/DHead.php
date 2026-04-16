<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationRequestMail;
use App\Mail\LeaveDHeadDecisionMail;
use App\Mail\LeaveHRNotificationMail;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\PayrollAndLeave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DHead extends Component
{
    // ─── Leave Entry Form ─────────────────────────────────────────────────────
    public $form = [
        'leave_type' => '', // stores LeaveType code (VL, SL, …)
        'start_date' => '',
        'end_date' => '',
        'day_part' => 'Full',
        'total_days' => 0,
        'reason' => '',
        'reliever' => '',
    ];

    public $attachment = null;

    public float $availableCredits = 0;

    // ─── Search & Modal State ─────────────────────────────────────────────────
    public $search = '';

    public $mySearch = '';

    public $showReviewModal = false;

    public $selectedLeave = null;

    public $remarks = '';

    // ─── Summary Cards ────────────────────────────────────────────────────────
    public $pendingCount = 0;

    public $approvedThisMonth = 0;

    public $onLeaveToday = 0;

    // ─── Validation ───────────────────────────────────────────────────────────
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

    // ─── Internal helpers ─────────────────────────────────────────────────────

    private function resolveFormLeaveType(): ?LeaveType
    {
        $code = $this->form['leave_type'];

        return $code ? LeaveType::resolve($code) : null;
    }

    private function isSoloParent(): bool
    {
        return (bool) DB::table('employee')
            ->where('user_id', Auth::id())
            ->value('is_solo_parent');
    }

    private function getAvailableLeaveTypes(): Collection
    {
        $isSoloParent = $this->isSoloParent();

        // DHead is always regular — no probation filter needed
        return LeaveType::where('is_active', true)
            ->when(! $isSoloParent, fn ($q) => $q->where('solo_parent_only', false))
            ->orderBy('label')
            ->get();
    }

    public function computeAvailableCredits(): float
    {
        $lt = $this->resolveFormLeaveType();

        if (! $lt) {
            return 0;
        }

        if ($lt->isLWOP()) {
            return -1;
        }

        $key = $lt->getPayrollKey();

        if (! $key) {
            return 0;
        }

        $payroll = PayrollAndLeave::where('user_id', Auth::id())->first();

        if (! $payroll) {
            return 0;
        }

        return max(0, ($payroll->{$key.'_total'} ?? 0) - ($payroll->{$key.'_consumed'} ?? 0));
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

    private function adjustConsumed(int $userId, ?LeaveType $lt, float $days, string $direction): void
    {
        if (! $lt || $days <= 0) {
            return;
        }

        $key = $lt->getPayrollKey();

        if (! $key) {
            return;
        }

        $payroll = PayrollAndLeave::where('user_id', $userId)->first();

        if (! $payroll) {
            return;
        }

        if ($direction === 'increment') {
            $payroll->increment("{$key}_consumed", $days);
        } else {
            $payroll->decrement("{$key}_consumed", $days);
        }
    }

    // ─── Submit Own Leave ─────────────────────────────────────────────────────
    public function submitLeave(): void
    {
        $this->validate();

        $lt = $this->resolveFormLeaveType();
        $hasCreditCap = $lt && $lt->getPayrollKey() !== null;

        if ($hasCreditCap && $this->form['total_days'] > $this->availableCredits) {
            $this->addError('form.total_days', "You only have {$this->availableCredits} day(s) remaining for this leave type.");

            return;
        }

        try {
            $attachmentPath = null;
            if ($this->attachment) {
                $attachmentPath = $this->attachment->store('leave-attachments', 'public');
            }

            // DHead bypasses the dept_head approval step — auto-approved, sent straight to HR
            $leave = DB::transaction(function () use ($attachmentPath, $lt) {
                $leave = Leave::create([
                    'user_id' => Auth::id(),
                    'leave_type' => $this->form['leave_type'],
                    'is_paid' => $lt?->is_paid ?? true,
                    'start_date' => $this->form['start_date'],
                    'end_date' => $this->form['end_date'],
                    'total_days' => $this->form['total_days'],
                    'day_part' => $this->form['day_part'],
                    'reason' => $this->form['reason'],
                    'reliever' => $this->form['reliever'] ?: null,
                    'attachment' => $attachmentPath,
                    'date_requested' => now()->toDateString(),
                    'dept_head_status' => 'approved',
                    'dept_head_id' => Auth::id(),
                    'dept_head_approved_at' => now(),
                    'hr_status' => 'pending',
                ]);

                $this->adjustConsumed(Auth::id(), $lt, (float) $this->form['total_days'], 'increment');

                return $leave;
            });

            $this->notifyHR($leave->load('user.employmentDetail.department'));

            $this->form = [
                'leave_type' => '',
                'start_date' => '',
                'end_date' => '',
                'day_part' => 'Full',
                'total_days' => 0,
                'reason' => '',
                'reliever' => '',
            ];
            $this->attachment = null;
            $this->availableCredits = 0;

            session()->flash('message', 'Leave application submitted successfully!');
        } catch (\Exception $e) {
            Log::error('DHead::submitLeave failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    // ─── Cancel / Delete Own Leaves ───────────────────────────────────────────
    public function cancelMyLeave(int $id): void
    {
        $leave = Leave::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($leave->hr_status !== 'pending') {
            session()->flash('error', 'This leave can no longer be deleted at this stage.');

            return;
        }

        DB::transaction(function () use ($leave) {
            $lt = LeaveType::resolve($leave->leave_type);
            $this->adjustConsumed($leave->user_id, $lt, (float) $leave->total_days, 'decrement');
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
            }
        }
    }

    // ─── Review Staff Leaves ──────────────────────────────────────────────────
    public function openReviewModal($id): void
    {
        $this->selectedLeave = Leave::with('user')->findOrFail($id);
        $this->remarks = $this->selectedLeave->dept_head_remarks ?? '';
        $this->showReviewModal = true;
    }

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

        // Always notify the employee of the dept head's decision
        $this->notifyStaff($fresh);

        // Forward to HR when approved
        if ($status === 'approved') {
            $this->notifyHR($fresh);
        }

        $this->reset(['showReviewModal', 'selectedLeave', 'remarks']);
        session()->flash('message', 'Application has been '.$status.' successfully.');
    }

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
            }
        }
    }

    // ─── Render ───────────────────────────────────────────────────────────────
    public function render()
    {
        $leavesQuery = Leave::with('user')
            ->where('user_id', '!=', Auth::id())
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('user', fn ($u) => $u->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhere('leave_type', 'like', '%'.$this->search.'%');
                });
            });

        $leaves = $leavesQuery->latest()->get();

        $myLeaves = Leave::where('user_id', Auth::id())
            ->when($this->mySearch, fn ($q) => $q->where(fn ($sub) => $sub
                ->where('leave_type', 'like', '%'.$this->mySearch.'%')
                ->orWhere('reason', 'like', '%'.$this->mySearch.'%')
            ))
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

        $lt = $this->resolveFormLeaveType();
        $isVL = $lt?->code === 'VL';
        $isSL = in_array($lt?->code, ['SL', 'SL_X', 'SL_M']);
        $isBL = $lt?->code === 'BL';
        $isSPL = $lt?->code === 'SPL';
        $isLWOP = $lt?->isLWOP() ?? false;

        $creditLabel = match (true) {
            $isVL => 'Available VL Credits',
            $isSL => 'Available SL Credits',
            $isBL => 'Available BL Credits',
            $isSPL => 'Available SPL Credits',
            $isLWOP => 'Leave Without Pay',
            default => 'No Credit Cap',
        };

        $leaveTypeOptions = $this->getAvailableLeaveTypes()
            ->map(fn ($t) => ['value' => $t->code, 'label' => $t->label])
            ->values()
            ->toArray();

        $leaveTypeMap = LeaveType::pluck('label', 'code')->toArray();

        return view('pages.users.dhead-leave', [
            'leaves' => $leaves,
            'myLeaves' => $myLeaves,
            'pendingCount' => $this->pendingCount,
            'approvedThisMonth' => $this->approvedThisMonth,
            'onLeaveToday' => $this->onLeaveToday,
            'availableCredits' => $this->availableCredits,
            'creditLabel' => $creditLabel,
            'showCredits' => $isVL || $isSL || $isBL || $isSPL,
            'leaveTypeOptions' => $leaveTypeOptions,
            'leaveTypeMap' => $leaveTypeMap,
        ])->layout('layouts.app');
    }
}
