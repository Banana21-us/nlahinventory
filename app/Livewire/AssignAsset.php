<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Department;
use App\Models\AssetMovement;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignAsset extends Component
{
    public $selectedAsset = null;
    public $asset_id;
    public $to_department_id;
    public $to_location_id;
    public $remarks;
    public $showModal = false;
    public $showTransferModal = false;
    public $showUnassignModal = false;
    public $search = '';
    public $filter = 'all'; // all, assigned, unassigned

    protected $rules = [
        'asset_id' => 'required|exists:assets,id',
        'to_department_id' => 'required|exists:departments,id',
        'to_location_id' => 'required|exists:locations,id',
        'remarks' => 'nullable|string',
    ];

    protected $messages = [
        'asset_id.required' => 'Please select an asset.',
        'to_department_id.required' => 'Please select a department.',
        'to_location_id.required' => 'Please select a location.',
    ];

    public function getAllAssetsProperty()
    {
        $query = Asset::query()
            ->with(['department', 'location'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('asset_code', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('brand', 'like', '%' . $this->search . '%')
                        ->orWhere('serial_number', 'like', '%' . $this->search . '%');
                });
            });

        // Apply filter
        if ($this->filter === 'assigned') {
            $query->whereNotNull('department_id')->whereNotNull('location_id');
        } elseif ($this->filter === 'unassigned') {
            $query->whereNull('department_id')->whereNull('location_id');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getDepartmentsProperty()
    {
        return Department::orderBy('name')->get();
    }

    public function getLocationsProperty()
    {
        return Location::orderBy('name')->get();
    }

    // ========== ASSIGN METHODS ==========
    public function openAssignModal($assetId)
    {
        $this->selectedAsset = Asset::findOrFail($assetId);
        $this->asset_id = $assetId;
        $this->showModal = true;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['selectedAsset', 'asset_id', 'to_department_id', 'to_location_id', 'remarks']);
        $this->resetErrorBag();
    }

    public function assignAsset()
    {
        $this->validate();

        $asset = Asset::findOrFail($this->asset_id);
        
        // Get current department and location
        $from_department_id = $asset->department_id;
        $from_location_id = $asset->location_id;
        
        // Update asset with new department and location
        $asset->update([
            'department_id' => $this->to_department_id,
            'location_id' => $this->to_location_id,
            'status' => 'in_use',
        ]);
        
        // Record the movement
        AssetMovement::create([
            'asset_id' => $this->asset_id,
            'from_department_id' => $from_department_id,
            'to_department_id' => $this->to_department_id,
            'from_location_id' => $from_location_id,
            'to_location_id' => $this->to_location_id,
            'moved_by' => Auth::id(),
            'remarks' => $this->remarks,
        ]);
        
        session()->flash('message', 'Asset assigned successfully!');
        $this->closeModal();
    }

    // ========== TRANSFER METHODS ==========
    public function openTransferModal($assetId)
    {
        $this->selectedAsset = Asset::findOrFail($assetId);
        $this->asset_id = $assetId;
        $this->showTransferModal = true;
        $this->resetErrorBag();
    }

    public function closeTransferModal()
    {
        $this->showTransferModal = false;
        $this->reset(['selectedAsset', 'asset_id', 'to_department_id', 'to_location_id', 'remarks']);
        $this->resetErrorBag();
    }

    public function transferAsset()
    {
        $this->validate();
        
        $asset = Asset::findOrFail($this->asset_id);
        
        $from_department_id = $asset->department_id;
        $from_location_id = $asset->location_id;
        
        $asset->update([
            'department_id' => $this->to_department_id,
            'location_id' => $this->to_location_id,
            'status' => 'in_use',
        ]);
        
        AssetMovement::create([
            'asset_id' => $this->asset_id,
            'from_department_id' => $from_department_id,
            'to_department_id' => $this->to_department_id,
            'from_location_id' => $from_location_id,
            'to_location_id' => $this->to_location_id,
            'moved_by' => Auth::id(),
            'remarks' => $this->remarks,
        ]);
        
        session()->flash('message', 'Asset transferred successfully!');
        $this->closeTransferModal();
    }

    // ========== UNASSIGN METHODS ==========
    public function confirmUnassign($assetId)
    {
        $this->selectedAsset = Asset::findOrFail($assetId);
        $this->asset_id = $assetId;
        $this->showUnassignModal = true;
    }

    public function closeUnassignModal()
    {
        $this->showUnassignModal = false;
        $this->selectedAsset = null;
        $this->asset_id = null;
    }

    public function unassignAsset()
    {
        $asset = Asset::findOrFail($this->asset_id);
        
        $from_department_id = $asset->department_id;
        $from_location_id = $asset->location_id;
        
        // Update asset to unassigned
        $asset->update([
            'department_id' => null,
            'location_id' => null,
            'status' => 'active',
        ]);
        
        // Record the unassignment movement (to_department_id and to_location_id will be NULL)
        AssetMovement::create([
            'asset_id' => $this->asset_id,
            'from_department_id' => $from_department_id,
            'to_department_id' => null,
            'from_location_id' => $from_location_id,
            'to_location_id' => null,
            'moved_by' => Auth::id(),
            'remarks' => 'Asset unassigned from department and location',
        ]);
        
        session()->flash('message', 'Asset unassigned successfully!');
        $this->closeUnassignModal();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.assign-asset', [
            'allAssets' => $this->allAssets,
            'departments' => $this->departments,
            'locations' => $this->locations,
        ]);
    }
}