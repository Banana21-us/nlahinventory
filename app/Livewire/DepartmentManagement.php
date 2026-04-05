<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;

class DepartmentManagement extends Component
{
    public string $search = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public $name;

    public $code;

    public $dept_head_id;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20',
                $this->isEditing
                    ? "unique:departments,code,{$this->selectedId}"
                    : 'unique:departments,code'],
            'dept_head_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        Department::create([
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'dept_head_id' => $this->dept_head_id ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Department created successfully.');
    }

    public function edit(int $id): void
    {
        $dept = Department::findOrFail($id);

        $this->selectedId = $dept->id;
        $this->name = $dept->name;
        $this->code = $dept->code;
        $this->dept_head_id = $dept->dept_head_id;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        $dept = Department::findOrFail($this->selectedId);
        $dept->update([
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'dept_head_id' => $this->dept_head_id ?: null,
        ]);

        $this->resetForm();
        session()->flash('message', 'Department updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        Department::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'Department deleted successfully.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'code', 'dept_head_id',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
    }

    public function render()
    {
        $departments = Department::query()
            ->with('deptHead.employmentDetail')
            ->when($this->search, fn ($q) => $q->where(fn ($inner) => $inner->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
            )
            )
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->with('employmentDetail')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.HR.department-management', compact('departments', 'users'))
            ->layout('layouts.app');
    }
}
