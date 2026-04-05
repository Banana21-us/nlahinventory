<?php

namespace App\Livewire\PointofSale;

use App\Models\Inventory;
use App\Models\Item;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PosInventory extends Component
{
    // Modal / form state
    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    // Form fields
    public string $item_id = '';

    public string $quantity = '';

    // Search / filter
    public string $search = '';

    protected function rules(): array
    {
        return [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:0',
        ];
    }

    protected $messages = [
        'item_id.required' => 'Please select an item.',
        'item_id.exists' => 'Selected item does not exist.',
        'quantity.required' => 'Quantity is required.',
        'quantity.integer' => 'Quantity must be a whole number.',
        'quantity.min' => 'Quantity cannot be negative.',
    ];

    // ─── Computed Properties (always available in the view) ──────────────────

    #[Computed]
    public function items()
    {
        return Item::where('status', 'active')->orderBy('name')->get();
    }

    #[Computed]
    public function inventories()
    {
        return Inventory::with('item')
            ->when($this->search, function ($query) {
                $query->whereHas('item', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            })
            ->latest()
            ->get();
    }

    // ─── Save (Create) ───────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate();

        $inventory = Inventory::where('item_id', $this->item_id)->first();

        if ($inventory) {
            $inventory->increment('quantity', (int) $this->quantity);
        } else {
            Inventory::create([
                'item_id' => (int) $this->item_id,
                'quantity' => (int) $this->quantity,
            ]);
        }

        $item = Item::find($this->item_id);
        session()->flash('message', "Inventory updated for '{$item->name}'.");
        $this->resetForm();
        $this->showForm = false;
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(int $id): void
    {
        $inventory = Inventory::findOrFail($id);

        $this->editingId = $inventory->id;
        $this->item_id = (string) $inventory->item_id;
        $this->quantity = (string) $inventory->quantity;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        $inventory = Inventory::findOrFail($this->editingId);
        $inventory->update([
            'item_id' => (int) $this->item_id,
            'quantity' => (int) $this->quantity,
        ]);

        $item = Item::find($this->item_id);
        session()->flash('message', "Inventory for '{$item->name}' has been updated.");
        $this->resetForm();
        $this->isEditing = false;
    }

    // ─── Delete ──────────────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $inventory = Inventory::with('item')->findOrFail($this->deletingId);
        $name = $inventory->item->name ?? 'Item';

        $inventory->delete();

        session()->flash('message', "Inventory record for '{$name}' has been removed.");
        $this->confirmingDeletion = false;
        $this->deletingId = null;
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function resetForm(): void
    {
        $this->reset(['item_id', 'quantity', 'editingId', 'deletingId']);
    }

    public function render()
    {
        return view('pages.POSuser.Inventory');
    }
}
