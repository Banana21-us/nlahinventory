<?php

namespace App\Livewire;

use Livewire\Component;

class MaintenanceCheck extends Component
{
    public function render()
    {
        return view('pages.maintenance.checklist')->layout('layouts.app');
    }
}
