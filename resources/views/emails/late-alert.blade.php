<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Notice</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7fb; margin: 0; padding: 0; }
        .wrapper { max-width: 560px; margin: 32px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        .header { background: #015581; padding: 28px 32px; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; letter-spacing: .3px; }
        .header p  { color: #a8cfe0; margin: 4px 0 0; font-size: 13px; }
        .body { padding: 28px 32px; }
        .greeting { font-size: 15px; color: #333; margin-bottom: 16px; }
        .alert-box { background: #fff8e7; border-left: 4px solid #f0b626; border-radius: 4px; padding: 14px 18px; margin-bottom: 20px; }
        .alert-box p { margin: 0; font-size: 14px; color: #6b4c00; }
        .detail-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .detail-table tr td { padding: 9px 0; border-bottom: 1px solid #f0f0f0; }
        .detail-table tr td:first-child { color: #666; width: 40%; }
        .detail-table tr td:last-child  { font-weight: 600; color: #111; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 12px; font-weight: 700; }
        .badge-late-am   { background: #fef3c7; color: #92400e; }
        .badge-late-pm   { background: #fee2e2; color: #991b1b; }
        .badge-late-both { background: #fee2e2; color: #991b1b; }
        .badge-late      { background: #fef3c7; color: #92400e; }
        .note { font-size: 13px; color: #777; margin-top: 20px; line-height: 1.6; }
        .footer { background: #f8fafc; padding: 16px 32px; border-top: 1px solid #eee; font-size: 12px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>Northern Luzon Adventist Hospital Inc.</h1>
        <p>HR Attendance Notification System</p>
    </div>

    <div class="body">
        <p class="greeting">
            Dear <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>,
        </p>

        <div class="alert-box">
            <p>
                Our records show that you were <strong>late</strong> on
                <strong>{{ $summary->attendance_date->format('l, F d, Y') }}</strong>.
                Please review the details below.
            </p>
        </div>

        <table class="detail-table">
            <tr>
                <td>Date</td>
                <td>{{ $summary->attendance_date->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <span class="badge badge-{{ $summary->status }}">
                        {{ $summary->status_label }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Late By</td>
                <td>{{ $summary->late_minutes }} minute{{ $summary->late_minutes !== 1 ? 's' : '' }}</td>
            </tr>
            @if($summary->shift_type === 'office')
            <tr>
                <td>AM Check-in</td>
                <td>{{ $summary->am_in ? \Carbon\Carbon::parse($summary->am_in)->format('h:i A') : '—' }}</td>
            </tr>
            <tr>
                <td>PM Check-in</td>
                <td>{{ $summary->pm_in ? \Carbon\Carbon::parse($summary->pm_in)->format('h:i A') : '—' }}</td>
            </tr>
            @else
            <tr>
                <td>Clock In</td>
                <td>{{ $summary->clock_in ? $summary->clock_in->format('h:i A') : '—' }}</td>
            </tr>
            @endif
        </table>

        <p class="note">
            If you believe this is an error or you have a valid reason for your tardiness,
            please coordinate with your Department Head or the HR department at your earliest convenience.
        </p>
    </div>

    <div class="footer">
        This is an automated message from the NLAH HR Attendance System. Please do not reply directly to this email.
    </div>
</div>
</body>
</html>
