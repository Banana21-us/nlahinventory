<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave HR Decision — FYI</title>
<style>
  body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .header { background: #015581; color: #fff; padding: 28px 32px; }
  .header p.label { margin: 0 0 4px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: .7; }
  .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
  .body { padding: 28px 32px; }
  .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
  .card-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
  .card-row .lbl { color: #6b7280; font-weight: 600; }
  .card-row .val { color: #111827; font-weight: 700; text-align: right; }
  .badge-approved { display:inline-block; background:#dcfce7; color:#166534; border:1px solid #86efac; padding:3px 10px; border-radius:99px; font-size:13px; font-weight:700; }
  .badge-rejected  { display:inline-block; background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; padding:3px 10px; border-radius:99px; font-size:13px; font-weight:700; }
  .remarks-box { border-radius: 8px; padding: 14px 18px; font-size: 14px; margin-bottom: 24px; }
  .remarks-approved { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
  .remarks-rejected  { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
  .btn-block { text-align: center; margin-bottom: 12px; }
  .btn { display: inline-block; padding: 12px 32px; border-radius: 8px; font-weight: 700; font-size: 14px; text-decoration: none; background: #015581; color: #fff; }
  .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <p class="label">NLAH · HR Administration — For Your Information</p>
    <h1>HR Leave Decision</h1>
  </div>
  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">
      Dear Department Head,<br><br>
      HR has made a final decision on a leave request from your team.
    </p>

    <div class="card">
      <div class="card-row">
        <span class="lbl">Employee:</span>
        <span class="val">{{ $leave->user?->name ?? '—' }}</span>
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
        <span class="lbl">HR Decision:</span>
        <span class="val">
          @if($leave->hr_status === 'approved')
            <span class="badge-approved">Approved</span>
          @else
            <span class="badge-rejected">Rejected</span>
          @endif
        </span>
      </div>
    </div>

    @if($leave->hr_status === 'rejected' && ($leave->rejection_reason || $leave->remarks))
    <div class="remarks-box remarks-rejected">
      <strong>Reason for Rejection:</strong><br>
      {{ $leave->rejection_reason ?? $leave->remarks }}
    </div>
    @elseif($leave->hr_status === 'approved' && $leave->remarks)
    <div class="remarks-box remarks-approved">
      <strong>HR Remarks:</strong><br>
      {{ $leave->remarks }}
    </div>
    @endif

    <div class="btn-block">
      <a href="{{ $portalUrl }}" class="btn">View Leave Portal</a>
    </div>
    <p style="font-size:12px;color:#9ca3af;text-align:center;margin:0;">This is a courtesy notification. No action is required from you.</p>
  </div>
  <div class="footer">
    Northern Luzon Adventist Hospital &nbsp;·&nbsp; This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
