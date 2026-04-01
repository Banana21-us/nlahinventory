<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Livewire\WithFileUploads;

class LeaveForm extends Component
{
    use WithFileUploads;

    public $showForm = false;
    public $search = '';

    public $leave_type;
    public $start_date;
    public $end_date;
    public $day_part = 'Full';
    public $total_days = 0;
    public $reason;
    public $reliever;
    public $attachment;

    public $availableCredits = 0;
    public $department;

    // Leave types that consume VL credits
    const VL_TYPES = ['Vacation Leave', 'Birthday Leave'];
    const SL_TYPES = ['Sick Leave'];

    protected function rules()
    {
        return [
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:5',
            'day_part'   => 'required|in:Full,AM,PM',
            'reliever'   => 'nullable|string',
            'attachment' => 'nullable|file|max:5120',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->department = $user->department ?? 'N/A';
        $this->availableCredits = $this->computeAvailableCredits();
    }

    /**
     * Compute VL/SL credits based on seniority and consumed leaves.
     * VL: 1–7 yrs = 10 days, 7–15 yrs = 15 days, 15+ yrs = 20 days
     * SL: 3 days regardless of seniority
     */
    public function computeAvailableCredits(): float
{
    $user = Auth::user();

    // Pull hiring_date from employment_details relationship
    $employment = \App\Models\EmploymentDetail::where('user_id', $user->id)->first();
    $hireDate   = $employment?->hiring_date ?? $user->created_at;

    $yearsInService = Carbon::parse($hireDate)->diffInYears(now());

    $vlEntitlement = match(true) {
        $yearsInService >= 15 => 20,
        $yearsInService >= 7  => 15,
        default               => 10,
    };

    $slEntitlement = 3;

    $usedVL = Leave::where('user_id', $user->id)
        ->whereIn('leave_type', self::VL_TYPES)
        ->whereIn('hr_status', ['pending', 'approved'])
        ->whereYear('start_date', now()->year)
        ->sum('total_days');

    $usedSL = Leave::where('user_id', $user->id)
        ->whereIn('leave_type', self::SL_TYPES)
        ->whereIn('hr_status', ['pending', 'approved'])
        ->whereYear('start_date', now()->year)
        ->sum('total_days');

    if (in_array($this->leave_type, self::SL_TYPES)) {
        return max(0, $slEntitlement - $usedSL);
    }

    if (in_array($this->leave_type, self::VL_TYPES)) {
        return max(0, $vlEntitlement - $usedVL);
    }

    return 0;
}

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['start_date', 'end_date', 'day_part'])) {
            $this->calculateTotalDays();
        }

        if ($propertyName === 'leave_type') {
            $this->availableCredits = $this->computeAvailableCredits();
        }
    }

    public function calculateTotalDays()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end   = Carbon::parse($this->end_date);
            $days  = $start->diffInDays($end) + 1;

            $this->total_days = ($this->day_part !== 'Full')
                ? $days - 0.5
                : (float) $days;
        } else {
            $this->total_days = 0;
        }
    }

    public function submit()
    {
        $this->validate();

        // Credit guard for VL/SL types
        if (in_array($this->leave_type, self::VL_TYPES) || in_array($this->leave_type, self::SL_TYPES)) {
            if ($this->total_days > $this->availableCredits) {
                $this->addError('leave_type', "Insufficient leave credits. You only have {$this->availableCredits} day(s) available.");
                return;
            }
        }

        try {
            $filePath = $this->attachment
                ? $this->attachment->store('leave-attachments', 'public')
                : null;

            Leave::create([
                'user_id'          => auth()->id(),
                'leave_type'       => $this->leave_type,
                'start_date'       => $this->start_date,
                'end_date'         => $this->end_date,
                'day_part'         => $this->day_part,
                'total_days'       => $this->total_days,
                'reason'           => $this->reason,
                'reliever'         => $this->reliever,
                'attachment'       => $filePath,
                'date_requested'   => now()->toDateString(),
                'dept_head_status' => 'pending',
                'hr_status'        => 'pending',
            ]);

            $this->reset(['leave_type', 'start_date', 'end_date', 'day_part',
                          'total_days', 'reason', 'reliever', 'attachment']);
            $this->total_days = 0;
            $this->day_part   = 'Full';
            $this->showForm   = false;
            $this->availableCredits = $this->computeAvailableCredits();

            session()->flash('message', 'Leave application submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Leave submission error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        return view('pages.users.leaveform', [
            'leaves' => Leave::where('user_id', auth()->id())
                ->where(function ($q) {
                    $q->where('leave_type', 'like', "%{$this->search}%")
                      ->orWhere('reason',     'like', "%{$this->search}%");
                })
                ->latest()
                ->get(),
            'availableCredits' => $this->availableCredits,
        ]);
    }
}