<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Department; // Assuming you have a Department model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HR extends Component
{
    public $users;
    public $departments; // To populate a dropdown
    public $showForm = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $selectedId;

    // Form fields - Added department_id and username
    public $employee_number, $department_id, $name, $username, $email, $password, $password_confirmation, $role = 'Staff';

    public function mount()
    {
        $this->users = User::latest()->get();
        // $this->departments = Department::all(); // Uncomment when you have departments
    }

    protected function rules()
    {
        $id = $this->selectedId;
        return [
            'employee_number' => ['required', 'string', $this->isEditing ? "unique:users,employee_number,$id" : "unique:users,employee_number"],
            'username'        => ['required', 'string', $this->isEditing ? "unique:users,username,$id" : "unique:users,username"],
            'department_id'   => ['nullable', 'exists:departments,id'], // Make sure this matches your depts table
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', $this->isEditing ? "unique:users,email,$id" : "unique:users,email"],
            'password'        => $this->isEditing ? ['nullable', 'confirmed', 'min:8'] : ['required', 'confirmed', 'min:8'],
            'role'            => ['required', 'in:Staff,HR,Maintenance,Inspector,Disable'],
        ];
    }

    public function save()
    {
        $this->validate();

        User::create([
            'employee_number' => $this->employee_number,
            'department_id'   => $this->department_id,
            'name'            => $this->name,
            'username'        => $this->username,
            'email'           => $this->email,
            'password'        => Hash::make($this->password),
            'role'            => $this->role,
            'email_verified_at' => now(),
        ]);

        $this->resetForm();
        session()->flash('message', 'User added successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->selectedId        = $user->id;
        $this->employee_number   = $user->employee_number;
        $this->department_id     = $user->department_id;
        $this->name              = $user->name;
        $this->username          = $user->username;
        $this->email             = $user->email;
        $this->role              = $user->role;
        
        $this->password = null;
        $this->password_confirmation = null;
        $this->isEditing = true;
        $this->showForm = true; // Make sure the form actually opens
    }

    public function update()
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

        // Handle Session Killing for Disabled Users
        if ($this->role === 'Disable') {
            DB::table('sessions')->where('user_id', $user->id)->delete();
            $data['is_active'] = false; // Sync with your is_active column
        } else {
            $data['is_active'] = true;
        }
    
        $user->update($data);

        $this->resetForm();
        session()->flash('message', 'User updated successfully.');
    }

    private function resetForm()
    {
        $this->reset(['employee_number', 'department_id', 'name', 'username', 'email', 'password', 'password_confirmation', 'role', 'selectedId', 'isEditing', 'showForm']);
        $this->users = User::latest()->get();
    }

    public function render()
    {
        return view('pages.HR.userlist')->layout('layouts.app');
    }
}