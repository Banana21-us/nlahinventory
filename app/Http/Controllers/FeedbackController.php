<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function getFeedbacks()
    {
        // Fetch feedbacks from database, ordered by latest first
        $feedbacks = DB::table('feedbacks')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($feedbacks);
    }

    public function submit(Request $request)
    {
        // Validate input
        if (empty($request->comment) || empty($request->rating)) {
            return response()->json([
                'success' => false, 
                'message' => 'Comment and rating are required fields'
            ]);
        }

        try {
            // Insert feedback directly using DB facade (no model)
            DB::table('feedbacks')->insert([
                'name' => $request->name ?? 'Guest',
                'comment' => $request->comment,
                'rating' => $request->rating,
                'feedback_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Feedback submission failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }
}