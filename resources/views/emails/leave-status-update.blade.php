<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leave Request Update</title>
<style>
  body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .header-approved { background: #166534; color: #fff; padding: 28px 32px; }
  .header-rejected  { background: #991b1b; color: #fff; padding: 28px 32px; }
  .header-approved p.label, .header-rejected p.label { margin: 0 0 4px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; opacity: .7; }
  .header-approved h1, .header-rejected h1 { margin: 0; font-size: 20px; font-weight: 700; }
  .body { padding: 28px 32px; }
  .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
  .card-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
  .card-row .lbl { color: #6b7280; font-weight: 600; }
  .card-row .val { color: #111827; font-weight: 700; text-align: right; }

  /* approval chain steps */
  .steps { display: flex; gap: 0; margin-bottom: 24px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; }
  .step { flex: 1; padding: 14px 12px; text-align: center; }
  .step-done-green { background: #dcfce7; }
  .step-done-red   { background: #fee2e2; }
  .step-icon { font-size: 18px; display: block; margin-bottom: 4px; }
  .step-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
  .step-title-green { color: #166534; }
  .step-title-red   { color: #991b1b; }
  .step-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
  .step-divider { width: 1px; background: #e5e7eb; }

  .remarks-box { border-radius: 8px; padding: 14px 18px; font-size: 14px; margin-bottom: 24px; }
  .remarks-approved { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
  .remarks-rejected  { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
  .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">

  @if($leave->hr_status === 'approved')
  <div class="header-approved">
    <p class="label">NLAH · HR Administration</p>
    <h1>&#10003; Your Leave Has Been Approved</h1>
  </div>
  @else
  <div class="header-rejected">
    <p class="label">NLAH · HR Administration</p>
    <h1>Your Leave Has Been Rejected</h1>
  </div>
  @endif

  <div class="body">
    <p style="font-size:14px;color:#374151;margin-top:0;">
      Dear <strong>{{ $leave->user->name }}</strong>,<br><br>
      @if($leave->hr_status === 'approved')
        Your <strong>{{ $leave->leave_type }}</strong> request has been reviewed and
        <strong>approved</strong> by both your Department Head and HR.
      @else
        Your <strong>{{ $leave->leave_type }}</strong> request has been <strong>rejected</strong> by HR.
      @endif
    </p>

    {{-- Approval chain --}}
    <div class="steps" style="margin-bottom:24px;">
      {{-- Step 1: Dept Head --}}
      <div class="step {{ $leave->dept_head_status === 'approved' ? 'step-done-green' : 'step-done-red' }}">
        <span class="step-icon">{{ $leave->dept_head_status === 'approved' ? '✅' : '❌' }}</span>
        <div class="step-title {{ $leave->dept_head_status === 'approved' ? 'step-title-green' : 'step-title-red' }}">Department Head</div>
        <div class="step-sub">{{ ucfirst($leave->dept_head_status) }}</div>
      </div>
      <div class="step-divider"></div>
      {{-- Step 2: HR --}}
      <div class="step {{ $leave->hr_status === 'approved' ? 'step-done-green' : 'step-done-red' }}">
        <span class="step-icon">{{ $leave->hr_status === 'approved' ? '✅' : '❌' }}</span>
        <div class="step-title {{ $leave->hr_status === 'approved' ? 'step-title-green' : 'step-title-red' }}">HR Office</div>
        <div class="step-sub">{{ ucfirst($leave->hr_status) }}</div>
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
      @if($leave->hr_approved_at)
      <div class="card-row">
        <span class="lbl">Decision Date:</span>
        <span class="val">{{ $leave->hr_approved_at->format('M d, Y h:i A') }}</span>
      </div>
      @endif
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

    <p style="font-size:13px;color:#6b7280;margin-bottom:0;">
      If you have any questions, please contact the HR department directly.
    </p>
  </div>

  <div class="footer">
    Northern Luzon Adventist Hospital &nbsp;·&nbsp; This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
