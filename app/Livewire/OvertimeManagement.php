<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\OvertimeApplication;
use App\Models\PayoffApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OvertimeManagement extends Component
{
    public string $filterStatus = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public $type = 'overtime';

    public $start_datetime;

    public $end_datetime;

    public $hours;

    public bool $lunch_break_deducted = false;

    public $reason;

    public function updatedStartDatetime(): void
    {
        $this->computeHours();
    }

    public function updatedEndDatetime(): void
    {
        $this->computeHours();
    }

    private function computeHours(): void
    {
        if ($this->start_datetime && $this->end_datetime) {
            $start = \Carbon\Carbon::parse($this->start_datetime);
            $end   = \Carbon\Carbon::parse($this->end_datetime);
            if ($end->gt($start)) {
                $raw = round($start->diffInMinutes($end) / 60, 2);
                $this->hours = $this->lunch_break_deducted ? max(0, $raw - 1) : $raw;
            } else {
                $this->hours = null;
            }
        }
    }

    public function deductLunchBreak(): void
    {
        if ($this->lunch_break_deducted) {
            return;
        }
        if ($this->hours !== null && $this->hours > 1) {
            $this->hours = round($this->hours - 1, 2);
            $this->lunch_break_deducted = true;
        }
    }

    protected function rules(): array
    {
        return [
            'type'           => ['required', 'in:overtime,on_call'],
            'start_datetime' => ['required', 'date'],
            'end_datetime'   => ['required', 'date', 'after:start_datetime'],
            'hours'          => ['required', 'numeric', 'min:0.01'],
            'reason'         => ['nullable', 'string', 'max:1000'],
        ];
    }

    private function checkOverlap(?int $excludeId = null): bool
    {
        $start = $this->start_datetime;
        $end   = $this->end_datetime;
        $uid   = Auth::id();

        $otQuery = OvertimeApplication::where('user_id', $uid)
            ->where('status', '!=', 'rejected')
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start);

        if ($excludeId) {
            $otQuery->where('id', '!=', $excludeId);
        }

        if ($otQuery->exists()) {
            return true;
        }

        return PayoffApplication::where('user_id', $uid)
            ->where('status', '!=', 'rejected')
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start)
            ->exists();
    }

    public function save(): void
    {
        $this->validate();

        if ($this->checkOverlap()) {
            $this->addError('start_datetime', 'This time period overlaps with an existing overtime or pay-off application.');

            return;
        }

        $isDeptHead = Department::where('dept_head_id', Auth::id())->exists();

        OvertimeApplication::create([
            'user_id'               => Auth::id(),
            'type'                  => $this->type,
            'start_datetime'        => $this->start_datetime,
            'end_datetime'          => $this->end_datetime,
            'hours'                 => $this->hours,
            'lunch_break_deducted'  => $this->lunch_break_deducted,
            'reason'                => $this->reason,
            'status'                => $isDeptHead ? 'dept_approved' : 'pending',
            'dept_head_status'      => $isDeptHead ? 'approved' : 'pending',
            'dept_head_approved_by' => $isDeptHead ? Auth::id() : null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Overtime application submitted successfully.');
    }

    public function edit(int $id): void
    {
        $app = OvertimeApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $this->selectedId            = $app->id;
        $this->type                  = $app->type;
        $this->start_datetime        = $app->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime          = $app->end_datetime->format('Y-m-d\TH:i');
        $this->hours                 = $app->hours;
        $this->lunch_break_deducted  = (bool) $app->lunch_break_deducted;
        $this->reason                = $app->reason;
        $this->isEditing             = true;
    }

    public function update(): void
    {
        $this->validate();

        if ($this->checkOverlap($this->selectedId)) {
            $this->addError('start_datetime', 'This time period overlaps with an existing overtime or pay-off application.');

            return;
        }

        OvertimeApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($this->selectedId)
            ->update([
                'type'                 => $this->type,
                'start_datetime'       => $this->start_datetime,
                'end_datetime'         => $this->end_datetime,
                'hours'                => $this->hours,
                'lunch_break_deducted' => $this->lunch_break_deducted,
                'reason'               => $this->reason,
            ]);

        $this->resetForm();
        session()->flash('message', 'Application updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId        = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        OvertimeApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($this->selectedId)
            ->delete();

        $this->resetForm();
        session()->flash('message', 'Application deleted.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'start_datetime', 'end_datetime', 'hours', 'reason',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
            'lunch_break_deducted',
        ]);
        $this->type = 'overtime';
    }

    public function render()
    {
        $applications = OvertimeApplication::query()
            ->with('deptHeadApprover', 'hrApprover', 'accountingApprover')
            ->where('user_id', Auth::id())
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('start_datetime')
            ->get();

        return view('pages.users.overtime', compact('applications'))
            ->layout('layouts.app');
    }
}
