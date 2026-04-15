<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\ItemType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AssetItemEntry extends Component
{
    public bool $showForm = false;

    public bool $showCategoryForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $name = '';

    public string $category_id = '';

    public string $desc = '';

    public string $category_name = '';

    public string $category_desc = '';

    public string $search = '';

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('item_types', 'name')->ignore($this->editingId),
            ],
            'category_id' => 'required|exists:categories,id',
            'desc' => 'nullable|string',
        ];
    }

    protected function categoryRules(): array
    {
        return [
            'category_name' => 'required|string|max:255|unique:categories,name',
            'category_desc' => 'nullable|string',
        ];
    }

    protected $messages = [
        'name.required' => 'Item type name is required.',
        'name.unique' => 'This item type already exists.',
        'category_id.required' => 'Please select a category.',
        'category_id.exists' => 'Selected category does not exist.',
        'category_name.required' => 'Category name is required.',
        'category_name.unique' => 'This category already exists.',
    ];

    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    #[Computed]
    public function itemTypes()
    {
        return ItemType::with(['category'])
            ->withCount('assets')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('desc', 'like', '%'.$this->search.'%')
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', '%'.$this->search.'%'));
                });
            })
            ->latest()
            ->get();
    }

    public function save(): void
    {
        $validated = $this->validate();

        ItemType::create([
            'name' => trim($validated['name']),
            'category_id' => (int) $validated['category_id'],
            'desc' => $this->desc !== '' ? trim($this->desc) : null,
        ]);

        session()->flash('message', "Item type '{$this->name}' has been added.");
        $this->cancelForm();
    }

    public function saveCategory(): void
    {
        $validated = $this->validate($this->categoryRules());

        $category = Category::create([
            'name' => trim($validated['category_name']),
            'desc' => $this->category_desc !== '' ? trim($this->category_desc) : null,
        ]);

        $this->category_id = (string) $category->id;
        $this->reset(['category_name', 'category_desc']);
        $this->resetValidation(['category_name', 'category_desc']);
        $this->showCategoryForm = false;

        session()->flash('message', "Category '{$category->name}' has been added.");
    }

    public function edit(int $id): void
    {
        $itemType = ItemType::findOrFail($id);

        $this->editingId = $itemType->id;
        $this->name = $itemType->name;
        $this->category_id = (string) $itemType->category_id;
        $this->desc = $itemType->desc ?? '';
        $this->showForm = false;
        $this->isEditing = true;
    }

    public function update(): void
    {
        $validated = $this->validate();

        $itemType = ItemType::findOrFail($this->editingId);
        $itemType->update([
            'name' => trim($validated['name']),
            'category_id' => (int) $validated['category_id'],
            'desc' => $this->desc !== '' ? trim($this->desc) : null,
        ]);

        session()->flash('message', "Item type '{$itemType->name}' has been updated.");
        $this->cancelEdit();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $itemType = ItemType::findOrFail($this->deletingId);

        if ($itemType->assets()->exists()) {
            session()->flash('message', "Item type '{$itemType->name}' cannot be removed while assets are assigned to it.");
            $this->cancelDelete();

            return;
        }

        $name = $itemType->name;

        $itemType->delete();

        session()->flash('message', "Item type '{$name}' has been removed.");
        $this->cancelDelete();
    }

    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
        $this->isEditing = false;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeletion = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'category_id', 'desc', 'editingId']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('pages.Assetsmanagement.item-entry')->layout('layouts.app');
    }
}
