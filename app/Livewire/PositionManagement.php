<?php

namespace App\Livewire;

use App\Models\Position;
use Livewire\Component;

class PositionManagement extends Component
{
    public string $search = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public string $name = '';

    public string $code = '';

    public string $description = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255',
                $this->isEditing
                    ? "unique:positions,name,{$this->selectedId}"
                    : 'unique:positions,name',
            ],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        Position::create([
            'name' => $this->name,
            'code' => strtoupper($this->code) ?: null,
            'description' => $this->description ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Position created successfully.');
    }

    public function edit(int $id): void
    {
        $pos = Position::findOrFail($id);

        $this->selectedId = $pos->id;
        $this->name = $pos->name;
        $this->code = $pos->code ?? '';
        $this->description = $pos->description ?? '';
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function update(): void
    {
        $this->validate();

        Position::findOrFail($this->selectedId)->update([
            'name' => $this->name,
            'code' => strtoupper($this->code) ?: null,
            'description' => $this->description ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Position updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        Position::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'Position deleted.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'code', 'description',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
    }

    public function render()
    {
        $positions = Position::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
            )
            ->orderBy('name')
            ->get();

        return view('pages.HR.position-management', compact('positions'))
            ->layout('layouts.app');
    }
}
