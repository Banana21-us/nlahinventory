<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave Request — HR Action Required</title>
<style>
  body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .header { background: #015581; color: #fff; padding: 28px 32px; }
  .header p.label { margin: 0 0 4px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: .7; }
  .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
  .body { padding: 28px 32px; }
  .card { background: #e6f0f7; border: 1px solid #bdd5e7; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
  .card-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
  .card-row .label { color: #5c7a94; font-weight: 600; }
  .card-row .value { color: #1a2e40; font-weight: 700; text-align: right; }
  .reason-box { background: #fef8e7; border: 1px solid #fde68a; border-radius: 8px; padding: 14px 18px; font-size: 14px; color: #5c4a1e; font-style: italic; margin-bottom: 24px; }
  .btn-block { text-align: center; margin-bottom: 12px; }
  .btn { display: inline-block; padding: 12px 32px; border-radius: 8px; font-weight: 700; font-size: 14px; text-decoration: none; }
  .btn-primary { background: #015581; color: #fff; }
  .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <p class="label">NLAH · HR Administration</p>
    <h1>Leave Request — Action Required</h1>
  </div>
  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">A leave request has been forwarded to HR and requires your review.</p>

    <div class="card">
      <div class="card-row">
        <span class="label">Employee:</span>
        <span class="value">{{ $leave->user->name }}</span>
      </div>
      <div class="card-row">
        <span class="label">Username: </span>
        <span class="value">{{ $leave->user->username }}</span>
      </div>
      <div class="card-row">
        <span class="label">Department: </span>
        <span class="value">{{ $leave->user->employmentDetail?->department?->name ?? 'N/A' }}</span>
      </div>
      <div class="card-row">
        <span class="label">Leave Type: </span>
        <span class="value">{{ $leave->leave_type }}</span>
      </div>
      <div class="card-row">
        <span class="label">Period: </span>
        <span class="value">{{ $leave->start_date->format('M d, Y') }} – {{ $leave->end_date->format('M d, Y') }}</span>
      </div>
      <div class="card-row">
        <span class="label">Total Days: </span>
        <span class="value">{{ $leave->total_days }} day(s) · {{ $leave->day_part }}</span>
      </div>
      <div class="card-row">
        <span class="label">Dept Head Status: </span>
        <span class="value" style="color:{{ $leave->dept_head_status === 'approved' ? '#166534' : '#92400e' }}">{{ ucfirst($leave->dept_head_status) }}</span>
      </div>
    </div>

    <div class="reason-box">"{{ $leave->reason }}"</div>

    <div class="btn-block">
      <a href="{{ $portalUrl }}" class="btn btn-primary">Open HR Leave Portal</a>
    </div>
    <p style="font-size:12px;color:#9ca3af;text-align:center;margin:0;">Log in to the portal to approve or reject this request.</p>
  </div>
  <div class="footer">
    Northern Luzon Adventist Hospital &nbsp;·&nbsp; This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
