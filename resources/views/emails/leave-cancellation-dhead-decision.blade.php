<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave Cancellation — Dept Head Decision</title>
<style>
  body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .header-approved { background: #b45309; color: #fff; padding: 28px 32px; }
  .header-rejected  { background: #015581; color: #fff; padding: 28px 32px; }
  .header-approved p.label, .header-rejected p.label { margin: 0 0 4px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: .75; }
  .header-approved h1, .header-rejected h1 { margin: 0; font-size: 20px; font-weight: 700; }
  .body { padding: 28px 32px; }
  .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
  .card-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
  .card-row .lbl { color: #6b7280; font-weight: 600; }
  .card-row .val { color: #111827; font-weight: 700; text-align: right; }
  .notice-approved { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 14px 18px; font-size: 14px; color: #78350f; margin-bottom: 24px; }
  .notice-rejected  { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 14px 18px; font-size: 14px; color: #991b1b; margin-bottom: 24px; }
  .btn-block { text-align: center; margin-bottom: 12px; }
  .btn-amber { display:inline-block; padding:12px 32px; border-radius:8px; font-weight:700; font-size:14px; text-decoration:none; background:#b45309; color:#fff; }
  .btn-blue  { display:inline-block; padding:12px 32px; border-radius:8px; font-weight:700; font-size:14px; text-decoration:none; background:#015581; color:#fff; }
  .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">

  @if($decision === 'approved')
  <div class="header-approved">
    <p class="label">NLAH · Department Head</p>
    <h1>Cancellation Forwarded to HR</h1>
  </div>
  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">
      Dear <strong>{{ $leave->user?->name ?? 'Team Member' }}</strong>,<br><br>
      Your Department Head has <strong>approved your cancellation request</strong> and forwarded it to HR for final review.
      You will receive another notification once HR makes their decision.
    </p>
    <div class="notice-approved">
      <strong>Next step:</strong> HR will review and confirm the cancellation. Your leave credits will be restored only after HR's final approval.
    </div>

  @else
  <div class="header-rejected">
    <p class="label">NLAH · Department Head</p>
    <h1>Cancellation Request Denied</h1>
  </div>
  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">
      Dear <strong>{{ $leave->user?->name ?? 'Team Member' }}</strong>,<br><br>
      Your Department Head has <strong>denied your cancellation request</strong>. Your leave remains active as originally approved.
    </p>
    <div class="notice-rejected">
      If you believe this is an error, please contact your Department Head or HR directly.
    </div>
  @endif

    <div class="card">
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
    </div>

    <div class="btn-block">
      @if($decision === 'approved')
        <a href="{{ $portalUrl }}" class="btn-amber">View My Leaves</a>
      @else
        <a href="{{ $portalUrl }}" class="btn-blue">View My Leaves</a>
      @endif
    </div>
  </div>

  <div class="footer">
    Northern Luzon Adventist Hospital &nbsp;·&nbsp; This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
