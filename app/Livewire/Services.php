<?php

namespace App\Livewire;

use Livewire\Component;

// Make sure #[Laravel\Livewire\Attributes\Layout] isn't forcing a restricted layout here

class Services extends Component
{
    public function render()
    {
        return view('livewire.services')->layout('layouts.home');
    }
}
