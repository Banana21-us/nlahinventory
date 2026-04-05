<?php

namespace App\Livewire;

use Livewire\Component;

class Navigation extends Component
{
    public function render()
    {
        // return view('livewire.navigation');
        return view('livewire.navigation', [
            'role' => auth()->user()?->role ?? null,
        ]);
    }
}
