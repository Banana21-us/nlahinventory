<?php

namespace App\Livewire;

use App\Models\OvertimeApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OvertimeManagement extends Component
{
    public string $search = '';

    public string $filterStatus = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public $user_id;

    public $type = 'overtime';

    public $start_datetime;

    public $end_datetime;

    public $reason;

    public $remarks;

    public $status = 'pending';

    public $approved_by;

    protected function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'type' => ['required', 'in:overtime,on_call'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'approved_by' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        OvertimeApplication::create([
            'user_id' => $this->user_id,
            'type' => $this->type,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'reason' => $this->reason,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'approved_by' => $this->approved_by ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Overtime application created successfully.');
    }

    public function edit(int $id): void
    {
        $app = OvertimeApplication::findOrFail($id);

        $this->selectedId = $app->id;
        $this->user_id = $app->user_id;
        $this->type = $app->type;
        $this->start_datetime = $app->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime = $app->end_datetime->format('Y-m-d\TH:i');
        $this->reason = $app->reason;
        $this->remarks = $app->remarks;
        $this->status = $app->status;
        $this->approved_by = $app->approved_by;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        OvertimeApplication::findOrFail($this->selectedId)->update([
            'user_id' => $this->user_id,
            'type' => $this->type,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'reason' => $this->reason,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'approved_by' => $this->approved_by ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Overtime application updated successfully.');
    }

    public function approve(int $id): void
    {
        OvertimeApplication::findOrFail($id)->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);
        session()->flash('message', 'Application approved.');
    }

    public function reject(int $id): void
    {
        OvertimeApplication::findOrFail($id)->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);
        session()->flash('message', 'Application rejected.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        OvertimeApplication::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'Application deleted.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'user_id', 'start_datetime', 'end_datetime', 'reason', 'remarks', 'approved_by',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
        $this->type = 'overtime';
        $this->status = 'pending';
    }

    public function render()
    {
        $applications = OvertimeApplication::query()
            ->with('user', 'approver')
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('start_datetime')
            ->get();

        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('pages.HR.overtime-management', compact('applications', 'users'))
            ->layout('layouts.app');
    }
}
