<?php

namespace App\Livewire;

use App\Models\OvertimeApplication;
use App\Models\PayoffApplication;
use App\Models\PayoffLeaveCredit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HrApplicationsManagement extends Component
{
    public string $search = '';

    public string $filterStatus = '';

    // ── Edit overtime (all-employee management) ───────────────
    public bool $editingOvertime = false;

    public ?int $overtimeId = null;

    public $ot_user_id;

    public $ot_type = 'overtime';

    public $ot_start_datetime;

    public $ot_end_datetime;

    public $ot_hours;

    public $ot_reason;

    public $ot_status = 'pending';

    // ── Edit payoff (all-employee management) ─────────────────
    public bool $editingPayoff = false;

    public ?int $payoffId = null;

    public $po_user_id;

    public $po_redemption_type = 'cash';

    public $po_start_datetime;

    public $po_end_datetime;

    public $po_hours;

    public $po_reason;

    public $po_status = 'pending';

    // ── My overtime creation ──────────────────────────────────
    public bool $myOtForm = false;

    public $myOt_type = 'overtime';

    public $myOt_start_datetime;

    public $myOt_end_datetime;

    public $myOt_hours;

    public $myOt_reason;

    // ── My payoff creation ────────────────────────────────────
    public bool $myPoForm = false;

    public $myPo_redemption_type = 'cash';

    public $myPo_start_datetime;

    public $myPo_end_datetime;

    public $myPo_hours;

    public $myPo_reason;

    // ── Auto-compute (edit modals) ────────────────────────────

    public function updatedOtStartDatetime(): void
    {
        $this->computeOtHours();
    }

    public function updatedOtEndDatetime(): void
    {
        $this->computeOtHours();
    }

    public function updatedPoStartDatetime(): void
    {
        $this->computePoHours();
    }

    public function updatedPoEndDatetime(): void
    {
        $this->computePoHours();
    }

    // ── Auto-compute (my forms) ───────────────────────────────

    public function updatedMyOtStartDatetime(): void
    {
        $this->computeMyOtHours();
    }

    public function updatedMyOtEndDatetime(): void
    {
        $this->computeMyOtHours();
    }

    public function updatedMyPoStartDatetime(): void
    {
        $this->computeMyPoHours();
    }

    public function updatedMyPoEndDatetime(): void
    {
        $this->computeMyPoHours();
    }

    private function computeOtHours(): void
    {
        if ($this->ot_start_datetime && $this->ot_end_datetime) {
            $start = \Carbon\Carbon::parse($this->ot_start_datetime);
            $end = \Carbon\Carbon::parse($this->ot_end_datetime);
            $this->ot_hours = $end->gt($start) ? round($start->diffInMinutes($end) / 60, 2) : null;
        }
    }

    private function computePoHours(): void
    {
        if ($this->po_start_datetime && $this->po_end_datetime) {
            $start = \Carbon\Carbon::parse($this->po_start_datetime);
            $end = \Carbon\Carbon::parse($this->po_end_datetime);
            $this->po_hours = $end->gt($start) ? round($start->diffInMinutes($end) / 60, 2) : null;
        }
    }

    private function computeMyOtHours(): void
    {
        if ($this->myOt_start_datetime && $this->myOt_end_datetime) {
            $start = \Carbon\Carbon::parse($this->myOt_start_datetime);
            $end = \Carbon\Carbon::parse($this->myOt_end_datetime);
            $this->myOt_hours = $end->gt($start) ? round($start->diffInMinutes($end) / 60, 2) : null;
        }
    }

    private function computeMyPoHours(): void
    {
        if ($this->myPo_start_datetime && $this->myPo_end_datetime) {
            $start = \Carbon\Carbon::parse($this->myPo_start_datetime);
            $end = \Carbon\Carbon::parse($this->myPo_end_datetime);
            $this->myPo_hours = $end->gt($start) ? round($start->diffInMinutes($end) / 60, 2) : null;
        }
    }

    // ── OVERTIME (management) ─────────────────────────────────

    public function approveOvertime(int $id): void
    {
        $app = OvertimeApplication::where('dept_head_status', 'approved')
            ->where('status', 'dept_approved')
            ->findOrFail($id);

        $app->update([
            'hr_status'      => 'approved',
            'hr_approved_by' => Auth::id(),
            'status'         => 'hr_approved',
        ]);
        session()->flash('message', 'Overtime approved by HR — forwarded to Accounting.');
    }

    public function rejectOvertime(int $id): void
    {
        OvertimeApplication::where('status', 'dept_approved')
            ->findOrFail($id)
            ->update([
                'hr_status'      => 'rejected',
                'hr_approved_by' => Auth::id(),
                'status'         => 'rejected',
            ]);
        session()->flash('message', 'Overtime rejected.');
    }

    public function accountingApproveOvertime(int $id): void
    {
        OvertimeApplication::where('status', 'hr_approved')
            ->findOrFail($id)
            ->update([
                'accounting_status'      => 'approved',
                'accounting_approved_by' => Auth::id(),
                'status'                 => 'approved',
            ]);
        session()->flash('message', 'Overtime tagged as approved for payroll.');
    }

    public function deductLunchBreakOvertime(int $id): void
    {
        $app = OvertimeApplication::findOrFail($id);

        if ($app->lunch_break_deducted || $app->hours <= 1) {
            return;
        }

        $app->update([
            'hours'                => round($app->hours - 1, 2),
            'lunch_break_deducted' => true,
        ]);
        session()->flash('message', 'Lunch break (-1 hr) deducted.');
    }

    public function editOvertime(int $id): void
    {
        $app = OvertimeApplication::findOrFail($id);
        $this->overtimeId = $app->id;
        $this->ot_user_id = $app->user_id;
        $this->ot_type = $app->type;
        $this->ot_start_datetime = $app->start_datetime->format('Y-m-d\TH:i');
        $this->ot_end_datetime = $app->end_datetime->format('Y-m-d\TH:i');
        $this->ot_hours = $app->hours;
        $this->ot_reason = $app->reason;
        $this->ot_status = $app->status;
        $this->editingOvertime = true;
    }

    public function updateOvertime(): void
    {
        $this->validate([
            'ot_user_id' => ['required', 'integer', 'exists:users,id'],
            'ot_type' => ['required', 'in:overtime,on_call'],
            'ot_start_datetime' => ['required', 'date'],
            'ot_end_datetime' => ['required', 'date', 'after:ot_start_datetime'],
            'ot_hours' => ['required', 'numeric', 'min:0.01'],
            'ot_reason' => ['nullable', 'string', 'max:1000'],
            'ot_status' => ['required', 'in:pending,hr_approved,approved,rejected'],
        ]);

        OvertimeApplication::findOrFail($this->overtimeId)->update([
            'user_id' => $this->ot_user_id,
            'type' => $this->ot_type,
            'start_datetime' => $this->ot_start_datetime,
            'end_datetime' => $this->ot_end_datetime,
            'hours' => $this->ot_hours,
            'reason' => $this->ot_reason,
            'status' => $this->ot_status,
            'approved_by' => $this->ot_status !== 'pending' ? Auth::id() : null,
        ]);

        $this->reset(['overtimeId', 'ot_user_id', 'ot_type', 'ot_start_datetime', 'ot_end_datetime', 'ot_hours', 'ot_reason', 'editingOvertime']);
        $this->ot_status = 'pending';
        session()->flash('message', 'Overtime updated.');
    }

    public function deleteOvertime(int $id): void
    {
        OvertimeApplication::findOrFail($id)->delete();
        session()->flash('message', 'Overtime deleted.');
    }

    // ── PAY-OFF (management) ──────────────────────────────────

    public function approvePayoff(int $id): void
    {
        $app = PayoffApplication::where('status', 'dept_approved')->findOrFail($id);

        $earnedAt  = Carbon::now()->toDateString();
        $expiresAt = Carbon::now()->addMonths(6)->toDateString();

        $app->update([
            'hr_status'      => 'approved',
            'hr_approved_by' => Auth::id(),
            'status'         => 'hr_approved', // forwarded to Accounting for tagging
        ]);

        // Credit the employee's payoff leave balance immediately
        PayoffLeaveCredit::create([
            'user_id'                => $app->user_id,
            'payoff_application_id'  => $app->id,
            'hours_earned'           => $app->hours,
            'hours_remaining'        => $app->hours,
            'earned_at'              => $earnedAt,
            'expires_at'             => $expiresAt,
        ]);

        session()->flash('message', 'Pay-off approved — '.$app->hours.' hrs credited to employee. Forwarded to Accounting.');
    }

    public function rejectPayoff(int $id): void
    {
        PayoffApplication::where('status', 'dept_approved')
            ->findOrFail($id)
            ->update([
                'hr_status'      => 'rejected',
                'hr_approved_by' => Auth::id(),
                'status'         => 'rejected',
            ]);
        session()->flash('message', 'Pay-off rejected.');
    }

    public function accountingTagPayoff(int $id): void
    {
        PayoffApplication::where('status', 'hr_approved')
            ->findOrFail($id)
            ->update([
                'accounting_status'      => 'approved',
                'accounting_approved_by' => Auth::id(),
                'status'                 => 'approved',
            ]);
        session()->flash('message', 'Pay-off tagged by Accounting.');
    }

    public function deductLunchBreakPayoff(int $id): void
    {
        $app = PayoffApplication::findOrFail($id);

        if ($app->lunch_break_deducted || $app->hours <= 1) {
            return;
        }

        $newHours = round($app->hours - 1, 2);
        $app->update([
            'hours'                => $newHours,
            'lunch_break_deducted' => true,
        ]);

        // If a credit was already created for this payoff, adjust it too
        $credit = PayoffLeaveCredit::where('payoff_application_id', $id)->first();
        if ($credit) {
            $diff = $credit->hours_earned - $newHours;
            $credit->update([
                'hours_earned'    => $newHours,
                'hours_remaining' => max(0, $credit->hours_remaining - $diff),
            ]);
        }

        session()->flash('message', 'Lunch break (-1 hr) deducted.');
    }

    public function editPayoff(int $id): void
    {
        $app = PayoffApplication::findOrFail($id);
        $this->payoffId = $app->id;
        $this->po_user_id = $app->user_id;
        $this->po_redemption_type = $app->redemption_type;
        $this->po_start_datetime = $app->start_datetime->format('Y-m-d\TH:i');
        $this->po_end_datetime = $app->end_datetime->format('Y-m-d\TH:i');
        $this->po_hours = $app->hours;
        $this->po_reason = $app->reason;
        $this->po_status = $app->status;
        $this->editingPayoff = true;
    }

    public function updatePayoff(): void
    {
        $this->validate([
            'po_user_id' => ['required', 'integer', 'exists:users,id'],
            'po_redemption_type' => ['required', 'in:cash,leave'],
            'po_start_datetime' => ['required', 'date'],
            'po_end_datetime' => ['required', 'date', 'after:po_start_datetime'],
            'po_hours' => ['required', 'numeric', 'min:0.01'],
            'po_reason' => ['nullable', 'string', 'max:1000'],
            'po_status' => ['required', 'in:pending,dept_approved,hr_approved,approved,rejected'],
        ]);

        PayoffApplication::findOrFail($this->payoffId)->update([
            'user_id' => $this->po_user_id,
            'redemption_type' => $this->po_redemption_type,
            'start_datetime' => $this->po_start_datetime,
            'end_datetime' => $this->po_end_datetime,
            'hours' => $this->po_hours,
            'reason' => $this->po_reason,
            'status' => $this->po_status,
            'approved_by' => $this->po_status !== 'pending' ? Auth::id() : null,
        ]);

        $this->reset(['payoffId', 'po_user_id', 'po_redemption_type', 'po_start_datetime', 'po_end_datetime', 'po_hours', 'po_reason', 'editingPayoff']);
        $this->po_status = 'pending';
        session()->flash('message', 'Pay-off updated.');
    }

    public function deletePayoff(int $id): void
    {
        PayoffApplication::findOrFail($id)->delete();
        session()->flash('message', 'Pay-off deleted.');
    }

    // ── MY APPLICATIONS (HR self-service) ─────────────────────

    public function saveMyOvertime(): void
    {
        $this->validate([
            'myOt_type' => ['required', 'in:overtime,on_call'],
            'myOt_start_datetime' => ['required', 'date'],
            'myOt_end_datetime' => ['required', 'date', 'after:myOt_start_datetime'],
            'myOt_hours' => ['required', 'numeric', 'min:0.01'],
            'myOt_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        OvertimeApplication::create([
            'user_id' => Auth::id(),
            'type' => $this->myOt_type,
            'start_datetime' => $this->myOt_start_datetime,
            'end_datetime' => $this->myOt_end_datetime,
            'hours' => $this->myOt_hours,
            'reason' => $this->myOt_reason,
            'status' => 'pending',
        ]);

        $this->reset(['myOt_start_datetime', 'myOt_end_datetime', 'myOt_hours', 'myOt_reason', 'myOtForm']);
        $this->myOt_type = 'overtime';
        session()->flash('message', 'Overtime application submitted.');
    }

    public function saveMyPayoff(): void
    {
        $this->validate([
            'myPo_redemption_type' => ['required', 'in:cash,leave'],
            'myPo_start_datetime' => ['required', 'date'],
            'myPo_end_datetime' => ['required', 'date', 'after:myPo_start_datetime'],
            'myPo_hours' => ['required', 'numeric', 'min:0.01'],
            'myPo_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        PayoffApplication::create([
            'user_id' => Auth::id(),
            'redemption_type' => $this->myPo_redemption_type,
            'start_datetime' => $this->myPo_start_datetime,
            'end_datetime' => $this->myPo_end_datetime,
            'hours' => $this->myPo_hours,
            'reason' => $this->myPo_reason,
            'status' => 'pending',
        ]);

        $this->reset(['myPo_redemption_type', 'myPo_start_datetime', 'myPo_end_datetime', 'myPo_hours', 'myPo_reason', 'myPoForm']);
        $this->myPo_redemption_type = 'cash';
        session()->flash('message', 'Pay-off application submitted.');
    }

    public function deleteMyOvertime(int $id): void
    {
        OvertimeApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id)
            ->delete();
        session()->flash('message', 'Application deleted.');
    }

    public function deleteMyPayoff(int $id): void
    {
        PayoffApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id)
            ->delete();
        session()->flash('message', 'Application deleted.');
    }

    public function render()
    {
        $overtimes = OvertimeApplication::query()
            ->with('user', 'hrApprover', 'accountingApprover')
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('start_datetime')
            ->get();

        $payoffs = PayoffApplication::query()
            ->with('user', 'deptHeadApprover', 'hrApprover')
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('start_datetime')
            ->get();

        $myOvertimes = OvertimeApplication::query()
            ->with('hrApprover', 'accountingApprover')
            ->where('user_id', Auth::id())
            ->orderByDesc('start_datetime')
            ->get();

        $myPayoffs = PayoffApplication::query()
            ->with('deptHeadApprover', 'hrApprover', 'accountingApprover')
            ->where('user_id', Auth::id())
            ->orderByDesc('start_datetime')
            ->get();

        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('pages.HR.hr-applications-management', compact('overtimes', 'payoffs', 'myOvertimes', 'myPayoffs', 'users'))
            ->layout('layouts.app');
    }
}
