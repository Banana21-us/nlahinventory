<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsEventController extends Controller
{
    /**
     * Display a listing of news and events with pagination.
     */
    public function index(Request $request)
    {
        // Fetch records from news_events table with pagination (9 per page)
        $newsEvents = DB::table('news_events')
                        ->orderBy('date', 'desc')
                        ->paginate(9)
                        ->withQueryString(); // Maintains query parameters in pagination links
        
        return view('nlah.news', compact('newsEvents'));
    }

    /**
     * Display the specified news/event.
     */
    public function show($id)
    {
        // Fetch a single record by ID
        $newsItem = DB::table('news_events')->where('id', $id)->first();
        
        // If not found, return 404
        if (!$newsItem) {
            abort(404, 'News/Event not found');
        }
        
        return view('nlah.news-detail', compact('newsItem'));
    }

    /**
     * Filter news/events by category with pagination.
     */
    public function byCategory(Request $request, $category)
    {
        $newsEvents = DB::table('news_events')
                        ->where('category', $category)
                        ->orderBy('date', 'desc')
                        ->paginate(9)
                        ->withQueryString();
        
        return view('nlah.news', compact('newsEvents', 'category'));
    }

    /**
     * Filter news/events by type (News or Event) with pagination.
     */
    public function byType(Request $request, $type)
    {
        // Capitalize first letter to match database (News/Event)
        $type = ucfirst(strtolower($type));
        
        $newsEvents = DB::table('news_events')
                        ->where('type', $type)
                        ->orderBy('date', 'desc')
                        ->paginate(9)
                        ->withQueryString();
        
        return view('nlah.news', compact('newsEvents', 'type'));
    }

    /**
     * Get latest news/events for homepage.
     */
    public function latest($limit = 3)
    {
        return DB::table('news_events')
                 ->orderBy('date', 'desc')
                 ->limit($limit)
                 ->get();
    }
}