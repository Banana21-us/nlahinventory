@include('partials.head')
<livewire:navigation/>

<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">News & Events</h1>
        <p class="mt-2">Stay updated with the latest happenings at Northern Luzon Adventist Hospital</p>
    </div>
    
    <!-- News & Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($newsEvents as $item)
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
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}
                    </span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                        {{ $item->category }}
                    </span>
                    <!-- <p><i class="fas fa-map-marker-alt mr-1"></i> {{ $item->location }}</p> -->
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xl font-bold text-gray-800 ">{{ $item->title }} </span>
                    <span class="text-xs bg-gray-100 text-black px-4 py-1 rounded"> <i class="fas fa-map-marker-alt"></i> {{ $item->location }}</span> 
                </div>
                
                <!-- <p><i class="fas fa-map-marker-alt mr-1"></i> {{ $item->location }}</p> -->
                    <!-- <p class="text-sm text-gray-500 mb-4">
                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $item->location }}
                    </p> -->

                <details class="mb-4 group">
                    <summary class="flex items-center justify-between cursor-pointer text-sm list-none">
                        <p class="text-gray-600 line-clamp-2">
                            {{ \Str::words($item->description, 5, '...') }}
                        </p>
                        <!-- <span class="font-medium text-gray-600 bg-gray-100 rounded-2xl"></span> -->
                        <svg class="w-5 h-5 transition-transform group-open:rotate-180 text-gray-600 bg-gray-100 rounded-xl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="mt-3 p-3 bg-gray-50 rounded-md text-sm text-gray-600">
                        @if($item->type == 'Event')
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($item->date)->format('F d, Y h:i A') }}</p>
                            <p><strong>Location:</strong> {{ $item->location }}</p>
                            <p class="mt-2"><strong>Full Description:</strong> {{ $item->full_description ?? $item->description }}</p>
                        @else
                            <p>{{ $item->full_description ?? $item->description }}</p>
                        @endif
                    </div>
                </details>

                <!-- <a href="{{ route('nlah.news.detail', $item->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    Read More 
                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a> -->
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 flext justify-center tex">
            <i class="far fa-newspaper text-gray-400 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No news or events found.</p>
            <p class="text-gray-400">Check back later for updates.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if(method_exists($newsEvents, 'links'))
        <div class="mt-12">
            {{ $newsEvents->links() }}
        </div>
    @endif
</main>

<livewire:footer/>

<style>
/* Remove default details marker for all browsers */
details > summary {
    list-style: none;
}
details > summary::-webkit-details-marker {
    display: none;
}
details > summary::marker {
    display: none;
}
</style>