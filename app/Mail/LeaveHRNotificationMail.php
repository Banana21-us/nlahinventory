<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveHRNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Leave $leave) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Leave — HR Action Required] '.$this->leave->user->name.' · '.$this->leave->leave_type,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-hr-notification',
            with: [
                'leave' => $this->leave,
                'portalUrl' => route('HR.hr-leave-management'),
            ],
        );
    }
}
