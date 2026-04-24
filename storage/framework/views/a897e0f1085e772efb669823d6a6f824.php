
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Application Status Update</title>
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
            background-color: <?php echo e($status === 'approved' ? '#10B981' : '#EF4444'); ?>;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .content {
            padding: 20px;
        }
        .status-box {
            background-color: <?php echo e($status === 'approved' ? '#D1FAE5' : '#FEE2E2'); ?>;
            border-left: 4px solid <?php echo e($status === 'approved' ? '#10B981' : '#EF4444'); ?>;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .status-text {
            font-size: 24px;
            font-weight: bold;
            color: <?php echo e($status === 'approved' ? '#065F46' : '#991B1B'); ?>;
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
        .remarks-box {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
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
            <h1>Leave Application <?php echo e($status === 'approved' ? 'Approved' : 'Rejected'); ?></h1>
        </div>
        
        <div class="content">
            <p>Dear <?php echo e($leave->user->username); ?>,</p>
            
            <div class="status-box">
                <div class="status-text">
                    <?php echo e($status === 'approved' ? '✓ APPROVED' : '✗ REJECTED'); ?>

                </div>
                <p style="margin-top: 10px; margin-bottom: 0;">
                    Your leave application has been <strong><?php echo e($status === 'approved' ? 'approved' : 'rejected'); ?></strong>.
                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($actionBy): ?>
                    <p style="margin-top: 5px; margin-bottom: 0; font-size: 12px;">
                        Processed by: <?php echo e($actionBy); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            <div class="leave-details">
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
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($remarks): ?>
                <div class="remarks-box">
                    <strong>📝 Remarks:</strong><br>
                    <?php echo e($remarks); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status === 'approved'): ?>
                    ✅ Your leave has been officially approved and recorded. 
                    Please coordinate with your team for any work transitions during your absence.
                <?php else: ?>
                    ❌ If you have questions about this decision, please contact the HR department for clarification.
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
            
            <hr style="margin: 20px 0;">
            <p style="font-size: 12px; color: #6B7280;">
                <strong>Leave Reference #:</strong> <?php echo e($leave->id); ?><br>
                <strong>Submitted on:</strong> <?php echo e(\Carbon\Carbon::parse($leave->created_at)->format('F d, Y h:i A')); ?>

            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from NLAH HR System.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/emails/leave-status.blade.php ENDPATH**/ ?>