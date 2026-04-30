<?php

namespace App\Mail;

use App\Models\Leave;
use App\Models\LeaveBalance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Leave $leave) {}

    public function envelope(): Envelope
    {
        $status = ucfirst($this->leave->hr_status);

        return new Envelope(
            subject: "[Leave {$status}] Your {$this->leave->leave_type} request has been {$this->leave->hr_status}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-status-update',
            with: [
                'leave'       => $this->leave,
                'balanceRows' => $this->buildBalanceRows(),
            ],
        );
    }

    private function buildBalanceRows(): array
    {
        $rows = [];

        foreach (LeaveBalance::with('leaveType')->where('user_id', $this->leave->user_id)->whereNull('deleted_at')->get() as $balance) {
            $lt = $balance->leaveType;
            if (! $lt) {
                continue;
            }
            $rows[] = [
                'label'     => $lt->label,
                'total'     => (float) $balance->total,
                'consumed'  => (float) $balance->consumed,
                'remaining' => max(0, (float) $balance->total - (float) $balance->consumed),
            ];
        }

        return $rows;
    }
}
