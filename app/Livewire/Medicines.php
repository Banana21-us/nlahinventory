<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Medicine;

class Medicines extends Component
{
    public $quantity = 0; // Add this
    public $generic_name = "";
    public $brand_name = "";
    public $dosage_form = "";
    public $strength = "";
    public $unit = "";
    public $reorder_level = 0;
    public $description = "";
    public $status = 'active';

public function save()
{
    $this->validate([
        'generic_name' => 'required|string|max:150',
    ]);

    try {
        Medicine::create([
            'quantity' => $this->quantity,
            'generic_name' => $this->generic_name,
            'brand_name' => $this->brand_name,
            'dosage_form' => $this->dosage_form,
            'strength' => $this->strength,
            'unit' => $this->unit,
            'reorder_level' => $this->reorder_level ?? 0,
            'description' => $this->description,
            'status' => $this->status ?? 'active',
        ]);

        session()->flash('message', 'Medicine added successfully!');
        // Reset form fields
        $this->reset();
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}
public $confirmingDeletion = false;
public $medicineIdToDelete;

public function confirmDelete($id)
{
    $this->medicineIdToDelete = $id;
    $this->confirmingDeletion = true;
}

public function delete()
{
    \App\Models\Medicine::find($this->medicineIdToDelete)->delete();
    $this->confirmingDeletion = false;
    $this->reset('medicineIdToDelete');
    session()->flash('message', 'Medicine removed from inventory.');
}
public function render()
{
    return view('pages.medmission.medicines', [
        'medicines' => Medicine::latest()->get()
    ])->layout('layouts.app');
}

public $isEditing = false;
public $editId;

public function edit($id)
{
    $medicine = \App\Models\Medicine::findOrFail($id);
    
    $this->editId = $id;
    $this->quantity = $medicine->quantity;
    $this->generic_name = $medicine->generic_name;
    $this->brand_name = $medicine->brand_name;
    $this->dosage_form = $medicine->dosage_form;
    $this->strength = $medicine->strength;
    $this->unit = $medicine->unit;
    $this->reorder_level = $medicine->reorder_level;
    $this->description = $medicine->description;
    $this->status = $medicine->status;

    $this->isEditing = true;
}

public function update()
{
    $this->validate([
        'generic_name' => 'required|string|max:150',

    ]);

    $medicine = \App\Models\Medicine::find($this->editId);
    $medicine->update([
        'quantity'      => $this->quantity,
        'generic_name'  => $this->generic_name,
        'brand_name'    => $this->brand_name,
        'dosage_form'   => $this->dosage_form,
        'strength'      => $this->strength,
        'unit'          => $this->unit,
        'reorder_level' => $this->reorder_level,
        'description'   => $this->description,
        'status'        => $this->status,
    ]);

    $this->isEditing = false;
    $this->reset();
    
    session()->flash('message', 'Medicine details updated successfully!');
}
}