<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Repair extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all'; // all, pending, completed
    public $userDepartmentId;
    public $userDepartmentName;
    
    // For details modal
    public $showDetailsModal = false;
    public $selectedAsset = null;
    
    // For repair update modal
    public $showRepairModal = false;
    public $repair_asset_id;
    public $repair_issue;
    public $repair_cost;
    public $repair_notes;
    public $repair_status = 'completed';

    public function mount()
    {
        $user = Auth::user();
        
        // Get department from user's employment detail through employee
        $employmentDetail = $user->employmentDetail;
        
        if ($employmentDetail && $employmentDetail->department_id) {
            $this->userDepartmentId = $employmentDetail->department_id;
            $department = \App\Models\Department::find($this->userDepartmentId);
            $this->userDepartmentName = $department->name ?? 'Your Department';
        } else {
            $this->userDepartmentName = 'All Departments';
        }
    }

    public function getAssetsProperty()
    {
        $query = Asset::query()
            ->with(['location', 'department'])
            ->where(function ($q) {
                // Assets that need repair: status is 'maintenance' OR condition is 'fair' or 'poor'
                $q->where('status', 'maintenance')
                  ->orWhere('condition_status', 'fair')
                  ->orWhere('condition_status', 'poor');
            });

        // Filter by department if user has a department
        if ($this->userDepartmentId) {
            $query->where('department_id', $this->userDepartmentId);
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('asset_code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('brand', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filter
        if ($this->filter === 'pending') {
            $query->where('status', 'maintenance');
        } elseif ($this->filter === 'completed') {
            // For completed repairs, we don't have a separate status
            // This would typically be handled by a repair records table
        }

        return $query->orderBy('created_at', 'desc')->paginate(12);
    }

    public function showDetails($id)
    {
        $this->selectedAsset = Asset::with(['location', 'department'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedAsset = null;
    }

    public function openRepairModal($id)
    {
        $asset = Asset::findOrFail($id);
        
        $this->repair_asset_id = $asset->id;
        $this->repair_issue = '';
        $this->repair_cost = null;
        $this->repair_notes = '';
        $this->repair_status = 'completed';
        $this->showRepairModal = true;
    }

    public function closeRepairModal()
    {
        $this->showRepairModal = false;
        $this->reset(['repair_asset_id', 'repair_issue', 'repair_cost', 'repair_notes', 'repair_status']);
    }

    public function completeRepair()
    {
        $asset = Asset::findOrFail($this->repair_asset_id);
        
        $old_status = $asset->status;
        $old_condition = $asset->condition_status;
        
        // Update asset status back to in_use and condition to good
        $asset->update([
            'status' => 'in_use',
            'condition_status' => 'good',
        ]);
        
        // Record the repair in movements
        AssetMovement::create([
            'asset_id' => $this->repair_asset_id,
            'from_department_id' => $asset->department_id,
            'to_department_id' => $asset->department_id,
            'from_location_id' => $asset->location_id,
            'to_location_id' => $asset->location_id,
            'moved_by' => Auth::id(),
            'remarks' => "REPAIR COMPLETED: Issue: {$this->repair_issue}. Cost: ₱" . number_format($this->repair_cost, 2) . ". Notes: {$this->repair_notes}",
        ]);
        
        session()->flash('message', 'Asset repair completed successfully!');
        $this->closeRepairModal();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filter']);
        $this->resetPage();
    }

    public function render()
    {
        return view('pages.RepairAssets.repair', [
            'assets' => $this->assets,
        ]);
    }
}