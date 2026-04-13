<?php

namespace App\Livewire;

use App\Models\AccessKey;
use Livewire\Component;

class AccessKeyManagement extends Component
{
    // All gate slugs that can be granted — add new ones here as modules grow
    public const AVAILABLE_PERMISSIONS = [
        'access-medical' => 'Medical Mission',
        'access-maintenance' => 'Maintenance Checklist',
        'access-verify' => 'Maintenance Verification',
        'access-hr-only' => 'HR Management',
        'access-payroll' => 'Payroll & Compliance',
        'access-cashier-only' => 'Point of Sale (Cashier)',
    ];

    // Named routes that are valid login redirect destinations
    public const AVAILABLE_ROUTES = [
        'HR.hrdashboard' => 'HR Dashboard',
        'Maintenance.dashboard' => 'Maintenance Dashboard',
        'Maintenance.checklist.verify' => 'Maintenance Verification',
        'pos.dashboard' => 'Point of Sale',
        'users.leaveform' => 'Staff Leave Form',
        'users.waiting' => 'Waiting Area (no module)',
    ];

    public string $search = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $selectedId = null;

    public string $name = '';

    public string $description = '';

    public string $redirect_to = '';

    public bool $is_super = false;

    public array $selectedPermissions = [];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255',
                $this->isEditing
                    ? "unique:access_keys,name,{$this->selectedId}"
                    : 'unique:access_keys,name',
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'redirect_to' => ['nullable', 'string', 'max:255'],
            'is_super' => ['boolean'],
            'selectedPermissions' => ['array'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        AccessKey::create([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'redirect_to' => $this->redirect_to ?: null,
            'is_super' => $this->is_super,
            'permissions' => $this->selectedPermissions,
        ]);

        $this->resetForm();
        session()->flash('message', 'Access key created successfully.');
    }

    public function edit(int $id): void
    {
        $key = AccessKey::findOrFail($id);

        $this->selectedId = $key->id;
        $this->name = $key->name;
        $this->description = $key->description ?? '';
        $this->redirect_to = $key->redirect_to ?? '';
        $this->is_super = (bool) $key->is_super;
        $this->selectedPermissions = $key->permissions ?? [];
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function update(): void
    {
        $this->validate();

        AccessKey::findOrFail($this->selectedId)->update([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'redirect_to' => $this->redirect_to ?: null,
            'is_super' => $this->is_super,
            'permissions' => $this->selectedPermissions,
        ]);

        $this->resetForm();
        session()->flash('message', 'Access key updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        AccessKey::findOrFail($this->selectedId)->delete();
        $this->resetForm();
        session()->flash('message', 'Access key deleted.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'description', 'redirect_to', 'is_super', 'selectedPermissions',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion',
        ]);
    }

    public function render()
    {
        $keys = AccessKey::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view('pages.HR.access-key-management', [
            'keys' => $keys,
            'availablePermissions' => self::AVAILABLE_PERMISSIONS,
            'availableRoutes' => self::AVAILABLE_ROUTES,
        ])->layout('layouts.app');
    }
}
