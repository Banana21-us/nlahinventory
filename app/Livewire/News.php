<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class News extends Component
{
    use WithPagination, WithFileUploads;
    
    // Form properties for adding news
    public $title;
    public $description;
    public $location;
    public $date;
    public $image;
    public $newsCategory;
    public $newsType = 'News';
    
    // UI state
    public $showForm = false;
    
    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'required|min:10',
        'location' => 'required',
        'date' => 'required|date',
        'newsCategory' => 'required',
        'newsType' => 'required|in:News,Event',
        'image' => 'required|image|max:20048', // 2MB max
    ];
    
    public function save()
    {
        $this->validate();
        
        $imageName = time() . '_' . $this->image->getClientOriginalName();
        $this->image->storeAs('news', $imageName, 'public');
        
        // Insert into database
        DB::table('news_events')->insert([
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'category' => $this->newsCategory,
            'type' => $this->newsType,
            'image' => $imageName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Reset form and close it
        $this->reset(['title', 'description', 'location', 'date', 'newsCategory', 'newsType', 'image']);
        $this->showForm = false;
        
        // Show success message
        session()->flash('message', 'News/Event successfully added.');
        
        // Refresh the page
        $this->resetPage();
    }
    
    public function render()
    {
        // Simply get all news events ordered by date
        $News = DB::table('news_events')
                    ->orderBy('date', 'desc')
                    ->paginate(9);
        
        return view('pages.NewsPage.newshr', [
            'News' => $News
        ]);
    }
}