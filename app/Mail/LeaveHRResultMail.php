<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveHRResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Leave $leave) {}

    public function envelope(): Envelope
    {
        $status = ucfirst($this->leave->hr_status);
        $name = $this->leave->user?->name ?? 'Staff';

        return new Envelope(
            subject: "[Leave {$status}] HR decision for {$name} · {$this->leave->leave_type}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-hr-result',
            with: [
                'leave' => $this->leave,
                'portalUrl' => route('users.dhead-leaveform'),
            ],
        );
    }
}
