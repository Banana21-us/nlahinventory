<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Response Recorded</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 40px 16px; }
        .card { max-width: 480px; margin: 0 auto; background: #fff; border-radius: 10px; padding: 40px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .icon { font-size: 48px; margin-bottom: 16px; }
        h2 { margin: 0 0 8px; font-size: 22px; }
        p { color: #6b7280; font-size: 15px; margin: 0 0 24px; }
        .badge { display: inline-block; padding: 6px 18px; border-radius: 999px; font-weight: 700; font-size: 14px; }
        .approved { background: #dcfce7; color: #15803d; }
        .rejected  { background: #fee2e2; color: #b91c1c; }
        .meta { background: #f9fafb; border-radius: 8px; padding: 14px 20px; text-align: left; font-size: 14px; color: #374151; margin-top: 20px; }
        .meta div { margin-bottom: 6px; }
        .meta div:last-child { margin-bottom: 0; }
        .meta span { color: #9ca3af; }
        a { color: #015581; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
<div class="card">
    @if($action === 'approved')
        <div class="icon">✅</div>
        <h2 style="color:#15803d;">Leave Approved</h2>
        <p>You have approved this leave request.</p>
        <span class="badge approved">Approved</span>
    @else
        <div class="icon">❌</div>
        <h2 style="color:#b91c1c;">Leave Rejected</h2>
        <p>You have rejected this leave request.</p>
        <span class="badge rejected">Rejected</span>
    @endif

    <div class="meta">
        <div><span>Employee: </span><strong>{{ $leave->user->name }}</strong></div>
        <div><span>Leave Type: </span>{{ $leave->leave_type }}</div>
        <div><span>Period: </span>{{ $leave->start_date->format('M d') }} – {{ $leave->end_date->format('M d, Y') }}</div>
        <div><span>Duration: </span>{{ $leave->total_days }} day(s)</div>
    </div>

    <p style="margin-top: 24px; font-size: 13px; color: #9ca3af;">
        This action has been recorded. The HR team will be notified and will complete the final approval step.<br><br>
        <a href="{{ route('login') }}">Log in to the HR system →</a>
    </p>
</div>
</body>
</html>
