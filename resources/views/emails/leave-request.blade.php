<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #015581; padding: 28px 32px; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; font-weight: 700; }
        .header p { color: #a8d0e8; margin: 4px 0 0; font-size: 13px; }
        .body { padding: 28px 32px; }
        .greeting { font-size: 15px; color: #374151; margin-bottom: 20px; }
        .card { background: #f0f7fc; border-left: 4px solid #015581; border-radius: 6px; padding: 16px 20px; margin-bottom: 24px; }
        .card-row { display: flex; margin-bottom: 8px; font-size: 14px; }
        .card-row:last-child { margin-bottom: 0; }
        .card-label { color: #6b7280; width: 130px; flex-shrink: 0; font-weight: 600; }
        .card-value { color: #111827; }
        .reason-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 12px 16px; margin-bottom: 24px; font-size: 14px; color: #374151; }
        .reason-box .label { font-weight: 700; color: #92400e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .actions { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; padding: 12px 32px; border-radius: 6px; font-size: 15px; font-weight: 700; text-decoration: none; margin: 0 8px; }
        .btn-approve { background: #15803d; color: #fff; }
        .btn-reject  { background: #b91c1c; color: #fff; }
        .note { font-size: 12px; color: #9ca3af; text-align: center; margin-top: 8px; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>Leave Request — Action Required</h1>
        <p>NLAH Hospital · Department Head Notification</p>
    </div>

    <div class="body">
        <p class="greeting">
            Hello, <strong>{{ $leave->user->department?->deptHead?->name ?? 'Department Head' }}</strong>.
            <br>
            <strong>{{ $leave->user->name }}</strong> has filed a leave application that requires your approval.
        </p>

        <div class="card">
            <div class="card-row">
                <span class="card-label">Employee</span>
                <span class="card-value">{{ $leave->user->name }} ({{ $leave->user->employee_number }})</span>
            </div>
            <div class="card-row">
                <span class="card-label">Department</span>
                <span class="card-value">{{ $leave->user->department?->name ?? 'N/A' }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Leave Type</span>
                <span class="card-value">{{ $leave->leave_type }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Period</span>
                <span class="card-value">
                    {{ $leave->start_date->format('M d, Y') }} – {{ $leave->end_date->format('M d, Y') }}
                </span>
            </div>
            <div class="card-row">
                <span class="card-label">Duration</span>
                <span class="card-value">{{ $leave->total_days }} day(s) · {{ $leave->day_part }}</span>
            </div>
            @if($leave->reliever)
            <div class="card-row">
                <span class="card-label">Reliever</span>
                <span class="card-value">{{ $leave->reliever }}</span>
            </div>
            @endif
            <div class="card-row">
                <span class="card-label">Filed On</span>
                <span class="card-value">{{ $leave->date_requested?->format('M d, Y') ?? now()->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="reason-box">
            <div class="label">Reason / Justification</div>
            {{ $leave->reason }}
        </div>

        <div class="actions">
            <a href="{{ $approveUrl }}" class="btn btn-approve">✓ Approve</a>
            <a href="{{ $rejectUrl }}" class="btn btn-reject">✗ Reject</a>
        </div>
        <p class="note">Clicking a button will immediately update the leave status. You can also log in to the HR system for detailed review.</p>

        <hr class="divider">
        <p style="font-size:13px; color:#6b7280; text-align:center;">
            If the buttons don't work, copy and paste this link into your browser:<br>
            <span style="color:#015581; font-size:11px; word-break:break-all;">{{ $approveUrl }}</span>
        </p>
    </div>

    <div class="footer">
        NLAH Hospital · This is an automated notification — do not reply to this email.
    </div>

</div>
</body>
</html>
