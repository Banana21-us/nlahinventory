<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class HR extends Component

{
    public $users;
    public $showForm = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $selectedId;

    // Form fields
    public $employee_number, $name, $email, $password, $password_confirmation,$email_verified_at, $role = 'Staff';

    public function mount()
    {
        $this->users = User::latest()->get();
    }

    protected function rules()
    {
        $uniqueEmail = $this->isEditing
            ? 'unique:users,email,' . $this->selectedId
            : 'unique:users,email';

        $uniqueEmpNo = $this->isEditing
            ? 'unique:users,employee_number,' . $this->selectedId
            : 'unique:users,employee_number';

        return [
            'employee_number' => ['required', 'string', $uniqueEmpNo],
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', $uniqueEmail],
            'password'        => $this->isEditing ? ['nullable', 'confirmed', 'min:8'] : ['required', 'confirmed', 'min:8'],
            'role'            => ['required', 'in:Staff,HR,Maintenance,Inspector,Disable'],
        ];
    }

    public function save()
    {
        $this->validate();

        User::create([
            'employee_number' => $this->employee_number,
            'name'            => $this->name,
            'email'           => $this->email,
            'password'        => Hash::make($this->password),
            'role'            => $this->role,
            'email_verified_at' => now(),
        ]);

        $this->reset(['employee_number', 'name', 'email', 'password', 'password_confirmation', 'role']);
        $this->showForm = false;
        $this->users = User::latest()->get();
        session()->flash('message', 'User added successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->selectedId        = $user->id;
        $this->employee_number   = $user->employee_number;
        $this->name              = $user->name;
        $this->email             = $user->email;
        $this->role              = $user->role;
        $this->password          = null;
        $this->password_confirmation = null;
        $this->isEditing         = true;
    }

    public function update()
    {
        $this->isEditing = true;
        $this->validate();

        $user = User::findOrFail($this->selectedId);
        $data = [
            'employee_number' => $this->employee_number,
            'name'            => $this->name,
            'email'           => $this->email,
            'role'            => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->role === 'Disable') {
        \DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();
    }
    
        $user->update($data);

        $this->reset(['employee_number', 'name', 'email', 'password', 'password_confirmation', 'role', 'selectedId']);
        $this->isEditing = false;
        $this->users = User::latest()->get();
        session()->flash('message', 'User updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        User::findOrFail($this->selectedId)->delete();
        $this->confirmingDeletion = false;
        $this->selectedId = null;
        $this->users = User::latest()->get();
        session()->flash('message', 'User deleted successfully.');
    }

    public function render()
    {
        return view('pages.HR.userlist')->layout('layouts.app');
    }


    
}
    