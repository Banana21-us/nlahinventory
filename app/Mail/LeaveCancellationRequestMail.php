<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveCancellationRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Leave $leave) {}

    public function envelope(): Envelope
    {
        $name = $this->leave->user?->name ?? 'Staff';

        return new Envelope(
            subject: "[Cancellation Request] {$name} · {$this->leave->leave_type}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-cancellation-request',
            with: [
                'leave'     => $this->leave,
                'portalUrl' => route('HR.hr-leave-management'),
            ],
        );
    }
}
