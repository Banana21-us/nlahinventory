<?php

namespace App\Livewire;

use App\Models\PayoffApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PayoffManagement extends Component
{
    public string $filterStatus = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public $redemption_type = 'cash';

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
            'redemption_type' => ['required', 'in:cash,leave'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'hours' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        PayoffApplication::create([
            'user_id' => Auth::id(),
            'redemption_type' => $this->redemption_type,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'hours' => $this->hours,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        $this->resetForm();
        session()->flash('message', 'Pay-off application submitted successfully.');
    }

    public function edit(int $id): void
    {
        $app = PayoffApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $this->selectedId = $app->id;
        $this->redemption_type = $app->redemption_type;
        $this->start_datetime = $app->start_datetime->format('Y-m-d\TH:i');
        $this->end_datetime = $app->end_datetime->format('Y-m-d\TH:i');
        $this->hours = $app->hours;
        $this->reason = $app->reason;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        PayoffApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($this->selectedId)
            ->update([
                'redemption_type' => $this->redemption_type,
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
        PayoffApplication::where('user_id', Auth::id())
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
        $this->redemption_type = 'cash';
    }

    public function render()
    {
        $applications = PayoffApplication::query()
            ->with('deptHeadApprover', 'hrApprover', 'accountingApprover')
            ->where('user_id', Auth::id())
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('start_datetime')
            ->get();

        return view('pages.users.payoff', compact('applications'))
            ->layout('layouts.app');
    }
}
