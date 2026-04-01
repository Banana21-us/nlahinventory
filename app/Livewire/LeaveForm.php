<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Livewire\WithFileUploads;

class LeaveForm extends Component
{
    use WithFileUploads;

    // Form State
    public $showForm = false;
    public $search   = '';

    // Form Fields — leave_type defaults to '' not null
    public string $leave_type = '';
    public $start_date;
    public $end_date;
    public $day_part   = 'Full';
    public $total_days = 0;
    public $reason;
    public $reliever;
    public $attachment;

    // User Info
    public $department;
    public $availableCredits = 0;

    // Leave type groups
    protected array $vlTypes = ['Vacation Leave', 'Birthday Leave'];
    protected array $slTypes = ['Sick Leave'];

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

        $this->department       = $user->department ?? 'N/A';
        $this->availableCredits = $this->computeAvailableCredits();

        Log::info('[LeaveForm] mount() complete', [
            'user_id'          => $user->id,
            'department'       => $this->department,
            'availableCredits' => $this->availableCredits,
            'reliever_col'     => Schema::hasColumn('leaves', 'reliever') ? 'YES' : 'NO',
        ]);
        Log::info('Leaves table columns', [
            'columns' => Schema::getColumnListing('leaves')
        ]);
    }

    // ─── Lifecycle Hooks ──────────────────────────────────────────────────────────

    public function updatedLeaveType(string $value): void
    {
        Log::info('[LeaveForm] updatedLeaveType() fired', ['value' => $value]);
        $this->availableCredits = $this->computeAvailableCredits();
        Log::info('[LeaveForm] credits after type change', ['credits' => $this->availableCredits]);
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

    // ─── Credits Calculation ──────────────────────────────────────────────────────

    public function computeAvailableCredits(): float
    {
        $user = Auth::user();

        $emp      = \DB::table('employment_details')->where('user_id', $user->id)->first();
        $hireDate = $emp?->hiring_date ?? $user->created_at;

        $yearsInService = Carbon::parse($hireDate)->diffInYears(now());

        $vlEntitlement = match(true) {
            $yearsInService >= 15 => 20,
            $yearsInService >= 7  => 15,
            default               => 10,
        };
        $slEntitlement = 3;

        $usedVL = Leave::where('user_id', $user->id)
            ->whereIn('leave_type', $this->vlTypes)
            ->whereIn('hr_status', ['pending', 'approved'])
            ->whereYear('start_date', now()->year)
            ->sum('total_days');

        $usedSL = Leave::where('user_id', $user->id)
            ->whereIn('leave_type', $this->slTypes)
            ->whereIn('hr_status', ['pending', 'approved'])
            ->whereYear('start_date', now()->year)
            ->sum('total_days');

        Log::info('[LeaveForm] computeAvailableCredits()', [
            'leave_type'     => $this->leave_type,
            'years'          => round($yearsInService, 2),
            'vl_entitlement' => $vlEntitlement,
            'usedVL'         => $usedVL,
            'usedSL'         => $usedSL,
        ]);

        if (in_array($this->leave_type, $this->slTypes)) {
            return max(0, $slEntitlement - $usedSL);
        }

        if (in_array($this->leave_type, $this->vlTypes)) {
            return max(0, $vlEntitlement - $usedVL);
        }

        return 0;
    }

    // ─── Day Calculation ──────────────────────────────────────────────────────────

    public function calculateTotalDays(): void
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

        Log::info('[LeaveForm] calculateTotalDays()', ['total_days' => $this->total_days]);
    }

    // ─── Form Submit ──────────────────────────────────────────────────────────────

  public function save(): void
{   
    \Log::info('[LeaveForm] about to save', [
    'data' => [
        'user_id'    => auth()->id(),
        'leave_type' => $this->leave_type,
        'start_date' => $this->start_date,
        'total_days' => $this->total_days,
    ]
]);
    try {
        // 1. Validate
        $this->validate();

        // 2. Handle File Upload (SAFE)
        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('leave_attachments', 'public');
        }

        // 3. Save to DB (IMPORTANT: assign to variable)
        $leave = Leave::create([
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

        // 4. Success message
        session()->flash('message', 'Leave application submitted successfully! ID: ' . $leave->id);

        // 5. Reset form
        $this->reset([
            'leave_type',
            'start_date',
            'end_date',
            'reason',
            'reliever',
            'attachment'
        ]);

        $this->showForm = false;
        $this->availableCredits = $this->computeAvailableCredits();

        Log::info('[LeaveForm] saved successfully', [
            'leave_id' => $leave->id
        ]);

    } catch (\Exception $e) {
        Log::error('[LeaveForm] save failed', [
            'error' => $e->getMessage()
        ]);

        session()->flash('message', 'Error: ' . $e->getMessage());
    }
}

    public function setLeaveType(string $value): void
{
    Log::info('[LeaveForm] setLeaveType() called', ['value' => $value]);
    $this->leave_type       = $value;
    $this->availableCredits = $this->computeAvailableCredits();
    Log::info('[LeaveForm] setLeaveType() done', [
        'leave_type'       => $this->leave_type,
        'availableCredits' => $this->availableCredits,
    ]);
}

    // ─── Render ───────────────────────────────────────────────────────────────────

    public function render()
    {
        $isVL = in_array($this->leave_type, $this->vlTypes);
        $isSL = in_array($this->leave_type, $this->slTypes);

        return view('pages.users.leaveform', [
            'leaves' => Leave::where('user_id', auth()->id())
                ->where(function ($query) {
                    $query->where('leave_type', 'like', "%{$this->search}%")
                          ->orWhere('reason',     'like', "%{$this->search}%");
                })
                ->latest()
                ->get(),
            'availableCredits' => $this->availableCredits,
            'creditLabel'      => $isVL ? 'Available VL Credits' : ($isSL ? 'Available SL Credits' : 'No Credit Cap'),
            'showCredits'      => $isVL || $isSL,
        ]);
    }
}