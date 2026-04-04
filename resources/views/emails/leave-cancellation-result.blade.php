<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave Cancellation {{ $leave->hr_status === 'cancelled' ? 'Approved' : 'Denied' }}</title>
<style>
  body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .header-approved { background: #6b7280; color: #fff; padding: 28px 32px; }
  .header-denied   { background: #015581; color: #fff; padding: 28px 32px; }
  .header p.label  { margin: 0 0 4px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: .75; }
  .header-approved h1, .header-denied h1 { margin: 0; font-size: 20px; font-weight: 700; }
  .body { padding: 28px 32px; }
  .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
  .card-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
  .card-row .lbl { color: #6b7280; font-weight: 600; }
  .card-row .val { color: #111827; font-weight: 700; text-align: right; }
  .badge-cancelled { display:inline-block; background:#f3f4f6; color:#374151; border:1px solid #d1d5db; padding:3px 10px; border-radius:99px; font-size:13px; font-weight:700; }
  .badge-active    { display:inline-block; background:#dcfce7; color:#166534; border:1px solid #86efac; padding:3px 10px; border-radius:99px; font-size:13px; font-weight:700; }
  .result-box-approved { background:#f3f4f6; border:1px solid #d1d5db; border-radius:8px; padding:14px 18px; font-size:14px; color:#374151; margin-bottom:24px; }
  .result-box-denied   { background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:14px 18px; font-size:14px; color:#991b1b; margin-bottom:24px; }
  .btn-block { text-align: center; margin-bottom: 12px; }
  .btn-grey { display:inline-block; padding:12px 32px; border-radius:8px; font-weight:700; font-size:14px; text-decoration:none; background:#6b7280; color:#fff; }
  .btn-blue { display:inline-block; padding:12px 32px; border-radius:8px; font-weight:700; font-size:14px; text-decoration:none; background:#015581; color:#fff; }
  .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">

  @if($leave->hr_status === 'cancelled')
  <div class="header-approved">
    <p class="label">NLAH · HR Administration</p>
    <h1>Leave Cancellation Approved</h1>
  </div>
  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">
      @if($recipientRole === 'dhead')
        Dear Department Head,<br><br>
        HR has approved the cancellation request for a leave from your team. The leave is now voided and credits have been restored.
      @else
        Dear {{ $leave->user?->name ?? 'Team Member' }},<br><br>
        Your request to cancel your leave has been <strong>approved</strong> by HR. Your leave credits have been restored.
      @endif
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
        <span class="val">{{ $leave->total_days }} day(s)</span>
      </div>
      <div class="card-row">
        <span class="lbl">New Status:</span>
        <span class="val"><span class="badge-cancelled">Cancelled</span></span>
      </div>
    </div>

    @if($leave->remarks)
    <div class="result-box-approved">
      <strong>HR Note:</strong><br>{{ $leave->remarks }}
    </div>
    @endif

    <div class="btn-block">
      <a href="{{ $portalUrl }}" class="btn-grey">View Leave Portal</a>
    </div>
    <p style="font-size:12px;color:#9ca3af;text-align:center;margin:0;">This is a courtesy notification. No further action required.</p>
  </div>

  @else
  {{-- Cancellation was denied — leave remains active --}}
  <div class="header-denied">
    <p class="label">NLAH · HR Administration</p>
    <h1>Cancellation Request Denied</h1>
  </div>
  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">
      @if($recipientRole === 'dhead')
        Dear Department Head,<br><br>
        HR has reviewed and <strong>denied</strong> the cancellation request for a leave from your team. The leave remains active as originally approved.
      @else
        Dear {{ $leave->user?->name ?? 'Team Member' }},<br><br>
        Your request to cancel your leave has been <strong>denied</strong> by HR. Your leave remains active as originally approved.
      @endif
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
        <span class="val">{{ $leave->total_days }} day(s)</span>
      </div>
      <div class="card-row">
        <span class="lbl">Current Status:</span>
        <span class="val"><span class="badge-active">Still Approved</span></span>
      </div>
    </div>

    @if($leave->remarks)
    <div class="result-box-denied">
      <strong>HR Reason:</strong><br>{{ $leave->remarks }}
    </div>
    @endif

    <div class="btn-block">
      <a href="{{ $portalUrl }}" class="btn-blue">View Leave Portal</a>
    </div>
    <p style="font-size:12px;color:#9ca3af;text-align:center;margin:0;">If you have questions, please contact HR directly.</p>
  </div>
  @endif

  <div class="footer">
    Northern Luzon Adventist Hospital &nbsp;·&nbsp; This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
