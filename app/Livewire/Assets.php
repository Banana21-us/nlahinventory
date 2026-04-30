<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Assets extends Component
{
    use WithPagination, WithFileUploads;

    public $asset_id;
    public $asset_code;
    public $name;
    public $category;
    public $brand;
    public $model;
    public $serial_number;
    public $purchase_date;
    public $purchase_cost;
    public $lifespan_years;
    public $end_of_life;
    public $status = 'available';
    public $condition_status = 'good';
    public $notes;
    public $image;
    public $existing_image;
    public $maintenance_department_id;

    public $showForm = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $search = '';

    public $toastMessage = '';
    public $toastError = '';
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
        'lifespan_years' => 'nullable|integer|min:1|max:50',
        'status' => 'required|in:available,in_use,out_of_service,maintenance,disposed,lost',
        'condition_status' => 'required|in:excellent,good,fair,poor,critical,damaged',
        'notes' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'maintenance_department_id' => 'required|exists:departments,id',
    ];

    protected $messages = [
        'asset_code.required' => 'Asset code is required.',
        'asset_code.unique' => 'This asset code already exists.',
        'name.required' => 'Asset name is required.',
        'serial_number.unique' => 'This serial number already exists.',
        'image.image' => 'File must be an image.',
        'image.max' => 'Image size must not exceed 2MB.',
        'lifespan_years.min' => 'Lifespan must be at least 1 year.',
        'lifespan_years.max' => 'Lifespan cannot exceed 50 years.',
        'lifespan_years.integer' => 'Lifespan must be a whole number.',
        'maintenance_department_id.required' => 'Please select a maintenance department.',
    ];

    public function getAssetsProperty()
    {
        $query = Asset::with(['department', 'maintenanceDepartment', 'location']);
        
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

    public function getDepartmentsProperty()
    {
        // Only show Maintenance and MIS departments for maintenance department selection
        return Department::whereIn('name', ['Maintenance', 'MIS'])
            ->orWhere('code', 'MAINT')
            ->orWhere('code', 'MIS')
            ->orderBy('name')
            ->get();
    }

    public function updatedLifespanYears()
    {
        if ($this->purchase_date && $this->lifespan_years) {
            $years = (int) $this->lifespan_years;
            $this->end_of_life = Carbon::parse($this->purchase_date)->addYears($years)->format('Y-m-d');
        } else {
            $this->end_of_life = null;
        }
    }

    public function updatedPurchaseDate()
    {
        if ($this->purchase_date && $this->lifespan_years) {
            $years = (int) $this->lifespan_years;
            $this->end_of_life = Carbon::parse($this->purchase_date)->addYears($years)->format('Y-m-d');
        }
    }

    public function save()
    {
        $this->rules['asset_code'] = 'required|string|max:50|unique:assets,asset_code';
        $this->rules['serial_number'] = 'nullable|string|max:100|unique:assets,serial_number';
        
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $this->image->getClientOriginalName());
            $this->image->storeAs('assets', $imageName, 'public');
            $imagePath = 'assets/' . $imageName;
        }

        $endOfLife = null;
        if ($this->purchase_date && $this->lifespan_years) {
            $years = (int) $this->lifespan_years;
            $endOfLife = Carbon::parse($this->purchase_date)->addYears($years);
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
            'lifespan_years' => $this->lifespan_years,
            'end_of_life' => $endOfLife,
            'status' => $this->status,
            'condition_status' => $this->condition_status,
            'notes' => $this->notes,
            'image' => $imagePath,
            'maintenance_department_id' => $this->maintenance_department_id,
        ]);

        $this->toastMessage = 'Asset created successfully!';
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

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        
        // Check if asset is disposed - prevent editing
        if ($asset->status === 'disposed') {
            $this->toastError = 'Disposed assets cannot be edited.';
            $this->showDetailsModal = false;
            return;
        }
        
        $this->asset_id = $asset->id;
        $this->asset_code = $asset->asset_code;
        $this->name = $asset->name;
        $this->category = $asset->category;
        $this->brand = $asset->brand;
        $this->model = $asset->model;
        $this->serial_number = $asset->serial_number;
        $this->purchase_date = $asset->purchase_date;
        $this->purchase_cost = $asset->purchase_cost;
        $this->lifespan_years = $asset->lifespan_years;
        $this->end_of_life = $asset->end_of_life;
        $this->status = $asset->status;
        $this->condition_status = $asset->condition_status;
        $this->notes = $asset->notes;
        $this->existing_image = $asset->image;
        $this->maintenance_department_id = $asset->maintenance_department_id;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function update()
    {
        $asset = Asset::findOrFail($this->asset_id);
        
        // Check if asset is disposed - prevent update
        if ($asset->status === 'disposed') {
            $this->toastError = 'Disposed assets cannot be updated.';
            $this->resetForm();
            $this->showForm = false;
            $this->isEditing = false;
            return;
        }
        
        $this->rules['asset_code'] = 'required|string|max:50|unique:assets,asset_code,' . $this->asset_id;
        $this->rules['serial_number'] = 'nullable|string|max:100|unique:assets,serial_number,' . $this->asset_id;
        
        $this->validate();

        $imagePath = $this->existing_image;
        if ($this->image) {
            if ($asset->image && Storage::disk('public')->exists($asset->image)) {
                Storage::disk('public')->delete($asset->image);
            }
            $imageName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $this->image->getClientOriginalName());
            $this->image->storeAs('assets', $imageName, 'public');
            $imagePath = 'assets/' . $imageName;
        }

        $endOfLife = $asset->end_of_life;
        if ($this->purchase_date && $this->lifespan_years) {
            $years = (int) $this->lifespan_years;
            $endOfLife = Carbon::parse($this->purchase_date)->addYears($years);
        } elseif (!$this->lifespan_years) {
            $endOfLife = null;
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
            'lifespan_years' => $this->lifespan_years,
            'end_of_life' => $endOfLife,
            'status' => $this->status,
            'condition_status' => $this->condition_status,
            'notes' => $this->notes,
            'image' => $imagePath,
            'maintenance_department_id' => $this->maintenance_department_id,
        ]);

        $this->toastMessage = 'Asset updated successfully!';
        $this->resetForm();
        $this->showForm = false;
        $this->isEditing = false;
    }

    public function confirmDelete($id)
    {
        $asset = Asset::findOrFail($id);
        
        // Check if asset is disposed - prevent deletion or allow with warning
        if ($asset->status === 'disposed') {
            $this->asset_id = $id;
            $this->confirmingDeletion = true;
        } else {
            $this->asset_id = $id;
            $this->confirmingDeletion = true;
        }
    }

    public function delete()
    {
        $asset = Asset::findOrFail($this->asset_id);
        
        if ($asset->image && Storage::disk('public')->exists($asset->image)) {
            Storage::disk('public')->delete($asset->image);
        }
        
        $asset->delete();

        $this->toastMessage = 'Asset deleted successfully!';
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

    public function showDetails($id)
    {
        $this->selectedAsset = Asset::with(['department', 'location', 'maintenanceDepartment'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedAsset = null;
    }

    private function resetForm()
    {
        $this->reset([
            'asset_id', 'asset_code', 'name', 'category', 'brand', 'model',
            'serial_number', 'purchase_date', 'purchase_cost', 'lifespan_years',
            'end_of_life', 'status', 'condition_status', 'notes', 'image',
            'existing_image', 'maintenance_department_id',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.assets', [
            'assets' => $this->assets,
            'departments' => $this->departments,
        ]);
    }
}