<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class LeaveRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Leave $leave) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Leave Request] '.$this->leave->user->name.' — '.$this->leave->leave_type,
        );
    }

    public function content(): Content
    {
        // Signed URLs — no expiry so the link always works
        $approveUrl = URL::signedRoute('leave.dhead.respond', [
            'leave' => $this->leave->id,
            'action' => 'approved',
            'dhead' => $this->leave->user->department?->dept_head_id,
        ]);

        $rejectUrl = URL::signedRoute('leave.dhead.respond', [
            'leave' => $this->leave->id,
            'action' => 'rejected',
            'dhead' => $this->leave->user->department?->dept_head_id,
        ]);

        return new Content(
            view: 'emails.leave-request',
            with: [
                'leave' => $this->leave,
                'approveUrl' => $approveUrl,
                'rejectUrl' => $rejectUrl,
            ],
        );
    }
}
