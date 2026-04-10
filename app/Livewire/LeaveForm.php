<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationRequestMail;
use App\Mail\LeaveRequestMail;
use App\Models\Leave;
use App\Models\PayrollAndLeave;
use App\Models\User;
use Carbon\Carbon;
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

    // Form Fields
    public string $leave_type = '';

    public ?string $start_date = null;

    public ?string $end_date = null;

    public string $day_part = 'Full';

    public float $total_days = 0;

    public string $reason = '';

    public string $reliever = '';

    public $attachment = null;

    // Leave type groups
    protected array $vlTypes = ['Vacation Leave'];

    protected array $slTypes = ['Sick Leave'];

    protected array $blTypes = ['Birthday Leave'];

    protected array $splTypes = ['Single Parent Leave'];

    protected function rules(): array
    {
        return [
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5',
            'day_part' => 'required|in:Full,AM,PM',
            'reliever' => 'nullable|string|max:255',
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

    // ─── Credits ──────────────────────────────────────────────────────────────

    public float $availableCredits = 0;

    public function computeAvailableCredits(): float
    {
        $userId  = Auth::id();
        $payroll = PayrollAndLeave::where('user_id', $userId)->first();

        if (! $payroll) {
            return 0;
        }

        return match (true) {
            in_array($this->leave_type, $this->slTypes)  => max(0, $payroll->sl_total  - $payroll->sl_consumed),
            in_array($this->leave_type, $this->vlTypes)  => max(0, $payroll->vl_total  - $payroll->vl_consumed),
            in_array($this->leave_type, $this->blTypes)  => max(0, $payroll->bl_total  - $payroll->bl_consumed),
            in_array($this->leave_type, $this->splTypes) => max(0, $payroll->spl_total - $payroll->spl_consumed),
            $this->leave_type === 'Leave Without Pay'    => -1,
            default                                      => 0,
        };
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

        $hasCreditCap = in_array($this->leave_type, $this->vlTypes)
            || in_array($this->leave_type, $this->slTypes)
            || in_array($this->leave_type, $this->blTypes)
            || in_array($this->leave_type, $this->splTypes);

        if ($hasCreditCap && $this->total_days > $this->availableCredits) {
            $this->addError('total_days', "You only have {$this->availableCredits} day(s) remaining for this leave type.");

            return;
        }

        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('leave_attachments', 'public');
        }

        $leave = DB::transaction(function () use ($filePath) {
            $leave = Leave::create([
                'user_id'          => auth()->id(),
                'leave_type'       => $this->leave_type,
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

            $this->incrementConsumed(auth()->id(), $this->leave_type, $this->total_days);

            return $leave;
        });

        $this->notifyDeptHead($leave);
        $this->resetForm();
        session()->flash('message', 'Leave application submitted successfully! Reference #'.$leave->id);
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

    private function notifyDeptHead(Leave $leave): void
    {
        $user = Auth::user()->load('employmentDetail.department.deptHead');
        $deptHead = $user->employmentDetail?->department?->deptHead;

        if (! $deptHead?->email) {
            return; // No dept head configured — skip silently
        }

        try {
            Mail::to($deptHead->email)->send(new LeaveRequestMail($leave->load('user.employmentDetail.department')));
        } catch (\Exception) {
            // Mail failure must not break the leave submission
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'leave_type', 'start_date', 'end_date', 'day_part',
            'total_days', 'reason', 'reliever', 'attachment', 'showForm',
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
            $this->decrementConsumed($leave->user_id, $leave->leave_type, (float) $leave->total_days);
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
                // Mail failure must not block the action
            }
        }
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $isVL  = in_array($this->leave_type, $this->vlTypes);
        $isSL  = in_array($this->leave_type, $this->slTypes);
        $isBL  = in_array($this->leave_type, $this->blTypes);
        $isSPL = in_array($this->leave_type, $this->splTypes);

        $creditLabel = match (true) {
            $isVL  => 'Available VL Credits',
            $isSL  => 'Available SL Credits',
            $isBL  => 'Available BL Credits',
            $isSPL => 'Available SPL Credits',
            default => 'No Credit Cap',
        };

        $leaves = Leave::where('user_id', auth()->id())
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q->where('leave_type', 'like', "%{$this->search}%")
                ->orWhere('reason', 'like', "%{$this->search}%")
            )
            )
            ->latest()
            ->get();

        return view('pages.users.leaveform', [
            'leaves'      => $leaves,
            'creditLabel' => $creditLabel,
            'showCredits' => $isVL || $isSL || $isBL || $isSPL,
        ])->layout('layouts.app');
    }
}
