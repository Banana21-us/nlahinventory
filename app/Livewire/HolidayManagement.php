<?php

namespace App\Livewire;

use App\Models\Holiday;
use Livewire\Component;

class HolidayManagement extends Component
{
    public string $search = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public $name;

    public $date;

    public $type = 'regular';

    public $is_recurring = true;

    public $remarks;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:regular,special_non_working,special_working'],
            'is_recurring' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        Holiday::create([
            'name' => $this->name,
            'date' => $this->date,
            'type' => $this->type,
            'is_recurring' => $this->is_recurring,
            'remarks' => $this->remarks,
        ]);

        $this->resetForm();
        session()->flash('message', 'Holiday created successfully.');
    }

    public function edit(int $id): void
    {
        $holiday = Holiday::findOrFail($id);

        $this->selectedId = $holiday->id;
        $this->name = $holiday->name;
        $this->date = $holiday->date->format('Y-m-d');
        $this->type = $holiday->type;
        $this->is_recurring = $holiday->is_recurring;
        $this->remarks = $holiday->remarks;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        Holiday::findOrFail($this->selectedId)->update([
            'name' => $this->name,
            'date' => $this->date,
            'type' => $this->type,
            'is_recurring' => $this->is_recurring,
            'remarks' => $this->remarks,
        ]);

        $this->resetForm();
        session()->flash('message', 'Holiday updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        Holiday::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'Holiday deleted successfully.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'date', 'type', 'is_recurring', 'remarks',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
        $this->type = 'regular';
        $this->is_recurring = true;
    }

    public function render()
    {
        $holidays = Holiday::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('date')
            ->get();

        return view('pages.HR.holiday-management', compact('holidays'))
            ->layout('layouts.app');
    }
}
