<div>
    <!-- Success Message -->
    @if(session()->has('message'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('message') }}
    </div>
    @endif

    <!-- Collapsible Form for Adding/Editing News -->
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-8" x-data="{ open: @entangle('showForm') }">
        <button @click="open = !open; if(!open) @this.cancelForm()" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition">
            <span class="font-bold text-gray-700">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                @if($isEditing)
                    Edit News / Event
                @else
                    Add News / Event
                @endif
            </span>
            <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open" x-collapse class="p-6 border-t border-gray-100 bg-gray-50/50">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Title -->
                    <div class="lg:col-span-2">
                        <label class="text-xs font-bold uppercase text-gray-500">Title *</label>
                        <input type="text" wire:model="title" class="w-full mt-1 rounded-md border-gray-300 p-2 border focus:ring-2 focus:ring-blue-500">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Date *</label>
                        <input type="date" wire:model="date" class="w-full mt-1 rounded-md border-gray-300 p-2 border focus:ring-2 focus:ring-blue-500">
                        @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Type *</label>
                        <select wire:model="newsType" class="w-full mt-1 rounded-md border-gray-300 p-2 border bg-white focus:ring-2 focus:ring-blue-500">
                            <option value="News">News</option>
                            <option value="Event">Event</option>
                        </select>
                        @error('newsType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Category *</label>
                        <select wire:model="newsCategory" class="w-full mt-1 rounded-md border-gray-300 p-2 border bg-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Select category...</option>
                            <option value="Medical">Medical</option>
                            <option value="Community">Community</option>
                            <option value="Announcement">Announcement</option>
                            <option value="Health Tips">Health Tips</option>
                            <option value="Hospital Update">Hospital Update</option>
                        </select>
                        @error('newsCategory') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Location</label>
                        <input type="text" wire:model="location" class="w-full mt-1 rounded-md border-gray-300 p-2 border focus:ring-2 focus:ring-blue-500" placeholder="e.g., Hospital Main Hall">
                        @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Image {{ $isEditing ? '(Leave empty to keep current)' : '*' }}</label>
                        <input type="file" wire:model="image" class="w-full mt-1 rounded-md border-gray-300 p-2 border focus:ring-2 focus:ring-blue-500" accept="image/*">
                        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        
                        <!-- Image Preview -->
                        @if ($image)
                        <div class="mt-2">
                            <p class="text-xs text-gray-500 mb-1">New Image:</p>
                            <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg">
                        </div>
                        @elseif($oldImage && $isEditing)
                        <div class="mt-2">
                            <p class="text-xs text-gray-500 mb-1">Current Image:</p>
                            <img src="{{ asset('storage/news/' . $oldImage) }}" class="h-20 w-20 object-cover rounded-lg">
                        </div>
                        @endif
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="text-xs font-bold uppercase text-gray-500">Description *</label>
                        <textarea wire:model="description" rows="4" class="w-full mt-1 rounded-md border-gray-300 p-2 border focus:ring-2 focus:ring-blue-500" placeholder="Write the full description here..."></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" @click="open = false; @this.cancelForm()" class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium">
                        Cancel
                    </button>
                    <flux:button type="submit" class="px-6 py-2 rounded-md font-bold transition flex items-center" variant="primary" color="blue">
                        @if($isEditing)
                            Update
                        @else
                            Publish
                        @endif
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold">News & Events</h1>
        <p class="text-gray-600 mt-2">Stay updated with the latest happenings at Northern Luzon Adventist Hospital</p>
    </div>

    <!-- News & Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($News as $item)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition group">
            <div class="relative h-48 overflow-hidden">
                <img src="{{ asset('storage/news/' . $item->image) }}" 
                     alt="{{ $item->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute top-4 right-4">
                    <span class="text-xs font-semibold {{ $item->type == 'News' ? 'bg-green-500' : 'bg-blue-500' }} text-white px-3 py-1 rounded-full">
                        {{ $item->type }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                        {{ $item->category }}
                    </span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xl font-bold text-gray-800">{{ $item->title }}</span>
                    <span class="text-xs bg-gray-100 text-black px-4 py-1 rounded">
                        <i class="fas fa-map-marker-alt"></i> {{ $item->location }}
                    </span> 
                </div>

                <details class="mb-4 group">
                    <summary class="flex items-center justify-between cursor-pointer text-sm list-none">
                        <p class="text-gray-600 line-clamp-2">
                            {{ \Str::words($item->description, 5, '...') }}
                        </p>
                        <svg class="w-5 h-5 transition-transform group-open:rotate-180 text-gray-600 bg-gray-100 rounded-xl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="mt-3 p-3 bg-gray-50 rounded-md text-sm text-gray-600">
                        @if($item->type == 'Event')
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($item->date)->format('F d, Y h:i A') }}</p>
                            <p><strong>Location:</strong> {{ $item->location }}</p>
                            <p class="mt-2"><strong>Full Description:</strong> {{ $item->description }}</p>
                        @else
                            <p>{{ $item->description }}</p>
                        @endif
                    </div>
                </details>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-2 mt-5">
                    <button wire:click="edit({{ $item->id }})" 
                            class="text-small bg-blue-100 text-blue-600 px-4 py-1 rounded hover:bg-blue-200 transition">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <button wire:click="delete({{ $item->id }})" 
                            wire:confirm="Are you sure you want to delete this item?" 
                            class="text-small bg-gray-100 text-red-600 px-4 py-1 rounded hover:bg-red-100 transition">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <p class="text-gray-500 text-lg">No news or events found.</p>
            <p class="text-gray-400">Check back later for updates.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if($News instanceof \Illuminate\Pagination\LengthAwarePaginator && $News->hasPages())
    <div class="mt-12">
        <div class="flex justify-center">
            {{ $News->links() }}
        </div>
        
        <!-- Page information -->
        <div class="text-center text-sm text-gray-500 mt-4">
            Showing {{ $News->firstItem() }} to {{ $News->lastItem() }} of {{ $News->total() }} results
        </div>
    </div>
    @endif
</div>