<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationDHeadDecisionMail;
use App\Mail\LeaveCancellationRequestMail;
use App\Mail\LeaveDHeadDecisionMail;
use App\Mail\LeaveHRNotificationMail;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
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

    public $cancellationPendingCount = 0;

    // ─── Validation ───────────────────────────────────────────────────────────
    protected $rules = [
        'form.leave_type' => 'required|string',
        'form.start_date' => 'required|date',
        'form.end_date' => 'required|date|after_or_equal:form.start_date',
        'form.reason' => 'required|string|min:5',
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

    private function birthdayLeaveWindow(): ?array
    {
        $birthDate = Auth::user()->employee?->birth_date;

        if (! $birthDate) {
            return null;
        }

        $birth = Carbon::parse($birthDate);
        $ref = $this->form['start_date'] ? Carbon::parse($this->form['start_date']) : now();

        $windowStart = Carbon::createSafe($ref->year, $birth->month, $birth->day)
            ?: Carbon::create($ref->year, 3, 1);

        $windowEnd = $windowStart->copy()->addMonths(2);

        return ['start' => $windowStart, 'end' => $windowEnd];
    }

    private function isSoloParent(): bool
    {
        return (bool) DB::table('employee')
            ->where('user_id', Auth::id())
            ->value('is_solo_parent');
    }

    private function employeeRow(): ?object
    {
        return DB::table('employee')->where('user_id', Auth::id())->first();
    }

    private function getAvailableLeaveTypes(): Collection
    {
        $isSoloParent = $this->isSoloParent();
        $emp = $this->employeeRow();
        $gender = $emp?->gender ?? '';
        $isFemale = $gender === 'Female';
        $isMarriedMale = $gender === 'Male' && strtolower($emp?->civil_status ?? '') === 'married';

        // DHead is always regular — no probation filter needed
        return LeaveType::where('is_active', true)
            ->when(! $isSoloParent, fn ($q) => $q->where('solo_parent_only', false))
            ->when(! $isFemale, fn ($q) => $q->where('code', '!=', 'ML'))
            ->when(! $isMarriedMale, fn ($q) => $q->where('code', '!=', 'PL'))
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

        if (! $lt->getPayrollKey()) {
            return 0;
        }

        $canonical = $lt->getCanonicalLeaveType();

        if (! $canonical) {
            return 0;
        }

        $balance = LeaveBalance::where('user_id', Auth::id())
            ->where('leave_type_id', $canonical->id)
            ->first();

        return max(0, ((float) ($balance?->total ?? 0)) - ((float) ($balance?->consumed ?? 0)));
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
        if (! $lt || $days <= 0 || ! $lt->getPayrollKey()) {
            return;
        }

        $canonical = $lt->getCanonicalLeaveType();

        if (! $canonical) {
            return;
        }

        $balance = LeaveBalance::firstOrCreate(
            ['user_id' => $userId, 'leave_type_id' => $canonical->id],
            ['total' => 0, 'consumed' => 0],
        );

        if ($direction === 'increment') {
            $balance->increment('consumed', $days);
        } else {
            $balance->update(['consumed' => max(0, (float) $balance->consumed - $days)]);
        }
    }

    // ─── Submit Own Leave ─────────────────────────────────────────────────────
    public function submitLeave(): void
    {
        $this->validate();

        // Gender / marital status guards for ML and PL
        $emp = $this->employeeRow();
        $gender = $emp?->gender ?? '';

        if ($this->form['leave_type'] === 'ML' && $gender !== 'Female') {
            $this->addError('form.leave_type', 'Maternity Leave is only available to female employees.');

            return;
        }

        if ($this->form['leave_type'] === 'PL' && ! ($gender === 'Male' && strtolower($emp?->civil_status ?? '') === 'married')) {
            $this->addError('form.leave_type', 'Paternity Leave is only available to married male employees.');

            return;
        }

        $lt = $this->resolveFormLeaveType();
        $hasCreditCap = $lt && $lt->getPayrollKey() !== null;

        if ($hasCreditCap && $this->form['total_days'] > $this->availableCredits) {
            $this->addError('form.total_days', "You only have {$this->availableCredits} day(s) remaining for this leave type.");

            return;
        }

        if ($lt?->code === 'VL') {
            $startYear = Carbon::parse($this->form['start_date'])->year;
            $usedInYear = $this->getVlUsedInYear($startYear);
            $remaining = max(0, 20 - $usedInYear);

            if ($this->form['total_days'] > $remaining) {
                $this->addError('form.end_date', "You can only take {$remaining} more VL day(s) in {$startYear}. Unused days carry over to next year.");

                return;
            }
        }

        if ($lt?->code === 'BL') {
            $window = $this->birthdayLeaveWindow();

            if (! $window) {
                $this->addError('form.leave_type', 'Your birth date is not on record. Please contact HR.');

                return;
            }

            $start = Carbon::parse($this->form['start_date']);

            if ($start->lt($window['start']) || $start->gt($window['end'])) {
                $this->addError('form.start_date', "Birthday Leave can only be used on your birthday or within 2 months after. Valid window: {$window['start']->format('M d')} – {$window['end']->format('M d, Y')}.");

                return;
            }
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

        // DHead's own leaves bypass the dept head step — mark as already dept-head-approved
        $leave->update([
            'hr_status' => 'cancellation_requested',
            'cancellation_dhead_status' => 'approved',
        ]);
        $this->notifyHROfCancellation($leave->fresh(['user.employmentDetail.department']));
        session()->flash('message', 'Cancellation request submitted. HR will review and confirm.');
    }

    private function getVlUsedInYear(int $year): float
    {
        return (float) Leave::where('user_id', Auth::id())
            ->where('leave_type', 'VL')
            ->whereYear('start_date', $year)
            ->whereNotIn('hr_status', ['rejected', 'cancelled'])
            ->whereNotIn('dept_head_status', ['rejected'])
            ->sum('total_days');
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

    // ─── Cancellation Review (staff requests to cancel approved leaves) ───────

    public function approveCancellationRequest(int $id): void
    {
        $leave = Leave::with('user.employmentDetail.department')
            ->where('hr_status', 'cancellation_requested')
            ->where('cancellation_dhead_status', 'pending')
            ->findOrFail($id);

        $leave->update(['cancellation_dhead_status' => 'approved']);

        // Notify staff: forwarded to HR
        if ($leave->user?->email) {
            try {
                Mail::to($leave->user->email)->send(new LeaveCancellationDHeadDecisionMail($leave, 'approved'));
            } catch (\Exception $e) {
                Log::error('LeaveCancellationDHeadDecisionMail failed', ['leave_id' => $leave->id, 'error' => $e->getMessage()]);
            }
        }

        // Notify HR for final approval
        $hrUsers = User::whereHas('employmentDetail', fn ($q) => $q->where('position', 'HR Manager'))
            ->whereNotNull('email')
            ->get();
        foreach ($hrUsers as $hr) {
            try {
                Mail::to($hr->email)->send(new LeaveCancellationRequestMail($leave));
            } catch (\Exception) {
            }
        }

        session()->flash('message', 'Cancellation approved and forwarded to HR.');
    }

    public function rejectCancellationRequest(int $id): void
    {
        $leave = Leave::with('user.employmentDetail.department')
            ->where('hr_status', 'cancellation_requested')
            ->where('cancellation_dhead_status', 'pending')
            ->findOrFail($id);

        $leave->update([
            'cancellation_dhead_status' => 'rejected',
            'hr_status' => 'approved', // restore — leave is still active
        ]);

        // Notify staff: denied
        if ($leave->user?->email) {
            try {
                Mail::to($leave->user->email)->send(new LeaveCancellationDHeadDecisionMail($leave, 'rejected'));
            } catch (\Exception $e) {
                Log::error('LeaveCancellationDHeadDecisionMail (rejected) failed', ['leave_id' => $leave->id, 'error' => $e->getMessage()]);
            }
        }

        session()->flash('message', 'Cancellation request denied. Leave remains active.');
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

        $this->cancellationPendingCount = Leave::where('user_id', '!=', Auth::id())
            ->where('hr_status', 'cancellation_requested')
            ->where('cancellation_dhead_status', 'pending')
            ->count();

        $lt = $this->resolveFormLeaveType();
        $payrollKey = $lt?->getPayrollKey();
        $isLWOP = $lt?->isLWOP() ?? false;

        $creditLabel = match (true) {
            $isLWOP => 'Leave Without Pay',
            $payrollKey !== null => 'Available '.strtoupper($payrollKey).' Credits',
            default => 'No Credit Cap',
        };

        $leaveTypeOptions = $this->getAvailableLeaveTypes()
            ->map(fn ($t) => ['value' => $t->code, 'label' => $t->label])
            ->values()
            ->toArray();

        $leaveTypeMap = LeaveType::pluck('label', 'code')->toArray();

        $cancellationLeaves = Leave::with('user.employmentDetail.department')
            ->where('user_id', '!=', Auth::id())
            ->where('hr_status', 'cancellation_requested')
            ->where('cancellation_dhead_status', 'pending')
            ->latest()
            ->get();

        // VL per-year cap
        $vlMaxEndDate = null;
        $vlRemainingThisYear = null;

        if ($lt?->code === 'VL' && ! empty($this->form['start_date'])) {
            $startYear = Carbon::parse($this->form['start_date'])->year;
            $usedInYear = $this->getVlUsedInYear($startYear);
            $vlRemainingThisYear = max(0, 20 - $usedInYear);

            if ($vlRemainingThisYear > 0) {
                $vlMaxEndDate = Carbon::parse($this->form['start_date'])
                    ->addDays($vlRemainingThisYear - 1)
                    ->toDateString();
            }
        }

        return view('pages.users.dhead-leave', [
            'leaves' => $leaves,
            'myLeaves' => $myLeaves,
            'cancellationLeaves' => $cancellationLeaves,
            'pendingCount' => $this->pendingCount,
            'cancellationPendingCount' => $this->cancellationPendingCount,
            'approvedThisMonth' => $this->approvedThisMonth,
            'onLeaveToday' => $this->onLeaveToday,
            'availableCredits' => $this->availableCredits,
            'creditLabel' => $creditLabel,
            'showCredits' => $payrollKey !== null,
            'blWindow' => ($lt?->code === 'BL') ? $this->birthdayLeaveWindow() : null,
            'leaveTypeOptions' => $leaveTypeOptions,
            'leaveTypeMap' => $leaveTypeMap,
            'vlMaxEndDate' => $vlMaxEndDate,
            'vlRemainingThisYear' => $vlRemainingThisYear,
        ])->layout('layouts.app');
    }
}
