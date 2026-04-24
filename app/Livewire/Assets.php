<?php

namespace App\Livewire;

use App\Models\Asset;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Assets extends Component
{
    use WithPagination, WithFileUploads;

    // Form properties matching your assets table
    public $asset_id;
    public $asset_code;
    public $name;
    public $category;
    public $brand;
    public $model;
    public $serial_number;
    public $purchase_date;
    public $purchase_cost;
    public $status = 'active';
    public $condition_status = 'good';
    public $notes;
    public $image;
    public $existing_image;
    
    // UI state
    public $showForm = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $search = '';
    public $showDetailsModal = false;
    public $selectedAsset = null;

    protected $rules = [
        'asset_code' => 'required|string|max:50',
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:100',
        'brand' => 'nullable|string|max:100',
        'model' => 'nullable|string|max:100',
        'serial_number' => 'nullable|string|max:100',
        'purchase_date' => 'nullable|date',
        'purchase_cost' => 'nullable|numeric|min:0',
        'status' => 'required|in:active,in_use,maintenance,retired',
        'condition_status' => 'required|in:good,fair,poor',
        'notes' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'asset_code.required' => 'Asset code is required.',
        'asset_code.unique' => 'This asset code already exists.',
        'name.required' => 'Asset name is required.',
        'serial_number.unique' => 'This serial number already exists.',
        'image.image' => 'File must be an image.',
        'image.max' => 'Image size must not exceed 2MB.',
    ];

    public function getAssetsProperty()
    {
        $query = Asset::query();
        
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('asset_code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('brand', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function save()
    {
        // Add unique validation rule for save
        $this->rules['asset_code'] = 'required|string|max:50|unique:assets,asset_code';
        $this->rules['serial_number'] = 'nullable|string|max:100|unique:assets,serial_number';
        
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $this->image->getClientOriginalName());
            $this->image->storeAs('assets', $imageName, 'public');
            $imagePath = 'assets/' . $imageName;
        }

        Asset::create([
            'asset_code' => $this->asset_code,
            'name' => $this->name,
            'category' => $this->category,
            'department_id' => null,
            'location_id' => null,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'purchase_date' => $this->purchase_date,
            'purchase_cost' => $this->purchase_cost,
            'status' => $this->status,
            'condition_status' => $this->condition_status,
            'notes' => $this->notes,
            'image' => $imagePath,
        ]);

        session()->flash('message', 'Asset created successfully!');
        $this->resetForm();
        $this->showForm = false;
        $this->isEditing = false;
    }

    public function openForm()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showForm = true;
    }

    public function showDetails($id)
    {
        $this->selectedAsset = Asset::with(['department', 'location'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedAsset = null;
    }

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        
        $this->asset_id = $asset->id;
        $this->asset_code = $asset->asset_code;
        $this->name = $asset->name;
        $this->category = $asset->category;
        $this->brand = $asset->brand;
        $this->model = $asset->model;
        $this->serial_number = $asset->serial_number;
        $this->purchase_date = $asset->purchase_date;
        $this->purchase_cost = $asset->purchase_cost;
        $this->status = $asset->status;
        $this->condition_status = $asset->condition_status;
        $this->notes = $asset->notes;
        $this->existing_image = $asset->image;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function update()
    {
        $asset = Asset::findOrFail($this->asset_id);
        
        // Add unique validation with ignore for current ID
        $this->rules['asset_code'] = 'required|string|max:50|unique:assets,asset_code,' . $this->asset_id;
        $this->rules['serial_number'] = 'nullable|string|max:100|unique:assets,serial_number,' . $this->asset_id;
        
        $this->validate();

        $imagePath = $this->existing_image;
        if ($this->image) {
            // Delete old image if exists
            if ($asset->image && Storage::disk('public')->exists($asset->image)) {
                Storage::disk('public')->delete($asset->image);
            }
            // Store new image
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $this->image->getClientOriginalName());
            $this->image->storeAs('assets', $imageName, 'public');
            $imagePath = 'assets/' . $imageName;
        }

        $asset->update([
            'asset_code' => $this->asset_code,
            'name' => $this->name,
            'category' => $this->category,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'purchase_date' => $this->purchase_date,
            'purchase_cost' => $this->purchase_cost,
            'status' => $this->status,
            'condition_status' => $this->condition_status,
            'notes' => $this->notes,
            'image' => $imagePath,
        ]);

        session()->flash('message', 'Asset updated successfully!');
        $this->resetForm();
        $this->showForm = false;
        $this->isEditing = false;
    }

    public function confirmDelete($id)
    {
        $this->asset_id = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $asset = Asset::findOrFail($this->asset_id);
        
        // Delete image if exists
        if ($asset->image && Storage::disk('public')->exists($asset->image)) {
            Storage::disk('public')->delete($asset->image);
        }
        
        $asset->delete();
        
        session()->flash('message', 'Asset deleted successfully!');
        $this->confirmingDeletion = false;
        $this->asset_id = null;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->asset_id = null;
    }

    public function cancelForm()
    {
        $this->resetForm();
        $this->showForm = false;
        $this->isEditing = false;
    }

    private function resetForm()
    {
        $this->reset([
            'asset_id',
            'asset_code',
            'name',
            'category',
            'brand',
            'model',
            'serial_number',
            'purchase_date',
            'purchase_cost',
            'status',
            'condition_status',
            'notes',
            'image',
            'existing_image',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.assets', [
            'assets' => $this->assets,
        ]);
    }
}