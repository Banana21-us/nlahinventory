<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveDHeadDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Leave $leave) {}

    public function envelope(): Envelope
    {
        $tag = $this->leave->dept_head_status === 'approved'
            ? 'Approved by Dept Head — Awaiting HR'
            : 'Rejected by Department Head';

        return new Envelope(
            subject: "[Leave Update] {$this->leave->leave_type} — {$tag}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-dhead-decision',
            with: ['leave' => $this->leave],
        );
    }
}
