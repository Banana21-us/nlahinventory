<?php

namespace App\Mail;

use App\Models\AttendanceSummary;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LateAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Employee $employee,
        public readonly AttendanceSummary $summary,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Notice – Late Arrival on '.$this->summary->attendance_date->format('F d, Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.late-alert',
        );
    }
}
