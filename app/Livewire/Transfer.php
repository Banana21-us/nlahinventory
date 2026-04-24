<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Location;
use App\Models\AssetMovement;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Transfer extends Component
{
    public $asset_id;
    public $to_department_id;
    public $to_location_id;
    public $remarks;
    
    public $selectedAsset = null;
    public $search = '';
    public $successMessage = '';
    public $showSuccess = false;

    protected $rules = [
        'asset_id' => 'required|exists:assets,id',
        'to_department_id' => 'required|integer',
        'to_location_id' => 'required|exists:locations,id',
        'remarks' => 'nullable|string',
    ];

    protected $messages = [
        'asset_id.required' => 'Please select an asset.',
        'to_department_id.required' => 'Please enter a department ID.',
        'to_location_id.required' => 'Please select a location.',
    ];

    public function getAvailableAssetsProperty()
    {
        return Asset::query()
            ->where('status', 'active')
            ->whereNull('department_id')
            ->whereNull('location_id')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('asset_code', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('serial_number', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAssignedAssetsProperty()
    {
        return Asset::query()
            ->whereNotNull('department_id')
            ->whereNotNull('location_id')
            ->with('location')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getLocationsProperty()
    {
        return Location::orderBy('name')->get();
    }

    public function selectAsset($id)
    {
        $this->selectedAsset = Asset::findOrFail($id);
        $this->asset_id = $id;
        $this->resetErrorBag();
    }

    public function assignAsset()
    {
        $this->validate();

        $asset = Asset::findOrFail($this->asset_id);
        
        // Get current department and location (should be null for new assets)
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
        
        $this->successMessage = 'Asset assigned successfully!';
        $this->showSuccess = true;
        
        // Reset form
        $this->reset(['asset_id', 'to_department_id', 'to_location_id', 'remarks', 'selectedAsset']);
        
        // Auto-hide success message after 3 seconds
        $this->dispatch('hide-success');
    }

    public function resetForm()
    {
        $this->reset(['asset_id', 'to_department_id', 'to_location_id', 'remarks', 'selectedAsset', 'search']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.transfer', [
            'availableAssets' => $this->availableAssets,
            'assignedAssets' => $this->assignedAssets,
            'locations' => $this->locations,
        ]);
    }
}