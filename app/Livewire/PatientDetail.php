<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patient;

class PatientDetail extends Component
{
    public Patient $patient;

    public function mount($id)
    {
        // Load the patient and their dispensing history with the medicine names
        $this->patient = Patient::with('dispensings.medicine')->findOrFail($id);
    }

    public function render()
    {
        return view('pages.medmission.patient-detail', [
            'history' => $this->patient->dispensings()->latest()->get()
        ])->layout('layouts.app');
    }
}