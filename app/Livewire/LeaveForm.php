<?php

namespace App\Livewire;

use App\Mail\LeaveCancellationRequestMail;
use App\Mail\LeaveRequestMail;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
    protected array $vlTypes = ['Vacation Leave', 'Birthday Leave'];

    protected array $slTypes = ['Sick Leave'];

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
        $userId = Auth::id();

        if (in_array($this->leave_type, $this->slTypes)) {
            return Leave::availableSLCredits($userId);
        }

        if (in_array($this->leave_type, $this->vlTypes)) {
            return Leave::availableVLCredits($userId);
        }

        return 0;
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

        // Enforce credit cap for VL and SL types
        $hasCreditCap = in_array($this->leave_type, $this->vlTypes)
                     || in_array($this->leave_type, $this->slTypes);

        if ($hasCreditCap && $this->total_days > $this->availableCredits) {
            $this->addError('total_days', "You only have {$this->availableCredits} day(s) remaining for this leave type.");

            return;
        }

        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('leave_attachments', 'public');
        }

        $leave = Leave::create([
            'user_id' => auth()->id(),
            'leave_type' => $this->leave_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'day_part' => $this->day_part,
            'total_days' => $this->total_days,
            'reason' => $this->reason,
            'reliever' => $this->reliever ?: null,
            'attachment' => $filePath,
            'date_requested' => now()->toDateString(),
            'dept_head_status' => 'pending',
            'hr_status' => 'pending',
        ]);

        // Notify the department head by email
        $this->notifyDeptHead($leave);

        $this->resetForm();
        session()->flash('message', 'Leave application submitted successfully! Reference #'.$leave->id);
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

        $leave->delete();
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
        $isVL = in_array($this->leave_type, $this->vlTypes);
        $isSL = in_array($this->leave_type, $this->slTypes);

        $leaves = Leave::where('user_id', auth()->id())
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q->where('leave_type', 'like', "%{$this->search}%")
                ->orWhere('reason', 'like', "%{$this->search}%")
            )
            )
            ->latest()
            ->get();

        return view('pages.users.leaveform', [
            'leaves' => $leaves,
            'creditLabel' => $isVL ? 'Available VL Credits' : ($isSL ? 'Available SL Credits' : 'No Credit Cap'),
            'showCredits' => $isVL || $isSL,
        ])->layout('layouts.app');
    }
}
