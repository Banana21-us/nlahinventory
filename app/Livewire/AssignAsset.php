<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

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

    public $filter = 'all';

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

    #[Computed]
    public function getAllAssetsProperty()
    {
        $query = Asset::query()
            ->with(['department', 'location'])
            // REMOVED the filter that excluded disposed/lost assets
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('asset_code', 'like', '%'.$this->search.'%')
                        ->orWhere('name', 'like', '%'.$this->search.'%')
                        ->orWhere('brand', 'like', '%'.$this->search.'%')
                        ->orWhere('serial_number', 'like', '%'.$this->search.'%');
                });
            });

        if ($this->filter === 'assigned') {
            $query->whereNotNull('department_id')->whereNotNull('location_id');
        } elseif ($this->filter === 'unassigned') {
            $query->whereNull('department_id')->whereNull('location_id');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    #[Computed]
    public function getDepartmentsProperty()
    {
        return Department::orderBy('name')->get();
    }

    #[Computed]
    public function getLocationsProperty()
    {
        return Location::orderBy('name')->get();
    }

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

        session()->flash('message', 'Asset assigned successfully!');
        $this->closeModal();
    }

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

        $asset->update([
            'department_id' => null,
            'location_id' => null,
            'status' => 'available',
        ]);

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
