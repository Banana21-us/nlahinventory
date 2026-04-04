<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave Request Update</title>
<style>
  body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .header-approved { background: #065f46; color: #fff; padding: 28px 32px; }
  .header-rejected  { background: #991b1b; color: #fff; padding: 28px 32px; }
  .header-approved p.label, .header-rejected p.label { margin: 0 0 4px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: .7; }
  .header-approved h1, .header-rejected h1 { margin: 0; font-size: 20px; font-weight: 700; }
  .body { padding: 28px 32px; }
  .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
  .card-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
  .card-row .lbl { color: #6b7280; font-weight: 600; }
  .card-row .val { color: #111827; font-weight: 700; text-align: right; }
  .steps { display: flex; gap: 0; margin-bottom: 24px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; }
  .step { flex: 1; padding: 14px 12px; text-align: center; }
  .step-done-green { background: #dcfce7; }
  .step-done-red   { background: #fee2e2; }
  .step-pending    { background: #f3f4f6; }
  .step-icon { font-size: 18px; display: block; margin-bottom: 4px; }
  .step-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
  .step-title-green   { color: #166534; }
  .step-title-red     { color: #991b1b; }
  .step-title-pending { color: #6b7280; }
  .step-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
  .step-divider { width: 1px; background: #e5e7eb; }
  .remarks-box { border-radius: 8px; padding: 14px 18px; font-size: 14px; margin-bottom: 24px; }
  .remarks-rejected { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
  .notice-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 14px 18px; font-size: 14px; color: #1e40af; margin-bottom: 24px; }
  .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">

  @if($leave->dept_head_status === 'approved')
  <div class="header-approved">
    <p class="label">NLAH · Department Head</p>
    <h1>&#10003; Dept Head Approved — Awaiting HR</h1>
  </div>
  @else
  <div class="header-rejected">
    <p class="label">NLAH · Department Head</p>
    <h1>Your Leave Has Been Rejected</h1>
  </div>
  @endif

  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">
      Dear <strong>{{ $leave->user?->name }}</strong>,<br><br>
      @if($leave->dept_head_status === 'approved')
        Your <strong>{{ $leave->leave_type }}</strong> request has been
        <strong>approved by your Department Head</strong> and has been forwarded to HR for final review.
        You will receive another notification once HR has made their decision.
      @else
        Your <strong>{{ $leave->leave_type }}</strong> request has been
        <strong>rejected by your Department Head</strong>.
        Please see the remarks below for details.
      @endif
    </p>

    {{-- Approval chain --}}
    <div class="steps">
      <div class="step {{ $leave->dept_head_status === 'approved' ? 'step-done-green' : 'step-done-red' }}">
        <span class="step-icon">{{ $leave->dept_head_status === 'approved' ? '✅' : '❌' }}</span>
        <div class="step-title {{ $leave->dept_head_status === 'approved' ? 'step-title-green' : 'step-title-red' }}">Department Head</div>
        <div class="step-sub">{{ ucfirst($leave->dept_head_status) }}</div>
      </div>
      <div class="step-divider"></div>
      <div class="step step-pending">
        <span class="step-icon">⏳</span>
        <div class="step-title step-title-pending">HR Office</div>
        <div class="step-sub">{{ $leave->dept_head_status === 'approved' ? 'Pending Review' : 'N/A' }}</div>
      </div>
    </div>

    {{-- Leave details --}}
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

    @if($leave->dept_head_status === 'rejected' && ($leave->dept_head_remarks ?? null))
    <div class="remarks-box remarks-rejected">
      <strong>Reason for Rejection:</strong><br>
      {{ $leave->dept_head_remarks }}
    </div>
    @endif

    @if($leave->dept_head_status === 'approved')
    <div class="notice-box">
      Your request is now in the HR queue. No further action is needed from you at this time.
    </div>
    @endif

    <p style="font-size:13px;color:#6b7280;margin-bottom:0;">
      If you have any questions, please contact HR or your Department Head directly.
    </p>
  </div>

  <div class="footer">
    Northern Luzon Adventist Hospital &nbsp;·&nbsp; This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
