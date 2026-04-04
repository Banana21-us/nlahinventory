<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveCancellationResultMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Leave  $leave       The leave record (hr_status will be 'cancelled' or 'approved' = denied)
     * @param string $recipientRole  'staff' or 'dhead' — controls wording in the template
     */
    public function __construct(
        public Leave $leave,
        public string $recipientRole = 'staff',
    ) {}

    public function envelope(): Envelope
    {
        $name   = $this->leave->user?->name ?? 'Staff';
        $result = $this->leave->hr_status === 'cancelled' ? 'Approved' : 'Denied';

        return new Envelope(
            subject: "[Cancellation {$result}] {$name} · {$this->leave->leave_type}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-cancellation-result',
            with: [
                'leave'         => $this->leave,
                'recipientRole' => $this->recipientRole,
                'portalUrl'     => $this->recipientRole === 'dhead'
                    ? route('users.dhead-leave')
                    : route('users.leaveform'),
            ],
        );
    }
}
