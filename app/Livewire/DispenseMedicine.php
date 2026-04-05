<?php

namespace App\Livewire;

use App\Models\Dispensing;
use App\Models\Medicine;
use App\Models\Patient;
use App\Services\AuditService;
use Livewire\Component;

class DispenseMedicine extends Component
{
    public $patient_id;

    public $medicine_id;

    public $quantity_dispensed = 1;

    public $unit = 'piece';

    public $medmission_place;

    public $searchPatient = ''; // The search text

    public $searchMedicine = '';

    public function dispense()
    {
        $this->validate([
            'patient_id' => 'required',
            'medicine_id' => 'required',
            'quantity_dispensed' => 'required|integer|min:1',
            'medmission_place' => 'required|string',
        ]);

        $medicine = Medicine::find($this->medicine_id);

        // Check if we have enough pieces
        if ($medicine->quantity < $this->quantity_dispensed) {
            session()->flash('error', "Insufficient stock! Only {$medicine->quantity} pieces remaining.");

            return;
        }

        $oldQuantity = $medicine->quantity;

        \DB::transaction(function () use ($medicine) {
            // 1. Record the dispensing
            Dispensing::create([
                'patient_id' => $this->patient_id,
                'medicine_id' => $this->medicine_id,
                'quantity_dispensed' => $this->quantity_dispensed,
                'dispensed_by' => auth()->user()->name,
                'medmission_place' => $this->medmission_place,
            ]);

            // 2. Subtract from the medicine table
            $medicine->decrement('quantity', $this->quantity_dispensed);
        });

        AuditService::log(
            action: 'medicine_dispensed',
            module: 'medical',
            modelType: 'Medicine',
            modelId: $medicine->id,
            oldValues: ['quantity' => $oldQuantity],
            newValues: ['quantity' => $medicine->fresh()->quantity],
        );

        $this->reset(['medicine_id', 'quantity_dispensed']);
        session()->flash('message', 'Medicine successfully dispensed!');
    }

    public function render()
    {
        return view('pages.medmission.dispense-medicine', [
            // Filter patients based on name or patient number
            'patients' => Patient::where('full_name', 'like', '%'.$this->searchPatient.'%')
                ->orWhere('patient_number', 'like', '%'.$this->searchPatient.'%')
                ->latest()
                ->take(5) // Only show top 5 matches for speed
                ->get(),

            'medicines' => Medicine::where('generic_name', 'like', '%'.$this->searchMedicine.'%')
                ->where('quantity', '>', 0)
                ->get(),

            'recent_activity' => Dispensing::with(['patient', 'medicine'])->latest()->take(5)->get(),
        ])->layout('layouts.app');
    }
}
