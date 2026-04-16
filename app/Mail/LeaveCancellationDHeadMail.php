<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveCancellationDHeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Leave $leave) {}

    public function envelope(): Envelope
    {
        $name = $this->leave->user?->name ?? 'Staff';

        return new Envelope(
            subject: "[Cancellation Review] {$name} · {$this->leave->leave_type}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-cancellation-dhead',
            with: [
                'leave' => $this->leave,
                'portalUrl' => route('users.dhead-leaveform'),
            ],
        );
    }
}
