<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class HR extends Component
{
    public string $search = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    // Form fields
    public $employee_number;

    public $name;

    public $username;

    public $email;

    public $password;

    public $password_confirmation;

    protected function rules(): array
    {
        $id = $this->selectedId;

        return [
            'employee_number' => ['required', 'string', $this->isEditing ? "unique:users,employee_number,{$id}" : 'unique:users,employee_number'],
            'username' => ['required', 'string', $this->isEditing ? "unique:users,username,{$id}" : 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', $this->isEditing ? "unique:users,email,{$id}" : 'unique:users,email'],
            'password' => $this->isEditing
                ? ['nullable', 'confirmed', Password::defaults()]
                : ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function save(): void
    {
        $this->validate();

        User::create([
            'employee_number' => $this->employee_number,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->resetForm();
        session()->flash('message', 'User added successfully.');
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->selectedId = $user->id;
        $this->employee_number = $user->employee_number;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->password = null;
        $this->password_confirmation = null;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        $user = User::findOrFail($this->selectedId);

        $data = [
            'employee_number' => $this->employee_number,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $this->resetForm();
        session()->flash('message', 'User updated successfully.');
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        $user->is_active = ! $user->is_active;
        $user->save();

        // Kill active sessions when deactivating
        if (! $user->is_active) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        session()->flash('message', $user->is_active ? 'Account activated.' : 'Account deactivated.');
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
            'employee_number', 'name', 'username',
            'email', 'password', 'password_confirmation',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
    }

    public function render()
    {
        $users = User::query()
            ->with(['employmentDetail'])
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', "%{$this->search}%")
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
