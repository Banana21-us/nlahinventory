<?php

namespace App\Livewire\PointofSale;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithFileUploads;

class PosItems extends Component
{
    use WithFileUploads;

    // Form visibility
    public bool $showForm = false;
    public bool $isEditing = false;
    public bool $confirmingDeletion = false;

    // Form fields
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $name = '';
    public string $type = '';
    public $image = null;          // new upload (TemporaryUploadedFile)
    public ?string $existingImage = null; // path of current saved image
    public string $price = '';
    public string $status = 'active';

    protected function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'type'   => 'nullable|string|max:100',
            'image'  => 'nullable|image|max:2048',
            'price'  => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'name.required'  => 'Item name is required.',
        'price.required' => 'Price is required.',
        'price.integer'  => 'Price must be a whole number.',
        'image.image'    => 'File must be an image.',
        'image.max'      => 'Image may not exceed 2 MB.',
    ];

    // ─── Save (Create) ───────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('item_images', 'public');
        }

        Item::create([
            'name'   => trim($this->name),
            'type'   => trim($this->type) ?: null,
            'image'  => $imagePath,
            'price'  => (int) $this->price,
            'status' => $this->status,
        ]);

        session()->flash('message', "'{$this->name}' has been added to the inventory.");
        $this->resetForm();
        $this->showForm = false;
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(int $id): void
    {
        $item = Item::findOrFail($id);

        $this->editingId     = $item->id;
        $this->name          = $item->name;
        $this->type          = $item->type ?? '';
        $this->existingImage = $item->image;
        $this->image         = null;
        $this->price         = (string) $item->price;
        $this->status        = $item->status;
        $this->isEditing     = true;
    }

    public function update(): void
    {
        $this->validate();

        $item = Item::findOrFail($this->editingId);

        $imagePath = $item->image; // keep existing by default
        if ($this->image) {
            // Delete old image if exists
            if ($item->image) {
                \Storage::disk('public')->delete($item->image);
            }
            $imagePath = $this->image->store('item_images', 'public');
        }

        $item->update([
            'name'   => trim($this->name),
            'type'   => trim($this->type) ?: null,
            'image'  => $imagePath,
            'price'  => (int) $this->price,
            'status' => $this->status,
        ]);

        session()->flash('message', "'{$item->name}' has been updated.");
        $this->resetForm();
        $this->isEditing = false;
    }

    // ─── Delete ──────────────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->deletingId        = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $item = Item::findOrFail($this->deletingId);

        if ($item->image) {
            \Storage::disk('public')->delete($item->image);
        }

        $name = $item->name;
        $item->delete();

        session()->flash('message', "'{$name}' has been removed from the inventory.");
        $this->confirmingDeletion = false;
        $this->deletingId         = null;
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function resetForm(): void
    {
        $this->reset(['name', 'type', 'image', 'existingImage', 'price', 'status', 'editingId', 'deletingId']);
        $this->status = 'active';
    }

    public function render()
    {
        return view('pages.POSuser.Items', [
            'items' => Item::latest()->get(),
        ]);
    }
}