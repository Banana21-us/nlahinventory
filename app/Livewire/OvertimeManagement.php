<?php

namespace App\Livewire;

use App\Models\OvertimeApplication;
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
            $end = \Carbon\Carbon::parse($this->end_datetime);
            $this->hours = $end->gt($start) ? round($start->diffInMinutes($end) / 60, 2) : null;
        }
    }

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:overtime,on_call'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'hours' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        OvertimeApplication::create([
            'user_id' => Auth::id(),
            'type' => $this->type,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'hours' => $this->hours,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        $this->resetForm();
        session()->flash('message', 'Overtime application submitted successfully.');
    }

    public function edit(int $id): void
    {
        $app = OvertimeApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $this->selectedId = $app->id;
        $this->type = $app->type;
        $this->start_datetime = $app->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime = $app->end_datetime->format('Y-m-d\TH:i');
        $this->hours = $app->hours;
        $this->reason = $app->reason;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        OvertimeApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($this->selectedId)
            ->update([
                'type' => $this->type,
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'hours' => $this->hours,
                'reason' => $this->reason,
            ]);

        $this->resetForm();
        session()->flash('message', 'Application updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
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
        ]);
        $this->type = 'overtime';
    }

    public function render()
    {
        $applications = OvertimeApplication::query()
            ->with('approver')
            ->where('user_id', Auth::id())
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('start_datetime')
            ->get();

        return view('pages.HR.overtime-management', compact('applications'))
            ->layout('layouts.app');
    }
}
