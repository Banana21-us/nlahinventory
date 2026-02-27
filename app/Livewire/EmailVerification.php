<?php

namespace App\Livewire;

use Livewire\Component;

class EmailVerification extends Component
{
    public bool $sent = false;

    public function resend()
    {
        auth()->user()->sendEmailVerificationNotification();
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.email-verification');
    }
}