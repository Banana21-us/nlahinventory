<?php

namespace App\Livewire;

use App\Models\PayoffApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PayoffManagement extends Component
{
    public string $search = '';

    public string $filterStatus = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public $user_id;

    public $start_datetime;

    public $end_datetime;

    public $hours;

    public $reason;

    public $status = 'pending';

    public $approved_by;

    protected function rules(): array
    {
        return [
            'user_id'        => ['required', 'integer', 'exists:users,id'],
            'start_datetime' => ['required', 'date'],
            'end_datetime'   => ['required', 'date', 'after:start_datetime'],
            'hours'          => ['required', 'numeric', 'min:0.5', 'max:24'],
            'reason'         => ['nullable', 'string', 'max:1000'],
            'status'         => ['required', 'in:pending,approved,rejected'],
            'approved_by'    => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        PayoffApplication::create([
            'user_id'        => $this->user_id,
            'start_datetime' => $this->start_datetime,
            'end_datetime'   => $this->end_datetime,
            'hours'          => $this->hours,
            'reason'         => $this->reason,
            'status'         => $this->status,
            'approved_by'    => $this->approved_by ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Pay-off application created successfully.');
    }

    public function edit(int $id): void
    {
        $app = PayoffApplication::findOrFail($id);

        $this->selectedId     = $app->id;
        $this->user_id        = $app->user_id;
        $this->start_datetime = $app->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime   = $app->end_datetime->format('Y-m-d\TH:i');
        $this->hours          = $app->hours;
        $this->reason         = $app->reason;
        $this->status         = $app->status;
        $this->approved_by    = $app->approved_by;
        $this->isEditing      = true;
    }

    public function update(): void
    {
        $this->validate();

        PayoffApplication::findOrFail($this->selectedId)->update([
            'user_id'        => $this->user_id,
            'start_datetime' => $this->start_datetime,
            'end_datetime'   => $this->end_datetime,
            'hours'          => $this->hours,
            'reason'         => $this->reason,
            'status'         => $this->status,
            'approved_by'    => $this->approved_by ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Pay-off application updated successfully.');
    }

    public function approve(int $id): void
    {
        PayoffApplication::findOrFail($id)->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
        ]);
        session()->flash('message', 'Application approved.');
    }

    public function reject(int $id): void
    {
        PayoffApplication::findOrFail($id)->update([
            'status'      => 'rejected',
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
        PayoffApplication::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'Application deleted.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'user_id', 'start_datetime', 'end_datetime', 'hours', 'reason', 'approved_by',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
        $this->status = 'pending';
    }

    public function render()
    {
        $applications = PayoffApplication::query()
            ->with('user', 'approver')
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('start_datetime')
            ->get();

        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('pages.HR.payoff-management', compact('applications', 'users'))
            ->layout('layouts.app');
    }
}
