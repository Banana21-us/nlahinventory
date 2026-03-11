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
        // Simple validation
        if (empty($request->name) || empty($request->comment) || empty($request->rating)) {
            return response()->json(['success' => false, 'message' => 'All fields are required']);
        }

        try {
            // Insert feedback
            DB::table('feedbacks')->insert([
                'name' => $request->name,
                'comment' => $request->comment,
                'rating' => $request->rating,
                'feedback_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Database error']);
        }
    }
}