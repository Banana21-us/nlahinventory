<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AssetManagement extends Component
{
    use WithPagination, WithFileUploads;

    public string $activeTab = 'inventory';

    // ── INVENTORY ──────────────────────────────────────────────
    public $inv_id;
    public string $inv_asset_code = '';
    public string $inv_name = '';
    public ?string $inv_category = null;
    public ?string $inv_brand = null;
    public ?string $inv_model = null;
    public ?string $inv_serial_number = null;
    public ?string $inv_purchase_date = null;
    public ?float $inv_purchase_cost = null;
    public string $inv_status = 'active';
    public string $inv_condition = 'good';
    public ?string $inv_notes = null;
    public $inv_image = null;
    public ?string $inv_existing_image = null;
    public bool $inv_show_form = false;
    public bool $inv_editing = false;
    public bool $inv_confirm_delete = false;
    public string $inv_search = '';

    // ── ASSIGN ─────────────────────────────────────────────────
    public $as_asset = null;
    public $as_asset_id = null;
    public $as_dept_id = null;
    public $as_loc_id = null;
    public ?string $as_remarks = null;
    public bool $as_show_assign = false;
    public bool $as_show_transfer = false;
    public bool $as_show_unassign = false;
    public string $as_search = '';
    public string $as_filter = 'all';

    // ── DEPT ───────────────────────────────────────────────────
    public $dept_id = null;
    public string $dept_name = '';
    public string $dept_search = '';
    public string $dept_filter = 'all';
    public bool $dept_show_detail = false;
    public $dept_selected = null;
    public bool $dept_show_update = false;
    public $dept_upd_id = null;
    public string $dept_upd_status = '';
    public string $dept_upd_condition = '';
    public string $dept_upd_remarks = '';

    // ── REPAIR ─────────────────────────────────────────────────
    public string $rep_search = '';
    public string $rep_filter = 'all';
    public bool $rep_show_detail = false;
    public $rep_selected = null;
    public bool $rep_show_modal = false;
    public $rep_asset_id = null;
    public string $rep_issue = '';
    public $rep_cost = null;
    public string $rep_notes = '';

    public function mount(): void
    {
        $detail = Auth::user()->employmentDetail;
        if ($detail?->department_id) {
            $this->dept_id = $detail->department_id;
            $this->dept_name = Department::find($detail->department_id)?->name ?? 'Your Department';
        } else {
            $this->dept_name = 'No Department Assigned';
        }
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage('inv_page');
        $this->resetPage('dept_page');
        $this->resetPage('rep_page');
    }

    // ── INVENTORY ──────────────────────────────────────────────

    public function getInventoryAssetsProperty()
    {
        return Asset::query()
            ->with(['department', 'location'])
            ->when($this->inv_search, fn ($q) => $q->where(function ($s) {
                $s->where('asset_code', 'like', "%{$this->inv_search}%")
                    ->orWhere('name', 'like', "%{$this->inv_search}%")
                    ->orWhere('brand', 'like', "%{$this->inv_search}%")
                    ->orWhere('serial_number', 'like', "%{$this->inv_search}%")
                    ->orWhere('category', 'like', "%{$this->inv_search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'inv_page');
    }

    public function invOpen(): void
    {
        $this->invReset();
        $this->inv_show_form = true;
    }

    public function invCancel(): void
    {
        $this->invReset();
        $this->inv_show_form = false;
    }

    public function invSave(): void
    {
        $this->validate([
            'inv_asset_code'   => 'required|string|max:50|unique:assets,asset_code',
            'inv_name'         => 'required|string|max:255',
            'inv_category'     => 'nullable|string|max:100',
            'inv_brand'        => 'nullable|string|max:100',
            'inv_model'        => 'nullable|string|max:100',
            'inv_serial_number' => 'nullable|string|max:100|unique:assets,serial_number',
            'inv_purchase_date' => 'nullable|date',
            'inv_purchase_cost' => 'nullable|numeric|min:0',
            'inv_status'       => 'required|in:active,in_use,maintenance,retired',
            'inv_condition'    => 'required|in:good,fair,poor',
            'inv_notes'        => 'nullable|string',
            'inv_image'        => 'nullable|image|max:2048',
        ]);

        $path = $this->inv_image ? 'assets/' . $this->storeImage($this->inv_image) : null;

        Asset::create([
            'asset_code'       => $this->inv_asset_code,
            'name'             => $this->inv_name,
            'category'         => $this->inv_category,
            'brand'            => $this->inv_brand,
            'model'            => $this->inv_model,
            'serial_number'    => $this->inv_serial_number,
            'purchase_date'    => $this->inv_purchase_date,
            'purchase_cost'    => $this->inv_purchase_cost,
            'status'           => $this->inv_status,
            'condition_status' => $this->inv_condition,
            'notes'            => $this->inv_notes,
            'image'            => $path,
        ]);

        session()->flash('message', 'Asset created successfully.');
        $this->invReset();
        $this->inv_show_form = false;
    }

    public function invEdit(int $id): void
    {
        $a = Asset::findOrFail($id);
        $this->fill([
            'inv_id'             => $a->id,
            'inv_asset_code'     => $a->asset_code,
            'inv_name'           => $a->name,
            'inv_category'       => $a->category,
            'inv_brand'          => $a->brand,
            'inv_model'          => $a->model,
            'inv_serial_number'  => $a->serial_number,
            'inv_purchase_date'  => $a->purchase_date?->format('Y-m-d'),
            'inv_purchase_cost'  => $a->purchase_cost,
            'inv_status'         => $a->status,
            'inv_condition'      => $a->condition_status,
            'inv_notes'          => $a->notes,
            'inv_existing_image' => $a->image,
        ]);
        $this->inv_editing   = true;
        $this->inv_show_form = true;
    }

    public function invUpdate(): void
    {
        $this->validate([
            'inv_asset_code'    => 'required|string|max:50|unique:assets,asset_code,' . $this->inv_id,
            'inv_name'          => 'required|string|max:255',
            'inv_category'      => 'nullable|string|max:100',
            'inv_brand'         => 'nullable|string|max:100',
            'inv_model'         => 'nullable|string|max:100',
            'inv_serial_number' => 'nullable|string|max:100|unique:assets,serial_number,' . $this->inv_id,
            'inv_purchase_date' => 'nullable|date',
            'inv_purchase_cost' => 'nullable|numeric|min:0',
            'inv_status'        => 'required|in:active,in_use,maintenance,retired',
            'inv_condition'     => 'required|in:good,fair,poor',
            'inv_notes'         => 'nullable|string',
            'inv_image'         => 'nullable|image|max:2048',
        ]);

        $a    = Asset::findOrFail($this->inv_id);
        $path = $this->inv_existing_image;

        if ($this->inv_image) {
            if ($a->image) Storage::disk('public')->delete($a->image);
            $path = 'assets/' . $this->storeImage($this->inv_image);
        }

        $a->update([
            'asset_code'       => $this->inv_asset_code,
            'name'             => $this->inv_name,
            'category'         => $this->inv_category,
            'brand'            => $this->inv_brand,
            'model'            => $this->inv_model,
            'serial_number'    => $this->inv_serial_number,
            'purchase_date'    => $this->inv_purchase_date,
            'purchase_cost'    => $this->inv_purchase_cost,
            'status'           => $this->inv_status,
            'condition_status' => $this->inv_condition,
            'notes'            => $this->inv_notes,
            'image'            => $path,
        ]);

        session()->flash('message', 'Asset updated successfully.');
        $this->invReset();
        $this->inv_show_form = false;
    }

    public function invConfirmDelete(int $id): void
    {
        $this->inv_id             = $id;
        $this->inv_confirm_delete = true;
    }

    public function invDelete(): void
    {
        $a = Asset::findOrFail($this->inv_id);
        if ($a->image) Storage::disk('public')->delete($a->image);
        $a->delete();
        session()->flash('message', 'Asset deleted.');
        $this->inv_confirm_delete = false;
        $this->inv_id             = null;
    }

    public function invCancelDelete(): void
    {
        $this->inv_confirm_delete = false;
        $this->inv_id             = null;
    }

    private function invReset(): void
    {
        $this->reset([
            'inv_id', 'inv_asset_code', 'inv_name', 'inv_category', 'inv_brand',
            'inv_model', 'inv_serial_number', 'inv_purchase_date', 'inv_purchase_cost',
            'inv_notes', 'inv_image', 'inv_existing_image', 'inv_editing',
        ]);
        $this->inv_status    = 'active';
        $this->inv_condition = 'good';
        $this->resetErrorBag();
    }

    // ── ASSIGN ─────────────────────────────────────────────────

    public function getAssignAssetsProperty()
    {
        $q = Asset::with(['department', 'location'])
            ->when($this->as_search, fn ($q) => $q->where(function ($s) {
                $s->where('asset_code', 'like', "%{$this->as_search}%")
                    ->orWhere('name', 'like', "%{$this->as_search}%");
            }));

        if ($this->as_filter === 'assigned') $q->whereNotNull('department_id');
        elseif ($this->as_filter === 'unassigned') $q->whereNull('department_id');

        return $q->orderByDesc('created_at')->get();
    }

    public function getDepartmentsProperty()
    {
        return Department::orderBy('name')->get();
    }

    public function getLocationsProperty()
    {
        return Location::orderBy('name')->get();
    }

    public function asOpenAssign(int $id): void
    {
        $this->as_asset    = Asset::findOrFail($id);
        $this->as_asset_id = $id;
        $this->as_show_assign = true;
    }

    public function asOpenTransfer(int $id): void
    {
        $this->as_asset    = Asset::findOrFail($id);
        $this->as_asset_id = $id;
        $this->as_show_transfer = true;
    }

    public function asConfirmUnassign(int $id): void
    {
        $this->as_asset    = Asset::findOrFail($id);
        $this->as_asset_id = $id;
        $this->as_show_unassign = true;
    }

    public function asClose(): void
    {
        $this->reset(['as_asset', 'as_asset_id', 'as_dept_id', 'as_loc_id', 'as_remarks']);
        $this->as_show_assign = $this->as_show_transfer = $this->as_show_unassign = false;
        $this->resetErrorBag();
    }

    public function asAssign(): void
    {
        $this->validate([
            'as_asset_id' => 'required|exists:assets,id',
            'as_dept_id'  => 'required|exists:departments,id',
            'as_loc_id'   => 'required|exists:locations,id',
        ]);
        $this->recordMovement($this->as_asset_id, $this->as_dept_id, $this->as_loc_id, $this->as_remarks);
        Asset::findOrFail($this->as_asset_id)->update([
            'department_id' => $this->as_dept_id,
            'location_id'   => $this->as_loc_id,
            'status'        => 'in_use',
        ]);
        session()->flash('message', 'Asset assigned.');
        $this->asClose();
    }

    public function asTransfer(): void
    {
        $this->validate([
            'as_asset_id' => 'required|exists:assets,id',
            'as_dept_id'  => 'required|exists:departments,id',
            'as_loc_id'   => 'required|exists:locations,id',
        ]);
        $this->recordMovement($this->as_asset_id, $this->as_dept_id, $this->as_loc_id, $this->as_remarks);
        Asset::findOrFail($this->as_asset_id)->update([
            'department_id' => $this->as_dept_id,
            'location_id'   => $this->as_loc_id,
        ]);
        session()->flash('message', 'Asset transferred.');
        $this->asClose();
    }

    public function asUnassign(): void
    {
        $a = Asset::findOrFail($this->as_asset_id);
        $this->recordMovement($this->as_asset_id, null, null, 'Unassigned');
        $a->update(['department_id' => null, 'location_id' => null, 'status' => 'active']);
        session()->flash('message', 'Asset unassigned.');
        $this->asClose();
    }

    // ── DEPT ───────────────────────────────────────────────────

    public function getDeptAssetsProperty()
    {
        if (! $this->dept_id) {
            return Asset::query()->whereRaw('0=1')->paginate(12, ['*'], 'dept_page');
        }

        $q = Asset::with(['location', 'department'])
            ->where('department_id', $this->dept_id)
            ->whereNotNull('location_id')
            ->when($this->dept_search, fn ($q) => $q->where(function ($s) {
                $s->where('asset_code', 'like', "%{$this->dept_search}%")
                    ->orWhere('name', 'like', "%{$this->dept_search}%");
            }));

        if ($this->dept_filter === 'in_use') $q->where('status', 'in_use');
        elseif ($this->dept_filter === 'maintenance') $q->where('status', 'maintenance');
        elseif ($this->dept_filter === 'retired') $q->where('status', 'retired');

        return $q->orderByDesc('created_at')->paginate(12, ['*'], 'dept_page');
    }

    public function deptShowDetail(int $id): void
    {
        $this->dept_selected  = Asset::with(['location', 'department'])->findOrFail($id);
        $this->dept_show_detail = true;
    }

    public function deptCloseDetail(): void
    {
        $this->dept_show_detail = false;
        $this->dept_selected    = null;
    }

    public function deptOpenUpdate(int $id): void
    {
        $a = Asset::findOrFail($id);
        $this->dept_upd_id        = $a->id;
        $this->dept_upd_status    = $a->status;
        $this->dept_upd_condition = $a->condition_status;
        $this->dept_upd_remarks   = '';
        $this->dept_show_update   = true;
    }

    public function deptCloseUpdate(): void
    {
        $this->dept_show_update = false;
        $this->reset(['dept_upd_id', 'dept_upd_status', 'dept_upd_condition', 'dept_upd_remarks']);
    }

    public function deptUpdateStatus(): void
    {
        $a   = Asset::findOrFail($this->dept_upd_id);
        $old = "{$a->status}/{$a->condition_status}";
        $a->update(['status' => $this->dept_upd_status, 'condition_status' => $this->dept_upd_condition]);
        $this->recordMovement(
            $a->id, $a->department_id, $a->location_id,
            "Status update {$old} → {$this->dept_upd_status}/{$this->dept_upd_condition}. {$this->dept_upd_remarks}"
        );
        session()->flash('message', 'Asset status updated.');
        $this->deptCloseUpdate();
    }

    // ── REPAIR ─────────────────────────────────────────────────

    public function getRepairAssetsProperty()
    {
        $q = Asset::with(['location', 'department'])
            ->where(fn ($q) => $q->where('status', 'maintenance')
                ->orWhere('condition_status', 'fair')
                ->orWhere('condition_status', 'poor'))
            ->when($this->dept_id, fn ($q) => $q->where('department_id', $this->dept_id))
            ->when($this->rep_search, fn ($q) => $q->where(function ($s) {
                $s->where('asset_code', 'like', "%{$this->rep_search}%")
                    ->orWhere('name', 'like', "%{$this->rep_search}%");
            }));

        if ($this->rep_filter === 'pending') $q->where('status', 'maintenance');

        return $q->orderByDesc('created_at')->paginate(12, ['*'], 'rep_page');
    }

    public function repShowDetail(int $id): void
    {
        $this->rep_selected   = Asset::with(['location', 'department'])->findOrFail($id);
        $this->rep_show_detail = true;
    }

    public function repCloseDetail(): void
    {
        $this->rep_show_detail = false;
        $this->rep_selected    = null;
    }

    public function repOpenModal(int $id): void
    {
        $this->rep_asset_id = $id;
        $this->rep_issue = $this->rep_notes = '';
        $this->rep_cost  = null;
        $this->rep_show_modal = true;
    }

    public function repCloseModal(): void
    {
        $this->rep_show_modal = false;
        $this->reset(['rep_asset_id', 'rep_issue', 'rep_cost', 'rep_notes']);
    }

    public function repComplete(): void
    {
        $this->validate(['rep_issue' => 'required|string|max:500']);

        $a = Asset::findOrFail($this->rep_asset_id);
        $a->update(['status' => 'in_use', 'condition_status' => 'good']);
        $this->recordMovement(
            $a->id, $a->department_id, $a->location_id,
            "REPAIR DONE: {$this->rep_issue}. Cost: ₱" . number_format((float) $this->rep_cost, 2) . ". {$this->rep_notes}"
        );
        session()->flash('message', 'Repair completed.');
        $this->repCloseModal();
        $this->repCloseDetail();
    }

    // ── HELPERS ────────────────────────────────────────────────

    private function recordMovement(int $assetId, $toDept, $toLoc, ?string $remarks): void
    {
        $a = Asset::findOrFail($assetId);
        AssetMovement::create([
            'asset_id'          => $assetId,
            'from_department_id' => $a->department_id,
            'to_department_id'  => $toDept,
            'from_location_id'  => $a->location_id,
            'to_location_id'    => $toLoc,
            'moved_by'          => Auth::id(),
            'remarks'           => $remarks,
        ]);
    }

    private function storeImage($file): string
    {
        $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->storeAs('assets', $name, 'public');
        return $name;
    }

    public function render()
    {
        return view('pages.Assetsmanagement.management', [
            'inventoryAssets' => $this->inventoryAssets,
            'assignAssets'    => $this->assignAssets,
            'departments'     => $this->departments,
            'locations'       => $this->locations,
            'deptAssets'      => $this->deptAssets,
            'repairAssets'    => $this->repairAssets,
        ]);
    }
}
