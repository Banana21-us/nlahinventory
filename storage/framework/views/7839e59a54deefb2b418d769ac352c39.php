
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Action Required - Leave Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .content {
            padding: 20px;
        }
        .info-box {
            background-color: #D1FAE5;
            border-left: 4px solid #10B981;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .leave-details {
            background-color: #f9fafb;
            border-left: 4px solid #4F46E5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #4B5563;
            display: inline-block;
            width: 120px;
        }
        .detail-value {
            color: #1F2937;
        }
        .button-group {
            margin: 30px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: opacity 0.3s;
        }
        .btn-approve {
            background-color: #10B981;
            color: white;
        }
        .btn-reject {
            background-color: #EF4444;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 12px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HR Action Required</h1>
        </div>
        
        <div class="content">
            <p>Dear HR Team,</p>
            
            <div class="info-box">
                <strong>✓ Department Head Confirmation:</strong><br>
                Confirmed by: <?php echo e($leave->departmentHead->username ?? 'N/A'); ?><br>
                Confirmed on: <?php echo e(\Carbon\Carbon::parse($leave->dept_head_confirmed_at)->format('F d, Y h:i A')); ?>

            </div>
            
            <p>A leave application has been <strong>confirmed by the Department Head</strong> and now requires your final approval.</p>
            
            <div class="leave-details">
                <div class="detail-row">
                    <span class="detail-label">Employee:</span>
                    <span class="detail-value"><?php echo e($leave->user->username); ?> (<?php echo e($leave->user->employee_number); ?>)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Department:</span>
                    <span class="detail-value"><?php echo e($leave->department); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Leave Type:</span>
                    <span class="detail-value"><?php echo e($leave->formatted_leave_type); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">
                        <?php echo e(\Carbon\Carbon::parse($leave->startdate)->format('F d, Y')); ?> - 
                        <?php echo e(\Carbon\Carbon::parse($leave->enddate)->format('F d, Y')); ?>

                        <br>(<?php echo e($leave->totaldays); ?> day(s))
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Reason:</span>
                    <span class="detail-value"><?php echo e($leave->reason); ?></span>
                </div>
            </div>
            
            <div class="button-group">
                <a href="<?php echo e(route('login')); ?>" class="btn btn-approve">✓ Approve Leave</a>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-reject">✗ Reject Leave</a>
            </div>
            
            <p class="expiry-note">⚠️ This email will expire in 7 days.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from NLAH HR System.</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/emails/leave-for-hr.blade.php ENDPATH**/ ?>