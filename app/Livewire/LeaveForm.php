<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationRequestMail;
use App\Mail\LeaveHRNotificationMail;
use App\Mail\LeaveRequestMail;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\PayrollAndLeave;
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

    public string $reliever = '';

    public $attachment = null;

    public float $availableCredits = 0;

    protected function rules(): array
    {
        return [
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:5',
            'day_part'   => 'required|in:Full,AM,PM',
            'reliever'   => 'nullable|string|max:255',
            'attachment' => 'nullable|file|max:5120',
        ];
    }

    // ─── Lifecycle ────────────────────────────────────────────────────────────

    public function updatedLeaveType(): void
    {
        $this->total_days       = 0;
        $this->availableCredits = $this->computeAvailableCredits();
    }

    public function updatedStartDate(): void { $this->calculateTotalDays(); }

    public function updatedEndDate(): void { $this->calculateTotalDays(); }

    public function updatedDayPart(): void { $this->calculateTotalDays(); }

    // ─── Internal helpers ─────────────────────────────────────────────────────

    private function resolveLeaveType(): ?LeaveType
    {
        return $this->leave_type ? LeaveType::resolve($this->leave_type) : null;
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

    private function getAvailableLeaveTypes(): Collection
    {
        $isSoloParent = $this->isSoloParent();
        $isRegular    = $this->isEmployeeRegular();

        return LeaveType::where('is_active', true)
            ->when(! $isSoloParent, fn ($q) => $q->where('solo_parent_only', false))
            ->orderBy('label')
            ->get()
            ->filter(function (LeaveType $lt) use ($isRegular) {
                // Probationary employees cannot take VL, SL, or BL
                if (! $isRegular && in_array($lt->code, ['VL', 'SL', 'SL_X', 'SL_M', 'BL'])) {
                    return false;
                }

                return true;
            });
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

        $key = $lt->getPayrollKey();

        if (! $key) {
            return 0; // no payroll tracking for this type
        }

        $payroll = PayrollAndLeave::where('user_id', Auth::id())->first();

        if (! $payroll) {
            return 0;
        }

        return max(0, ($payroll->{$key.'_total'} ?? 0) - ($payroll->{$key.'_consumed'} ?? 0));
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
        $this->validate();

        $lt           = $this->resolveLeaveType();
        $hasCreditCap = $lt && $lt->getPayrollKey() !== null;

        if ($hasCreditCap && $this->total_days > $this->availableCredits) {
            $this->addError('total_days', "You only have {$this->availableCredits} day(s) remaining for this leave type.");

            return;
        }

        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('leave_attachments', 'public');
        }

        $leave = DB::transaction(function () use ($filePath, $lt) {
            $leave = Leave::create([
                'user_id'          => auth()->id(),
                'leave_type'       => $this->leave_type,
                'is_paid'          => $lt?->is_paid ?? true,
                'start_date'       => $this->start_date,
                'end_date'         => $this->end_date,
                'day_part'         => $this->day_part,
                'total_days'       => $this->total_days,
                'reason'           => $this->reason,
                'reliever'         => $this->reliever ?: null,
                'attachment'       => $filePath,
                'date_requested'   => now()->toDateString(),
                'dept_head_status' => 'pending',
                'hr_status'        => 'pending',
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

    private function adjustConsumedByRawType(int $userId, string $leaveType, float $days, string $direction): void
    {
        $lt = LeaveType::resolve($leaveType);
        $this->adjustConsumed($userId, $lt, $days, $direction);
    }

    private function notifyDeptHead(Leave $leave): void
    {
        $user     = Auth::user()->load('employmentDetail.department.deptHead');
        $deptHead = $user->employmentDetail?->department?->deptHead;

        $loaded = $leave->load('user.employmentDetail.department');

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
            'total_days', 'reason', 'reliever', 'attachment', 'showForm',
        ]);
        $this->day_part         = 'Full';
        $this->total_days       = 0;
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

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $lt     = $this->resolveLeaveType();
        $isVL   = $lt?->code === 'VL';
        $isSL   = in_array($lt?->code, ['SL', 'SL_X', 'SL_M']);
        $isBL   = $lt?->code === 'BL';
        $isSPL  = $lt?->code === 'SPL';
        $isLWOP = $lt?->isLWOP() ?? false;

        $creditLabel = match (true) {
            $isVL   => 'Available VL Credits',
            $isSL   => 'Available SL Credits',
            $isBL   => 'Available BL Credits',
            $isSPL  => 'Available SPL Credits',
            $isLWOP => 'Leave Without Pay',
            default => 'No Credit Cap',
        };

        $leaveTypeOptions = $this->getAvailableLeaveTypes()
            ->map(fn ($t) => ['value' => $t->code, 'label' => $t->label])
            ->values()
            ->toArray();

        // Payroll map for badge display (code → label)
        $leaveTypeMap = LeaveType::pluck('label', 'code')->toArray();

        // Probationary status info
        $detail         = Auth::user()->employmentDetail;
        $isProbationary = $detail && $detail->regularization_date === null;
        $expectedRegDate = null;
        $daysLeft        = null;

        if ($isProbationary && $detail?->hiring_date) {
            $accrual         = app(LeaveAccrualService::class);
            $expectedRegDate = $accrual->computeExpectedRegularizationDate($detail->hiring_date);
            $daysLeft        = (int) now()->diffInDays($expectedRegDate, false);
        }

        $leaves = Leave::where('user_id', auth()->id())
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('leave_type', 'like', "%{$this->search}%")
                ->orWhere('reason', 'like', "%{$this->search}%")
            ))
            ->latest()
            ->get();

        return view('pages.users.leaveform', [
            'leaves'           => $leaves,
            'creditLabel'      => $creditLabel,
            'showCredits'      => $isVL || $isSL || $isBL || $isSPL,
            'leaveTypeOptions' => $leaveTypeOptions,
            'leaveTypeMap'     => $leaveTypeMap,
            'isProbationary'   => $isProbationary,
            'expectedRegDate'  => $expectedRegDate,
            'daysLeft'         => $daysLeft,
        ])->layout('layouts.app');
    }
}
