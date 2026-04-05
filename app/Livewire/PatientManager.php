<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class PatientManager extends Component
{
    use WithPagination;

    public $full_name;

    public $age;

    public $gender;

    public $contact_number;

    public $medical_history;

    public $search = '';

    public $showForm = false;

    public function save()
    {
        $validated = $this->validate([
            'full_name' => 'required|min:3',
            'gender' => 'required',
            'age' => 'nullable|numeric',
        ]);

        // This will stop the code and show you the data on screen
        // If you don't see this black screen, the code never reached this line!
        // dd('Validation passed!', $this->full_name);

        $patientNumber = 'PN-'.date('Y').'-'.str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT);

        Patient::create([
            'patient_number' => $patientNumber,
            'full_name' => $this->full_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'medical_history' => $this->medical_history,
        ]);

        $this->reset();
        $this->showForm = false;
        session()->flash('message', 'Patient saved!');
    }

    public function render()
    {
        return view('pages.medmission.patient-manager', [
            'patients' => Patient::where('full_name', 'like', '%'.$this->search.'%')
                ->orWhere('patient_number', 'like', '%'.$this->search.'%')
                ->latest()
                ->paginate(10),
        ])->layout('layouts.app');
    }
}
