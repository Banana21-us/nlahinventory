@include('partials.head')
<livewire:navigation/>

<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">

  {{-- Page header --}}
  <div class="mb-16">
    <h1 class="text-4xl md:text-6xl font-bold tracking-tight leading-[1.05] text-zinc-900 mb-4">
      News &amp; Events
    </h1>
    <p class="text-lg md:text-xl text-zinc-500 font-medium max-w-xl leading-snug">
      Stay updated with the latest happenings at Northern Luzon Adventist Hospital.
    </p>
    <div class="w-full border-t border-dashed border-zinc-300 mt-10"></div>
  </div>

  {{-- Grid --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($newsEvents as $item)
    <article class="group bg-white border border-zinc-200 rounded-xl overflow-hidden hover:border-zinc-400 hover:shadow-md transition-all duration-200 flex flex-col">

      {{-- Thumbnail --}}
      <div class="relative h-48 overflow-hidden bg-zinc-100">
        <img
          src="{{ asset('storage/news/' . $item->image) }}"
          alt="{{ $item->title }}"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          onerror="this.closest('.relative').classList.add('flex','items-center','justify-center'); this.remove();"
        >
        <span class="absolute top-3 left-3 text-[11px] font-semibold px-2.5 py-1 rounded-full
          {{ $item->type === 'News' ? 'bg-zinc-900 text-white' : 'bg-[#e8dec9] text-[#5a4e3a]' }}">
          {{ $item->type }}
        </span>
      </div>

      {{-- Body --}}
      <div class="p-5 flex flex-col flex-1">
        {{-- Meta row --}}
        <div class="flex items-center gap-2 mb-3 flex-wrap">
          <span class="text-xs text-zinc-400">
            {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}
          </span>
          @if($item->category)
          <span class="text-[11px] bg-zinc-100 text-zinc-500 px-2 py-0.5 rounded-md font-medium">{{ $item->category }}</span>
          @endif
          @if($item->location)
          <span class="text-[11px] text-zinc-400 flex items-center gap-1 ml-auto">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $item->location }}
          </span>
          @endif
        </div>

        {{-- Title --}}
        <h2 class="text-base font-semibold text-zinc-900 leading-snug mb-2 tracking-tight">
          {{ $item->title }}
        </h2>

        {{-- Description expand --}}
        <details class="flex-1 group/det">
          <summary class="flex items-start justify-between gap-2 cursor-pointer list-none">
            <p class="text-sm text-zinc-500 line-clamp-2 leading-relaxed">
              {{ \Str::words($item->description, 8, '…') }}
            </p>
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-zinc-400 transition-transform group-open/det:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <div class="mt-3 pt-3 border-t border-zinc-100 text-sm text-zinc-500 leading-relaxed space-y-1.5">
            @if($item->type === 'Event')
              <p><span class="font-medium text-zinc-700">Date:</span> {{ \Carbon\Carbon::parse($item->date)->format('F d, Y g:i A') }}</p>
              @if($item->location)
              <p><span class="font-medium text-zinc-700">Location:</span> {{ $item->location }}</p>
              @endif
              @if($item->full_description ?? $item->description)
              <p class="mt-2">{{ $item->full_description ?? $item->description }}</p>
              @endif
            @else
              <p>{{ $item->full_description ?? $item->description }}</p>
            @endif
          </div>
        </details>
      </div>
    </article>

    @empty
    <div class="col-span-3 py-20 text-center">
      <div class="w-12 h-12 rounded-xl bg-zinc-100 flex items-center justify-center mx-auto mb-4">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="text-zinc-400">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
      </div>
      <p class="text-zinc-500 font-medium">No news or events yet.</p>
      <p class="text-zinc-400 text-sm mt-1">Check back later for updates.</p>
    </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if(method_exists($newsEvents, 'links'))
  <div class="mt-12">
    {{ $newsEvents->links() }}
  </div>
  @endif

</main>

<livewire:footer/>

<style>
details > summary { list-style: none; }
details > summary::-webkit-details-marker { display: none; }
details > summary::marker { display: none; }
</style>
