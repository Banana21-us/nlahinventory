<?php

namespace App\Livewire;

use App\Models\LeaveType;
use Livewire\Component;

class LeaveTypeManagement extends Component
{
    public string $search = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public $code;

    public $label;

    public $is_paid = true;

    public $requires_attachment = false;

    public $solo_parent_only = false;

    public $requires_admin_approval = false;

    public $annual_days;

    public $reset_type = 'anniversary';

    public $is_active = true;

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20',
                $this->isEditing
                    ? "unique:leave_types,code,{$this->selectedId}"
                    : 'unique:leave_types,code'],
            'label' => ['required', 'string', 'max:255'],
            'is_paid' => ['boolean'],
            'requires_attachment' => ['boolean'],
            'solo_parent_only' => ['boolean'],
            'requires_admin_approval' => ['boolean'],
            'annual_days' => ['nullable', 'numeric', 'min:0', 'max:365'],
            'reset_type' => ['required', 'in:anniversary,january,birth_month,none'],
            'is_active' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        LeaveType::create([
            'code' => strtoupper($this->code),
            'label' => $this->label,
            'is_paid' => $this->is_paid,
            'requires_attachment' => $this->requires_attachment,
            'solo_parent_only' => $this->solo_parent_only,
            'requires_admin_approval' => $this->requires_admin_approval,
            'annual_days' => $this->annual_days,
            'reset_type' => $this->reset_type,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        session()->flash('message', 'Leave type created successfully.');
    }

    public function edit(int $id): void
    {
        $lt = LeaveType::findOrFail($id);

        $this->selectedId = $lt->id;
        $this->code = $lt->code;
        $this->label = $lt->label;
        $this->is_paid = $lt->is_paid;
        $this->requires_attachment = $lt->requires_attachment;
        $this->solo_parent_only = $lt->solo_parent_only;
        $this->requires_admin_approval = $lt->requires_admin_approval;
        $this->annual_days = $lt->annual_days;
        $this->reset_type = $lt->reset_type;
        $this->is_active = $lt->is_active;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        LeaveType::findOrFail($this->selectedId)->update([
            'code' => strtoupper($this->code),
            'label' => $this->label,
            'is_paid' => $this->is_paid,
            'requires_attachment' => $this->requires_attachment,
            'solo_parent_only' => $this->solo_parent_only,
            'requires_admin_approval' => $this->requires_admin_approval,
            'annual_days' => $this->annual_days,
            'reset_type' => $this->reset_type,
            'is_active' => $this->is_active,
        ]);

        $this->resetForm();
        session()->flash('message', 'Leave type updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        LeaveType::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'Leave type deleted successfully.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'code', 'label', 'annual_days',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
        $this->is_paid = true;
        $this->requires_attachment = false;
        $this->solo_parent_only = false;
        $this->requires_admin_approval = false;
        $this->reset_type = 'anniversary';
        $this->is_active = true;
    }

    public function render()
    {
        $leaveTypes = LeaveType::query()
            ->when($this->search, fn ($q) => $q->where(fn ($inner) => $inner
                ->where('code', 'like', "%{$this->search}%")
                ->orWhere('label', 'like', "%{$this->search}%")
            ))
            ->orderBy('code')
            ->get();

        return view('pages.HR.leave-type-management', compact('leaveTypes'))
            ->layout('layouts.app');
    }
}
