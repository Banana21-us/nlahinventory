<?php

namespace App\Http\Controllers;

use App\Mail\LeaveDHeadDecisionMail;
use App\Mail\LeaveHRNotificationMail;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeaveResponseController extends Controller
{
    public function respond(Request $request, Leave $leave, string $action)
    {
        // Validate the signed URL — abort if tampered or expired
        abort_unless($request->hasValidSignature(), 403, 'This link is invalid or has already been used.');

        // Only allow valid actions
        abort_unless(in_array($action, ['approved', 'rejected']), 422, 'Invalid action.');

        // If already processed, show a neutral message
        if ($leave->dept_head_status !== 'pending') {
            return view('emails.leave-response-done', [
                'leave' => $leave->load('user'),
                'action' => $leave->dept_head_status, // show whatever it already is
            ]);
        }

        $leave->load('user.employmentDetail.department');

        $leave->update([
            'dept_head_status' => $action,
            'dept_head_id' => $request->query('dhead'),
            'dept_head_approved_at' => now(),
        ]);

        // Notify the employee of the dept head's decision
        $staffEmail = $leave->user?->email;
        if ($staffEmail) {
            try {
                Mail::to($staffEmail)->send(new LeaveDHeadDecisionMail($leave));
            } catch (\Exception $e) {
                Log::error('LeaveResponseController: LeaveDHeadDecisionMail failed', [
                    'leave_id' => $leave->id,
                    'email' => $staffEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // If approved, forward to HR for final review
        if ($action === 'approved') {
            $hrUsers = User::whereHas('employmentDetail', fn ($q) => $q->where('position', 'HR Manager'))
                ->whereNotNull('email')
                ->get();

            foreach ($hrUsers as $hr) {
                try {
                    Mail::to($hr->email)->send(new LeaveHRNotificationMail($leave));
                } catch (\Exception $e) {
                    Log::error('LeaveResponseController: LeaveHRNotificationMail failed', [
                        'leave_id' => $leave->id,
                        'email' => $hr->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return view('emails.leave-response-done', compact('leave', 'action'));
    }
}
