@include('partials.head')
<livewire:navigation/>

<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">News & Events</h1>
        <p class=" mt-2">Stay updated with the latest happenings at Northern Luzon Adventist Hospital</p>
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
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2 line-clamp-2">{{ $item->title }}</h2>
                <p class="text-gray-600 mb-3 line-clamp-2">{{ $item->description }}</p>
                <p class="text-sm text-gray-500 mb-4">
                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $item->location }}
                </p>
                <a href="{{ route('nlah.news.detail', $item->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    Read More 
                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="far fa-newspaper text-gray-400 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No news or events found.</p>
            <p class="text-gray-400">Check back later for updates.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination Links - Only show if it's a paginator instance -->
    @if(method_exists($newsEvents, 'links') && $newsEvents->hasPages())
    <div class="mt-12">
        <div class="flex justify-center">
            {{ $newsEvents->links() }}
        </div>
        
        <!-- Page information -->
        <div class="text-center text-sm text-gray-500 mt-4">
            Page {{ $newsEvents->currentPage() }} of {{ $newsEvents->lastPage() }}
        </div>
    </div>
    @endif
</main>

<livewire:footer/>