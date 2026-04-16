<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveCancellationDHeadDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  Leave   $leave
     * @param  string  $decision  'approved' | 'rejected'
     */
    public function __construct(
        public Leave $leave,
        public string $decision,
    ) {}

    public function envelope(): Envelope
    {
        $tag = $this->decision === 'approved'
            ? 'Forwarded to HR'
            : 'Denied by Dept Head';

        return new Envelope(
            subject: "[Cancellation Update] {$this->leave->leave_type} — {$tag}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-cancellation-dhead-decision',
            with: [
                'leave'    => $this->leave,
                'decision' => $this->decision,
                'portalUrl' => route('users.leaveform'),
            ],
        );
    }
}
