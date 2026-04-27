<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Department;
use App\Models\AssetMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeptAsset extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $userDepartmentId;
    public $userDepartmentName;
    
    public $showDetailsModal = false;
    public $selectedAsset = null;
    
    public $showUpdateModal = false;
    public $update_asset_id;
    public $update_status;
    public $update_condition_status;
    public $update_remarks;

    public function mount()
    {
        $user = Auth::user();
        $employmentDetail = $user->employmentDetail;
        
        if ($employmentDetail && $employmentDetail->department_id) {
            $this->userDepartmentId = $employmentDetail->department_id;
            $department = Department::find($this->userDepartmentId);
            $this->userDepartmentName = $department->name ?? 'Your Department';
        } else {
            $this->userDepartmentName = 'No Department Assigned';
            session()->flash('error', 'You are not assigned to any department.');
        }
    }

    public function getAssetsProperty()
    {
        if (!$this->userDepartmentId) {
            return collect([]);
        }
        
        $query = Asset::query()
            ->with(['location', 'department'])
            ->where('department_id', $this->userDepartmentId)
            ->whereNotNull('department_id')
            ->whereNotNull('location_id');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('asset_code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('brand', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter === 'in_use') {
            $query->where('status', 'in_use');
        } elseif ($this->filter === 'maintenance') {
            $query->where('status', 'maintenance');
        } elseif ($this->filter === 'retired') {
            $query->where('status', 'retired');
        }

        return $query->orderBy('created_at', 'desc')->paginate(12);
    }

    public function showDetails($id)
    {
        $this->selectedAsset = Asset::with(['location', 'department'])->findOrFail($id);
        
        if ($this->selectedAsset->department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to view this asset.');
            return;
        }
        
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedAsset = null;
    }

    public function openUpdateModal($id)
    {
        $asset = Asset::findOrFail($id);
        
        if ($asset->department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to update this asset.');
            return;
        }
        
        $this->update_asset_id = $asset->id;
        $this->update_status = $asset->status;
        $this->update_condition_status = $asset->condition_status;
        $this->update_remarks = '';
        $this->showUpdateModal = true;
    }

    public function closeUpdateModal()
    {
        $this->showUpdateModal = false;
        $this->reset(['update_asset_id', 'update_status', 'update_condition_status', 'update_remarks']);
    }

    public function updateAssetStatus()
    {
        $asset = Asset::findOrFail($this->update_asset_id);
        
        if ($asset->department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to update this asset.');
            $this->closeUpdateModal();
            return;
        }
        
        $old_status = $asset->status;
        $old_condition = $asset->condition_status;
        
        $asset->update([
            'status' => $this->update_status,
            'condition_status' => $this->update_condition_status,
        ]);
        
        AssetMovement::create([
            'asset_id' => $asset->id,
            'from_department_id' => $asset->department_id,
            'to_department_id' => $asset->department_id,
            'from_location_id' => $asset->location_id,
            'to_location_id' => $asset->location_id,
            'moved_by' => Auth::id(),
            'remarks' => "Status changed from {$old_status} to {$this->update_status}. Condition changed from {$old_condition} to {$this->update_condition_status}. " . $this->update_remarks,
        ]);
        
        session()->flash('message', 'Asset status and condition updated successfully!');
        $this->closeUpdateModal();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filter']);
        $this->resetPage();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.dept-asset', [
            'assets' => $this->assets,
        ]);
    }
}