<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HR extends Component
{
    public string $search = '';
    public bool $showForm = false;
    public bool $isEditing = false;
    public bool $confirmingDeletion = false;
    public ?int $selectedId = null;

    // Form fields
    public $employee_number, $department_id, $name, $username, $email, $password, $password_confirmation, $role = 'Staff';

    protected function rules(): array
    {
        $id = $this->selectedId;
        return [
            'employee_number' => ['required', 'string', $this->isEditing ? "unique:users,employee_number,{$id}" : 'unique:users,employee_number'],
            'username'        => ['required', 'string', $this->isEditing ? "unique:users,username,{$id}" : 'unique:users,username'],
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', $this->isEditing ? "unique:users,email,{$id}" : 'unique:users,email'],
            'password'        => $this->isEditing ? ['nullable', 'confirmed', 'min:8'] : ['required', 'confirmed', 'min:8'],
            'role'            => ['required', 'in:Staff,HR,Maintenance,Inspector,Cashier,Disable'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        User::create([
            'employee_number'   => $this->employee_number,
            'department_id'     => $this->department_id,
            'name'              => $this->name,
            'username'          => $this->username,
            'email'             => $this->email,
            'password'          => Hash::make($this->password),
            'role'              => $this->role,
            'email_verified_at' => now(),
        ]);

        $this->resetForm();
        session()->flash('message', 'User added successfully.');
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->selectedId          = $user->id;
        $this->employee_number     = $user->employee_number;
        $this->department_id       = $user->department_id;
        $this->name                = $user->name;
        $this->username            = $user->username;
        $this->email               = $user->email;
        $this->role                = $user->role;
        $this->password            = null;
        $this->password_confirmation = null;
        $this->isEditing           = true;
    }

    public function update(): void
    {
        $this->validate();

        $user = User::findOrFail($this->selectedId);

        $data = [
            'employee_number' => $this->employee_number,
            'department_id'   => $this->department_id,
            'name'            => $this->name,
            'username'        => $this->username,
            'email'           => $this->email,
            'role'            => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        // Kill active sessions when disabling a user
        if ($this->role === 'Disable') {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $user->update($data);

        $this->resetForm();
        session()->flash('message', 'User updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        User::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'User deleted successfully.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'employee_number', 'department_id', 'name', 'username',
            'email', 'password', 'password_confirmation',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
        $this->role = 'Staff';
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) =>
                $q->where(fn ($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('employee_number', 'like', "%{$this->search}%")
                      ->orWhere('username', 'like', "%{$this->search}%")
                )
            )
            ->latest()
            ->get();

        return view('pages.HR.userlist', compact('users'))->layout('layouts.app');
    }
}
