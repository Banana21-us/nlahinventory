<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class News extends Component
{
    use WithPagination, WithFileUploads;
    public $title;
    public $description;
    public $location;
    public $date;
    public $image;
    public $newsCategory;
    public $newsType = 'News';
    public $editId = null;
    public $oldImage;
    
    // UI state
    public $showForm = false;
    public $isEditing = false;
    
    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'required|min:10',
        'location' => 'required',
        'date' => 'required|date',
        'newsCategory' => 'required',
        'newsType' => 'required|in:News,Event',
        'image' => 'nullable|image|max:2048', // Made nullable for editing
    ];
    
    public function save()
    {
        if ($this->isEditing) {
            $this->update();
        } else {
            $this->store();
        }
    }
    
    public function store()
    {
        $this->validate();
        
        // Handle image upload
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
        $this->resetForm();
        
        session()->flash('message', 'News/Event successfully added.');
        
        // Refresh the page
        $this->resetPage();
    }
    
    public function edit($id)
    {
        // Fetch the news item
        $news = DB::table('news_events')->where('id', $id)->first();
        
        if ($news) {
            $this->editId = $id;
            $this->title = $news->title;
            $this->description = $news->description;
            $this->location = $news->location;
            $this->date = $news->date;
            $this->newsCategory = $news->category;
            $this->newsType = $news->type;
            $this->oldImage = $news->image;
            
            $this->isEditing = true;
            $this->showForm = true;
        }
    }
    
    public function update()
    {
        $this->validate([
            'title' => 'required|min:3',
            'description' => 'required|min:10',
            'location' => 'required',
            'date' => 'required|date',
            'newsCategory' => 'required',
            'newsType' => 'required|in:News,Event',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $updateData = [
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'category' => $this->newsCategory,
            'type' => $this->newsType,
            'updated_at' => now(),
        ];
        
        // Handle image upload if a new image is provided
        if ($this->image) {
            // Delete old image
            if ($this->oldImage) {
                $oldImagePath = storage_path('app/public/news/' . $this->oldImage);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            
            // Upload new image
            $imageName = time() . '_' . $this->image->getClientOriginalName();
            $this->image->storeAs('news', $imageName, 'public');
            $updateData['image'] = $imageName;
        }
        
        // Update database
        DB::table('news_events')->where('id', $this->editId)->update($updateData);
        
        // Reset form and close it
        $this->resetForm();
        
        session()->flash('message', 'News/Event successfully updated.');
        
        // Refresh the page
        $this->resetPage();
    }
    
    public function delete($id)
    {
        // Get the news item to delete its image
        $news = DB::table('news_events')->where('id', $id)->first();
        
        if ($news && $news->image) {
            // Delete the image file from storage
            $imagePath = storage_path('app/public/news/' . $news->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        
        // Delete from database
        DB::table('news_events')->where('id', $id)->delete();
        
        $this->resetPage();
        session()->flash('message', 'Deleted successfully.');
    }
    
    public function resetForm()
    {
        $this->reset(['title', 'description', 'location', 'date', 'newsCategory', 'newsType', 'image', 'editId', 'oldImage']);
        $this->showForm = false;
        $this->isEditing = false;
    }
    
    public function cancelForm()
    {
        $this->resetForm();
    }
    
    public function render()
    {
        $News = DB::table('news_events')
                    ->orderBy('date', 'desc')
                    ->paginate(9);
        
        return view('pages.NewsPage.newshr', [
            'News' => $News
        ]);
    }
}