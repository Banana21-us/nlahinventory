<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

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
                'leave'  => $leave->load('user'),
                'action' => $leave->dept_head_status, // show whatever it already is
            ]);
        }

        $leave->load('user');

        $leave->update([
            'dept_head_status'      => $action,
            'dept_head_id'          => $request->query('dhead'),
            'dept_head_approved_at' => now(),
        ]);

        return view('emails.leave-response-done', compact('leave', 'action'));
    }
}
