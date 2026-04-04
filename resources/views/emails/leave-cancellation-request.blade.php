<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave Cancellation Request — HR Action Required</title>
<style>
  body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .header { background: #b45309; color: #fff; padding: 28px 32px; }
  .header p.label { margin: 0 0 4px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: .75; }
  .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
  .body { padding: 28px 32px; }
  .alert-box { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px; color: #78350f; }
  .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
  .card-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
  .card-row .lbl { color: #6b7280; font-weight: 600; }
  .card-row .val { color: #111827; font-weight: 700; text-align: right; }
  .badge-approved { display:inline-block; background:#dcfce7; color:#166534; border:1px solid #86efac; padding:3px 10px; border-radius:99px; font-size:13px; font-weight:700; }
  .reason-box { background: #fef8e7; border: 1px solid #fde68a; border-radius: 8px; padding: 14px 18px; font-size: 14px; color: #5c4a1e; font-style: italic; margin-bottom: 24px; }
  .btn-block { text-align: center; margin-bottom: 12px; }
  .btn { display: inline-block; padding: 12px 32px; border-radius: 8px; font-weight: 700; font-size: 14px; text-decoration: none; background: #b45309; color: #fff; }
  .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <p class="label">NLAH · HR Administration</p>
    <h1>Leave Cancellation Request</h1>
  </div>
  <div class="body">
    <div class="alert-box">
      <strong>Action Required:</strong> An employee has requested to cancel a previously approved leave. Please review and confirm or deny this request in the HR portal.
    </div>

    <div class="card">
      <div class="card-row">
        <span class="lbl">Employee:</span>
        <span class="val">{{ $leave->user?->name ?? '—' }}</span>
      </div>
      <div class="card-row">
        <span class="lbl">Department:</span>
        <span class="val">{{ $leave->user?->employmentDetail?->department?->name ?? 'N/A' }}</span>
      </div>
      <div class="card-row">
        <span class="lbl">Leave Type:</span>
        <span class="val">{{ $leave->leave_type }}</span>
      </div>
      <div class="card-row">
        <span class="lbl">Period:</span>
        <span class="val">{{ $leave->start_date->format('M d, Y') }} – {{ $leave->end_date->format('M d, Y') }}</span>
      </div>
      <div class="card-row">
        <span class="lbl">Total Days:</span>
        <span class="val">{{ $leave->total_days }} day(s) · {{ $leave->day_part }}</span>
      </div>
      <div class="card-row">
        <span class="lbl">Original HR Status:</span>
        <span class="val"><span class="badge-approved">Approved</span></span>
      </div>
    </div>

    @if($leave->reason)
    <div class="reason-box">"{{ $leave->reason }}"</div>
    @endif

    <div class="btn-block">
      <a href="{{ $portalUrl }}" class="btn">Review in HR Portal</a>
    </div>
    <p style="font-size:12px;color:#9ca3af;text-align:center;margin:0;">Log in to approve or deny the cancellation request.</p>
  </div>
  <div class="footer">
    Northern Luzon Adventist Hospital &nbsp;·&nbsp; This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
