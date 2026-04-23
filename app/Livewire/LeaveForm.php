<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationDHeadMail;
use App\Mail\LeaveCancellationRequestMail;
use App\Mail\LeaveHRNotificationMail;
use App\Mail\LeaveRequestMail;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use App\Services\LeaveAccrualService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeaveForm extends Component
{
    use WithFileUploads;

    // Form State
    public bool $showForm = false;

    public string $search = '';

    // Form Fields — leave_type now stores the LeaveType code (VL, SL, …)
    public string $leave_type = '';

    public ?string $start_date = null;

    public ?string $end_date = null;

    public string $day_part = 'Full';

    public float $total_days = 0;

    public string $reason = '';

    public $attachment = null;

    public float $availableCredits = 0;

    protected function rules(): array
    {
        return [
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5',
            'day_part' => 'required|in:Full,AM,PM',
            'attachment' => 'nullable|file|max:5120',
        ];
    }

    // ─── Lifecycle ────────────────────────────────────────────────────────────

    public function updatedLeaveType(): void
    {
        $this->total_days = 0;
        $this->availableCredits = $this->computeAvailableCredits();
    }

    public function updatedStartDate(): void
    {
        $this->calculateTotalDays();
    }

    public function updatedEndDate(): void
    {
        $this->calculateTotalDays();
    }

    public function updatedDayPart(): void
    {
        $this->calculateTotalDays();
    }

    // ─── Internal helpers ─────────────────────────────────────────────────────

    private function resolveLeaveType(): ?LeaveType
    {
        return $this->leave_type ? LeaveType::resolve($this->leave_type) : null;
    }

    private function birthdayLeaveWindow(): ?array
    {
        $birthDate = Auth::user()->employee?->birth_date;

        if (! $birthDate) {
            return null;
        }

        $birth = Carbon::parse($birthDate);
        $ref   = $this->start_date ? Carbon::parse($this->start_date) : now();

        $regularizationDate = Auth::user()->employmentDetail?->regularization_date
            ? Carbon::parse(Auth::user()->employmentDetail->regularization_date)
            : null;

        // Birthday in the same year as the reference date; handle Feb 29 gracefully
        $windowStart = Carbon::createSafe($ref->year, $birth->month, $birth->day)
            ?: Carbon::create($ref->year, 3, 1);

        $windowEnd = $windowStart->copy()->addMonths(2);

        // If this year's window ends before the regularization date, advance to next year
        if ($regularizationDate && $windowEnd->lt($regularizationDate)) {
            $windowStart = Carbon::createSafe($ref->year + 1, $birth->month, $birth->day)
                ?: Carbon::create($ref->year + 1, 3, 1);
            $windowEnd = $windowStart->copy()->addMonths(2);
        }

        return [
            'start' => $windowStart,
            'end'   => $windowEnd,
        ];
    }

    private function isEmployeeRegular(): bool
    {
        return Auth::user()->employmentDetail?->regularization_date !== null;
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
        if (! $this->isEmployeeRegular()) {
            return new Collection();
        }

        $isSoloParent = $this->isSoloParent();
        $emp          = $this->employeeRow();
        $gender       = $emp?->gender ?? '';
        $isFemale     = $gender === 'Female';
        $isMarriedMale = $gender === 'Male' && strtolower($emp?->civil_status ?? '') === 'married';

        return LeaveType::where('is_active', true)
            ->when(! $isSoloParent,   fn ($q) => $q->where('solo_parent_only', false))
            ->when(! $isFemale,       fn ($q) => $q->where('code', '!=', 'ML'))
            ->when(! $isMarriedMale,  fn ($q) => $q->where('code', '!=', 'PL'))
            ->orderBy('label')
            ->get();
    }

    // ─── Credits ──────────────────────────────────────────────────────────────

    public function computeAvailableCredits(): float
    {
        $lt = $this->resolveLeaveType();

        if (! $lt) {
            return 0;
        }

        if ($lt->isLWOP()) {
            return -1; // unlimited — no cap
        }

        if (! $lt->getPayrollKey()) {
            return 0; // no tracked balance for this type
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

    // ─── Day Calculation ──────────────────────────────────────────────────────

    public function calculateTotalDays(): void
    {
        if ($this->start_date && $this->end_date) {
            $days = Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;

            $this->total_days = ($this->day_part !== 'Full')
                ? $days - 0.5
                : (float) $days;
        } else {
            $this->total_days = 0;
        }
    }

    // ─── Save ─────────────────────────────────────────────────────────────────

    public function save(): void
    {
        if (! $this->isEmployeeRegular()) {
            session()->flash('error', 'Leave filing is not available during the probationary period.');

            return;
        }

        $this->validate();

        // Gender / marital status guards for ML and PL
        $emp    = $this->employeeRow();
        $gender = $emp?->gender ?? '';

        if ($this->leave_type === 'ML' && $gender !== 'Female') {
            $this->addError('leave_type', 'Maternity Leave is only available to female employees.');

            return;
        }

        if ($this->leave_type === 'PL' && ! ($gender === 'Male' && strtolower($emp?->civil_status ?? '') === 'married')) {
            $this->addError('leave_type', 'Paternity Leave is only available to married male employees.');

            return;
        }

        $lt = $this->resolveLeaveType();
        $hasCreditCap = $lt && $lt->getPayrollKey() !== null;

        if ($hasCreditCap && $this->total_days > $this->availableCredits) {
            $this->addError('total_days', "You only have {$this->availableCredits} day(s) remaining for this leave type.");

            return;
        }

        if ($lt?->code === 'VL') {
            $startYear  = Carbon::parse($this->start_date)->year;
            $usedInYear = $this->getVlUsedInYear($startYear);
            $remaining  = max(0, 20 - $usedInYear);

            if ($this->total_days > $remaining) {
                $this->addError('end_date', "You can only take {$remaining} more VL day(s) in {$startYear}. Unused days carry over to next year.");

                return;
            }
        }

        if ($lt?->code === 'BL') {
            $window = $this->birthdayLeaveWindow();

            if (! $window) {
                $this->addError('leave_type', 'Your birth date is not on record. Please contact HR.');

                return;
            }

            $start = Carbon::parse($this->start_date);
            $regularizationDate = Auth::user()->employmentDetail?->regularization_date
                ? Carbon::parse(Auth::user()->employmentDetail->regularization_date)
                : null;

            if ($regularizationDate && $start->lt($regularizationDate)) {
                $this->addError('start_date', 'Birthday Leave can only be claimed on or after your regularization date ('.$regularizationDate->format('M d, Y').').');

                return;
            }

            if ($start->lt($window['start']) || $start->gt($window['end'])) {
                $this->addError('start_date', "Birthday Leave can only be used on your birthday or within 2 months after. Valid window: {$window['start']->format('M d')} – {$window['end']->format('M d, Y')}.");

                return;
            }
        }

        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('leave_attachments', 'public');
        }

        $leave = DB::transaction(function () use ($filePath, $lt) {
            $leave = Leave::create([
                'user_id' => auth()->id(),
                'leave_type' => $this->leave_type,
                'is_paid' => $lt?->is_paid ?? true,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'day_part' => $this->day_part,
                'total_days' => $this->total_days,
                'reason' => $this->reason,
                'attachment' => $filePath,
                'date_requested' => now()->toDateString(),
                'dept_head_status' => 'pending',
                'hr_status' => 'pending',
            ]);

            $this->adjustConsumed(auth()->id(), $lt, $this->total_days, 'increment');

            return $leave;
        });

        $this->notifyDeptHead($leave);
        $this->resetForm();
        session()->flash('message', 'Leave application submitted successfully! Reference #'.$leave->id);
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

    private function adjustConsumedByRawType(int $userId, string $leaveType, float $days, string $direction): void
    {
        $lt = LeaveType::resolve($leaveType);
        $this->adjustConsumed($userId, $lt, $days, $direction);
    }

    private function notifyDeptHead(Leave $leave): void
    {
        $user = Auth::user()->load('employmentDetail.department.deptHead');
        $deptHead = $user->employmentDetail?->department?->deptHead;

        $loaded = $leave->load('user.employmentDetail.department.deptHead');

        if ($deptHead?->email) {
            try {
                Mail::to($deptHead->email)->send(new LeaveRequestMail($loaded));
            } catch (\Exception) {
            }

            return;
        }

        // No dept head configured — notify HR directly so the leave is not missed
        $hrUsers = User::whereHas('employmentDetail', fn ($q) => $q->where('position', 'HR Manager'))
            ->whereNotNull('email')
            ->get();

        foreach ($hrUsers as $hr) {
            try {
                Mail::to($hr->email)->send(new LeaveHRNotificationMail($loaded));
            } catch (\Exception) {
            }
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'leave_type', 'start_date', 'end_date', 'day_part',
            'total_days', 'reason', 'attachment', 'showForm',
        ]);
        $this->day_part = 'Full';
        $this->total_days = 0;
        $this->availableCredits = 0;
    }

    // ─── Cancel / Delete ──────────────────────────────────────────────────────

    public function deletePending(int $id): void
    {
        $leave = Leave::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($leave->dept_head_status !== 'pending') {
            session()->flash('error', 'This leave has already been reviewed and cannot be deleted.');

            return;
        }

        DB::transaction(function () use ($leave) {
            $this->adjustConsumedByRawType($leave->user_id, $leave->leave_type, (float) $leave->total_days, 'decrement');
            $leave->delete();
        });

        session()->flash('message', 'Leave application deleted.');
    }

    public function cancelLeave(int $id): void
    {
        $leave = Leave::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($leave->dept_head_status !== 'approved' || $leave->hr_status !== 'pending') {
            session()->flash('error', 'This leave cannot be cancelled at this stage.');

            return;
        }

        $leave->update(['hr_status' => 'cancelled']);
        session()->flash('message', 'Leave application cancelled.');
    }

    public function requestCancellation(int $id): void
    {
        $leave = Leave::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($leave->hr_status !== 'approved') {
            session()->flash('error', 'Only fully approved leaves can request cancellation.');

            return;
        }

        $fresh = $leave->fresh(['user.employmentDetail.department.deptHead']);
        $deptHead = $fresh->user?->employmentDetail?->department?->deptHead;

        if ($deptHead?->email) {
            // Two-stage: notify dept head first
            $leave->update([
                'hr_status' => 'cancellation_requested',
                'cancellation_dhead_status' => 'pending',
            ]);
            try {
                Mail::to($deptHead->email)->send(new LeaveCancellationDHeadMail($fresh));
            } catch (\Exception) {
            }
            session()->flash('message', 'Cancellation request submitted. Your Department Head will review it first.');
        } else {
            // No dept head configured — go directly to HR
            $leave->update([
                'hr_status' => 'cancellation_requested',
                'cancellation_dhead_status' => 'approved',
            ]);
            $this->notifyHROfCancellation($fresh);
            session()->flash('message', 'Cancellation request submitted. HR will review and confirm.');
        }
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

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $lt = $this->resolveLeaveType();
        $payrollKey = $lt?->getPayrollKey();
        $isLWOP = $lt?->isLWOP() ?? false;

        $creditLabel = match (true) {
            $isLWOP      => 'Leave Without Pay',
            $payrollKey !== null => 'Available '.strtoupper($payrollKey).' Credits',
            default      => 'No Credit Cap',
        };

        $leaveTypeOptions = $this->getAvailableLeaveTypes()
            ->map(fn ($t) => ['value' => $t->code, 'label' => $t->label])
            ->values()
            ->toArray();

        // Payroll map for badge display (code → label)
        $leaveTypeMap = LeaveType::pluck('label', 'code')->toArray();

        // Probationary status info
        $detail = Auth::user()->employmentDetail;
        $isProbationary = $detail && $detail->regularization_date === null;
        $expectedRegDate = null;
        $daysLeft = null;

        if ($isProbationary && $detail?->hiring_date) {
            $accrual = app(LeaveAccrualService::class);
            $expectedRegDate = $accrual->computeExpectedRegularizationDate($detail->hiring_date);
            $daysLeft = (int) now()->diffInDays($expectedRegDate, false);
        }

        $leaves = Leave::where('user_id', auth()->id())
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('leave_type', 'like', "%{$this->search}%")
                ->orWhere('reason', 'like', "%{$this->search}%")
            ))
            ->latest()
            ->get();

        $blWindow = ($lt?->code === 'BL') ? $this->birthdayLeaveWindow() : null;

        // VL per-year cap: max 20 days per calendar year
        $vlMaxEndDate        = null;
        $vlRemainingThisYear = null;

        if ($lt?->code === 'VL' && $this->start_date) {
            $startYear           = Carbon::parse($this->start_date)->year;
            $usedInYear          = $this->getVlUsedInYear($startYear);
            $vlRemainingThisYear = max(0, 20 - $usedInYear);

            if ($vlRemainingThisYear > 0) {
                $vlMaxEndDate = Carbon::parse($this->start_date)
                    ->addDays($vlRemainingThisYear - 1)
                    ->toDateString();
            }
        }

        return view('pages.users.leaveform', [
            'leaves'              => $leaves,
            'creditLabel'         => $creditLabel,
            'showCredits'         => $payrollKey !== null,
            'leaveTypeOptions'    => $leaveTypeOptions,
            'leaveTypeMap'        => $leaveTypeMap,
            'isProbationary'      => $isProbationary,
            'expectedRegDate'     => $expectedRegDate,
            'daysLeft'            => $daysLeft,
            'blWindow'            => $blWindow,
            'vlMaxEndDate'        => $vlMaxEndDate,
            'vlRemainingThisYear' => $vlRemainingThisYear,
        ])->layout('layouts.app');
    }
}
