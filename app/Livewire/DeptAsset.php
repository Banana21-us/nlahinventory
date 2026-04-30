<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Department;
use App\Models\AssetMovement;
use App\Models\AssetMaintenance;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeptAsset extends Component
{
    use WithPagination;

    public $search = '';
    public $userDepartmentId;
    public $userDepartmentName;
    
    public $showDetailsModal = false;
    public $selectedAsset = null;
    
    // Repair Request Modal
    public $showRepairRequestModal = false;
    public $repair_asset_id;
    public $repair_notes;
    
    // Lost Report Modal
    public $showLostReportModal = false;
    public $lost_asset_id;
    public $lost_notes;

    public function mount()
    {
        $user = Auth::user();
        
        // Get department from user's employment detail through employee
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
            ->with(['location', 'department', 'maintenanceDepartment'])
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

        return $query->orderBy('created_at', 'desc')->paginate(12);
    }

    public function showDetails($id)
    {
        $this->selectedAsset = Asset::with(['location', 'department', 'maintenanceDepartment'])->findOrFail($id);
        
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

    public function openRepairRequestModal($id)
    {
        $asset = Asset::findOrFail($id);
        
        if ($asset->department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to request repair for this asset.');
            return;
        }
        
        $this->repair_asset_id = $asset->id;
        $this->repair_notes = '';
        $this->showRepairRequestModal = true;
    }

    public function closeRepairRequestModal()
    {
        $this->showRepairRequestModal = false;
        $this->reset(['repair_asset_id', 'repair_notes']);
    }
    
    public function openLostReportModal($id)
    {
        $asset = Asset::findOrFail($id);
        
        if ($asset->department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to report this asset as lost.');
            return;
        }
        
        $this->lost_asset_id = $asset->id;
        $this->lost_notes = '';
        $this->showLostReportModal = true;
    }
    
    public function closeLostReportModal()
    {
        $this->showLostReportModal = false;
        $this->reset(['lost_asset_id', 'lost_notes']);
    }

    public function submitRepairRequest()
    {
        $this->validate([
            'repair_notes' => 'required|string|min:5',
        ]);

        $asset = Asset::findOrFail($this->repair_asset_id);
        
        if ($asset->department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to request repair for this asset.');
            $this->closeRepairRequestModal();
            return;
        }
        
        // Update asset status to maintenance and condition to poor
        $asset->update([
            'status' => 'maintenance',
            'condition_status' => 'poor',
        ]);
        
        // Create record in asset_maintenance table
        AssetMaintenance::create([
            'asset_id' => $asset->id,
            'issue_description' => $this->repair_notes,
            'status' => 'pending',
            'maintenance_department_id' => $asset->maintenance_department_id,
            'reported_at' => Carbon::now(),
        ]);
        
        // Record the repair request in movements
        AssetMovement::create([
            'asset_id' => $asset->id,
            'from_department_id' => $asset->department_id,
            'to_department_id' => $asset->department_id,
            'from_location_id' => $asset->location_id,
            'to_location_id' => $asset->location_id,
            'moved_by' => Auth::id(),
            'remarks' => "REPAIR REQUESTED: {$this->repair_notes}",
        ]);
        
        session()->flash('message', 'Repair request submitted successfully! Maintenance department has been notified.');
        $this->closeRepairRequestModal();
    }
    
    public function submitLostReport()
    {
        $this->validate([
            'lost_notes' => 'required|string|min:5',
        ]);

        $asset = Asset::findOrFail($this->lost_asset_id);
        
        if ($asset->department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to report this asset as lost.');
            $this->closeLostReportModal();
            return;
        }
        
        // Update asset status to lost
        $asset->update([
            'status' => 'lost',
            'condition_status' => 'critical',
        ]);
        
        // Create record in asset_maintenance table
        AssetMaintenance::create([
            'asset_id' => $asset->id,
            'issue_description' => $this->lost_notes,
            'repair_action' => 'Asset reported as lost',
            'status' => 'cancelled',
            'maintenance_department_id' => $asset->maintenance_department_id,
            'reported_at' => Carbon::now(),
        ]);
        
        // Record the lost report in movements
        AssetMovement::create([
            'asset_id' => $asset->id,
            'from_department_id' => $asset->department_id,
            'to_department_id' => $asset->department_id,
            'from_location_id' => $asset->location_id,
            'to_location_id' => $asset->location_id,
            'moved_by' => Auth::id(),
            'remarks' => "ASSET REPORTED AS LOST: {$this->lost_notes}",
        ]);
        
        session()->flash('message', 'Asset has been marked as lost. This will be recorded in the asset history.');
        $this->closeLostReportModal();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.dept-asset', [
            'assets' => $this->assets,
        ]);
    }
}