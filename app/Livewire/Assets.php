<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetLocation;
use App\Models\ItemType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Assets extends Component
{
    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $item_type_id = '';

    public string $location_id = '';

    public string $status = 'available';

    public string $brand = '';

    public ?string $purchase_date = null;

    public string $sku = '';

    public string $search = '';

    protected function rules(): array
    {
        return [
            'item_type_id' => 'required|exists:item_types,id',
            'location_id' => 'required|exists:asset_locations,id',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'brand' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('assets', 'sku')->ignore($this->editingId),
            ],
        ];
    }

    protected $messages = [
        'item_type_id.required' => 'Please select an item type.',
        'item_type_id.exists' => 'Selected item type does not exist.',
        'location_id.required' => 'Please select a location.',
        'location_id.exists' => 'Selected location does not exist.',
        'status.required' => 'Status is required.',
        'sku.required' => 'SKU is required.',
        'sku.unique' => 'This SKU already exists.',
    ];

    #[Computed]
    public function itemTypes()
    {
        return ItemType::orderBy('name')->get();
    }

    #[Computed]
    public function locations()
    {
        return AssetLocation::orderBy('name')->get();
    }

    #[Computed]
    public function assets()
    {
        return Asset::with(['itemType', 'location'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('sku', 'like', '%'.$this->search.'%')
                        ->orWhere('brand', 'like', '%'.$this->search.'%')
                        ->orWhereHas('itemType', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('location', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'));
                });
            })
            ->latest()
            ->get();
    }

    public function save(): void
    {
        $this->validate();

        Asset::create([
            'item_type_id' => (int) $this->item_type_id,
            'location_id' => (int) $this->location_id,
            'status' => $this->status,
            'brand' => $this->brand !== '' ? trim($this->brand) : null,
            'purchase_date' => $this->purchase_date,
            'sku' => trim($this->sku),
        ]);

        session()->flash('message', "Asset '{$this->sku}' has been added.");
        $this->cancelForm();
    }

    public function edit(int $id): void
    {
        $asset = Asset::findOrFail($id);

        $this->editingId = $asset->id;
        $this->item_type_id = (string) $asset->item_type_id;
        $this->location_id = (string) $asset->location_id;
        $this->status = $asset->status;
        $this->brand = $asset->brand ?? '';
        $this->purchase_date = $asset->purchase_date?->toDateString();
        $this->sku = $asset->sku;
        $this->showForm = false;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        $asset = Asset::findOrFail($this->editingId);
        $asset->update([
            'item_type_id' => (int) $this->item_type_id,
            'location_id' => (int) $this->location_id,
            'status' => $this->status,
            'brand' => $this->brand !== '' ? trim($this->brand) : null,
            'purchase_date' => $this->purchase_date,
            'sku' => trim($this->sku),
        ]);

        session()->flash('message', "Asset '{$this->sku}' has been updated.");
        $this->cancelEdit();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $asset = Asset::findOrFail($this->deletingId);
        $sku = $asset->sku;

        $asset->delete();

        session()->flash('message', "Asset '{$sku}' has been removed.");
        $this->cancelDelete();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
        $this->isEditing = false;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeletion = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset(['item_type_id', 'location_id', 'brand', 'purchase_date', 'sku', 'editingId']);
        $this->status = 'available';
        $this->resetValidation();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.assets')->layout('layouts.app');
    }
}
