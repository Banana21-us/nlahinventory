<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\AssetMaintenance;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Repair extends Component
{
    use WithPagination;

    public $search = '';
    public $userDepartmentId;
    public $userDepartmentName;
    
    public $showDetailsModal = false;
    public $selectedAsset = null;
    
    public $showRepairModal = false;
    public $repair_asset_id;
    public $repair_issue;
    public $repair_cost;
    public $repair_notes;
    public $repair_action = 'fix'; // fix or dispose

    public function mount()
    {
        $user = Auth::user();
        
        $employmentDetail = $user->employmentDetail;
        
        if ($employmentDetail && $employmentDetail->department_id) {
            $this->userDepartmentId = $employmentDetail->department_id;
            $department = \App\Models\Department::find($this->userDepartmentId);
            $this->userDepartmentName = $department->name ?? 'Maintenance Department';
        } else {
            $this->userDepartmentName = 'No Department Assigned';
        }
    }

    public function getAssetsProperty()
    {
        $query = Asset::query()
            ->with(['location', 'department', 'maintenanceDepartment'])
            ->where(function ($q) {
                $q->where('status', 'maintenance')
                  ->orWhere('status', 'out_of_service')
                  ->orWhere('condition_status', 'fair')
                  ->orWhere('condition_status', 'poor');
            })
            ->where('maintenance_department_id', $this->userDepartmentId);

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
        
        if ($this->selectedAsset->maintenance_department_id != $this->userDepartmentId) {
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

    public function startRepair($id)
    {
        $asset = Asset::findOrFail($id);
        
        if ($asset->maintenance_department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to repair this asset.');
            return;
        }
        
        // Update existing maintenance record or create if not exists
        $maintenance = AssetMaintenance::where('asset_id', $asset->id)->first();
        
        if ($maintenance) {
            // Update existing record
            $maintenance->update([
                'status' => 'in_progress',
                'started_at' => Carbon::now(),
            ]);
        } else {
            // Create new record if doesn't exist
            AssetMaintenance::create([
                'asset_id' => $asset->id,
                'issue_description' => 'Repair started',
                'status' => 'in_progress',
                'maintenance_department_id' => $asset->maintenance_department_id,
                'reported_at' => Carbon::now(),
                'started_at' => Carbon::now(),
            ]);
        }
        
        // Update asset status to out_of_service (in progress)
        $asset->update([
            'status' => 'out_of_service',
        ]);
        
        // Record the start of repair in movements
        AssetMovement::create([
            'asset_id' => $asset->id,
            'from_department_id' => $asset->department_id,
            'to_department_id' => $asset->department_id,
            'from_location_id' => $asset->location_id,
            'to_location_id' => $asset->location_id,
            'moved_by' => Auth::id(),
            'remarks' => "REPAIR STARTED - Asset is now in progress",
        ]);
        
        session()->flash('message', 'Repair started! Asset status changed to "In Progress".');
    }

    public function openCompleteModal($id)
    {
        $asset = Asset::findOrFail($id);
        
        if ($asset->maintenance_department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to complete this repair.');
            return;
        }
        
        $this->repair_asset_id = $asset->id;
        $this->repair_issue = '';
        $this->repair_cost = null;
        $this->repair_notes = '';
        $this->repair_action = 'fix';
        $this->showRepairModal = true;
    }

    public function closeRepairModal()
    {
        $this->showRepairModal = false;
        $this->reset(['repair_asset_id', 'repair_issue', 'repair_cost', 'repair_notes', 'repair_action']);
    }

    public function completeRepair()
    {
        $this->validate([
            'repair_issue' => 'required|string|min:3',
            'repair_cost' => 'nullable|numeric|min:0',
            'repair_notes' => 'nullable|string',
            'repair_action' => 'required|in:fix,dispose',
        ]);

        $asset = Asset::findOrFail($this->repair_asset_id);
        
        if ($asset->maintenance_department_id != $this->userDepartmentId) {
            session()->flash('error', 'You are not authorized to complete this repair.');
            $this->closeRepairModal();
            return;
        }
        
        // Update the existing maintenance record
        $maintenance = AssetMaintenance::where('asset_id', $asset->id)->first();
        
        if ($this->repair_action === 'fix') {
            // Fix the asset - restore to in_use and good condition
            $asset->update([
                'status' => 'in_use',
                'condition_status' => 'good',
            ]);
            
            if ($maintenance) {
                $maintenance->update([
                    'status' => 'completed',
                    'repair_action' => $this->repair_issue, // Store issue in repair_action
                    'cost' => $this->repair_cost ?? 0,
                    'completed_at' => Carbon::now(),
                ]);
            }
            
            // Record the action in movements
            AssetMovement::create([
                'asset_id' => $this->repair_asset_id,
                'from_department_id' => $asset->department_id,
                'to_department_id' => $asset->department_id,
                'from_location_id' => $asset->location_id,
                'to_location_id' => $asset->location_id,
                'moved_by' => Auth::id(),
                'remarks' => "REPAIR COMPLETED: Issue: {$this->repair_issue}. Cost: ₱" . number_format($this->repair_cost ?? 0, 2) . ". Notes: {$this->repair_notes}",
            ]);
            
            session()->flash('message', 'Asset repair completed successfully!');
            
        } else {
            // Dispose the asset
            $asset->update([
                'status' => 'disposed',
                'condition_status' => 'damaged',
            ]);
            
            if ($maintenance) {
                $maintenance->update([
                    'status' => 'cancelled',
                    'repair_action' => 'DISPOSED: ' . $this->repair_issue, // Store disposal reason in repair_action
                    'completed_at' => Carbon::now(),
                ]);
            }
            
            // Record the action in movements
            AssetMovement::create([
                'asset_id' => $this->repair_asset_id,
                'from_department_id' => $asset->department_id,
                'to_department_id' => $asset->department_id,
                'from_location_id' => $asset->location_id,
                'to_location_id' => $asset->location_id,
                'moved_by' => Auth::id(),
                'remarks' => "ASSET DISPOSED: Reason: {$this->repair_issue}. Notes: {$this->repair_notes}",
            ]);
            
            session()->flash('message', 'Asset has been disposed.');
        }
        
        $this->closeRepairModal();
    }

    public function clearFilters()
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.repair', [
            'assets' => $this->assets,
        ]);
    }
}