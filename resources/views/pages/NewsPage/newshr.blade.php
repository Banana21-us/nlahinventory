<style>
    /* ── Brand Palettesss ─────────────────────────── */
    /* Deep Blue  #015581 | Gold  #f0b626 | Teal  #027c8b */

    .brand-bg-blue        { background-color: #015581; }
    .brand-bg-blue-light  { background-color: #e6f0f7; }
    .brand-text-blue      { color: #015581; }

    .brand-bg-gold        { background-color: #f0b626; }
    .brand-bg-gold-light  { background-color: #fef8e7; }
    .brand-text-gold      { color: #f0b626; }

    .brand-bg-teal        { background-color: #027c8b; }
    .brand-bg-teal-light  { background-color: #e6f4f5; }
    .brand-text-teal      { color: #027c8b; }

    /* Buttons */
    .brand-btn-blue {
        background-color: #015581;
        color: #fff;
        transition: background-color .15s ease;
    }
    .brand-btn-blue:hover  { background-color: #01406a; }

    .brand-btn-teal {
        background-color: #027c8b;
        color: #fff;
        transition: background-color .15s ease;
    }
    .brand-btn-teal:hover  { background-color: #016070; }

    /* Focus ring */
    .brand-focus:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(1,85,129,.2);
        border-color: #015581;
    }
    .teal-focus:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2,124,139,.2);
        border-color: #027c8b;
    }

    /* Hover row */
    .brand-row-hover:hover { background-color: #f0f7fc; }

    /* Card hover */
    .news-card { transition: box-shadow .2s ease, transform .2s ease; }
    .news-card:hover {
        box-shadow: 0 10px 30px -6px rgba(1,85,129,.18);
        transform: translateY(-2px);
    }

    /* Edit button */
    .edit-btn       { background-color: #e6f0f7; color: #015581; }
    .edit-btn:hover { background-color: #cde0ef; }

    @keyframes shrink { from { width:100% } to { width:0% } }
</style>

<div class="max-w-7xl mx-auto py-8 px-4">

    {{-- ═══════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 brand-bg-blue-light rounded-lg">
                <svg class="w-6 h-6 brand-text-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Content Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">News &amp; Events</h1>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ADD / EDIT COLLAPSIBLE FORM
    ═══════════════════════════════════════════ --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden"
         x-data="{ open: @entangle('showForm') }">

        {{-- Toggle Button --}}
        <button
            @click="open = !open; if(!open) @this.cancelForm()"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none"
        >
            <div class="flex items-center">
                <div class="p-2 brand-bg-blue-light rounded-lg mr-4">
                    <svg class="w-5 h-5 brand-text-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" x-show="!open"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">
                    @if($isEditing) Edit News / Event @else News / Event Entry @endif
                </h2>
            </div>
            <span class="text-sm font-medium brand-text-blue"
                  x-text="open ? 'Minimize' : '{{ $isEditing ? 'Edit Entry' : 'Add News / Event' }}'">
            </span>
        </button>

        {{-- Collapsible Body --}}
        <div
            x-show="open"
            x-collapse
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-6 border-t border-gray-100 bg-gray-50/30"
        >
            <form wire:submit.prevent="save" enctype="multipart/form-data"
                  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Title --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Title *</label>
                    <input type="text" wire:model="title" placeholder="Enter headline…"
                           class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Date *</label>
                    <input type="date" wire:model="date"
                           class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Type *</label>
                    <select wire:model="newsType"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                        <option value="News">News</option>
                        <option value="Event">Event</option>
                    </select>
                    @error('newsType') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Category *</label>
                    <select wire:model="newsCategory"
                            class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2 bg-white">
                        <option value="">— Select category —</option>
                        <option value="Medical">Medical</option>
                        <option value="Community">Community</option>
                        <option value="Announcement">Announcement</option>
                        <option value="Health Tips">Health Tips</option>
                        <option value="Hospital Update">Hospital Update</option>
                    </select>
                    @error('newsCategory') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Location</label>
                    <input type="text" wire:model="location" placeholder="e.g., Hospital Main Hall"
                           class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('location') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                        Image {{ $isEditing ? '(Leave empty to keep current)' : '*' }}
                    </label>
                    <input type="file" wire:model="image" accept="image/*"
                           class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"/>
                    @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                    @if($image)
                        <div class="mt-2 flex items-center gap-2">
                            <p class="text-xs text-gray-400">New image:</p>
                            <img src="{{ $image->temporaryUrl() }}"
                                 class="h-12 w-12 object-cover rounded-lg border border-gray-200"/>
                        </div>
                    @elseif($oldImage && $isEditing)
                        <div class="mt-2 flex items-center gap-2">
                            <p class="text-xs text-gray-400">Current image:</p>
                            <img src="{{ asset('storage/news/' . $oldImage) }}"
                                 class="h-12 w-12 object-cover rounded-lg border border-gray-200"/>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Description *</label>
                    <textarea wire:model="description" rows="4"
                              placeholder="Write the full description here…"
                              class="brand-focus block w-full rounded-md border border-gray-300 shadow-sm sm:text-sm p-2"></textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Actions --}}
                <div class="lg:col-span-3 flex justify-end items-center gap-3 pt-4 border-t border-gray-100 mt-2">
                    <button type="button"
                            @click="open = false; @this.cancelForm()"
                            class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancel
                    </button>
                    <button type="submit"
                            class="brand-btn-blue text-sm font-bold py-2 px-10 rounded shadow-md active:scale-95 flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">
                            @if($isEditing) Update @else Publish @endif
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Saving…
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         NEWS & EVENTS GRID TABLE SECTION
    ═══════════════════════════════════════════ --}}
    <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        {{-- Section Header --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 brand-bg-teal-light rounded-lg">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">All Entries</h3>
                @if($News instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                        {{ $News->total() }} {{ Str::plural('entry', $News->total()) }}
                    </span>
                @else
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                        {{ $News->count() }} {{ Str::plural('entry', $News->count()) }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Cards Grid --}}
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($News as $item)
                    @php $isEvent = $item->type === 'Event'; @endphp

                    <div class="news-card bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col shadow-sm">

                        {{-- Image --}}
                        <div class="relative h-44 overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/news/' . $item->image) }}"
                                 alt="{{ $item->title }}"
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300"/>

                            {{-- Type pill --}}
                            <div class="absolute top-3 right-3">
                                @if($isEvent)
                                    <span class="text-xs font-bold text-white px-2.5 py-1 rounded-full shadow brand-bg-blue">
                                        Event
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-white px-2.5 py-1 rounded-full shadow brand-bg-teal">
                                        News
                                    </span>
                                @endif
                            </div>

                            {{-- Category pill --}}
                            <div class="absolute bottom-3 left-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border"
                                      style="background-color:#fef8e7;color:#7a5000;border-color:#f0b626;">
                                    {{ $item->category }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-4 flex flex-col flex-1">

                            {{-- Meta --}}
                            <div class="flex items-center justify-between mb-2 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}
                                </span>
                                @if($item->location)
                                    <span class="flex items-center gap-1 truncate max-w-[130px]">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $item->location }}</span>
                                    </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h3 class="text-sm font-bold text-gray-900 mb-2 leading-snug">{{ $item->title }}</h3>

                            {{-- Expandable description --}}
                            <details class="flex-1 group mb-3">
                                <summary class="flex items-start justify-between cursor-pointer list-none gap-2">
                                    <p class="text-xs text-gray-500 line-clamp-2">
                                        {{ \Str::words($item->description, 14, '…') }}
                                    </p>
                                    <svg class="w-4 h-4 shrink-0 transition-transform group-open:rotate-180 text-gray-400 mt-0.5 brand-bg-gold-light rounded"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </summary>
                                <div class="mt-2 p-3 rounded-lg text-xs text-gray-600 brand-bg-blue-light">
                                    @if($isEvent)
                                        <p class="mb-1 font-semibold">{{ \Carbon\Carbon::parse($item->date)->format('F d, Y') }}</p>
                                        <p class="mb-2 text-gray-500">{{ $item->location }}</p>
                                        <p>{{ $item->description }}</p>
                                    @else
                                        <p>{{ $item->description }}</p>
                                    @endif
                                </div>
                            </details>

                            {{-- Action Buttons --}}
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2 mt-auto">
                                <button wire:click="edit({{ $item->id }})"
                                        class="edit-btn rounded-md px-2.5 py-1.5 text-xs font-semibold shadow-sm transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                        wire:confirm="Are you sure you want to delete this item?"
                                        class="text-red-500 hover:text-red-700 font-semibold text-xs transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-14 text-center">
                        <div class="flex flex-col items-center text-gray-400">
                            <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <p class="text-sm font-medium">No news or events yet.</p>
                            <p class="text-xs mt-1">Click "Add News / Event" above to publish your first entry.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if($News instanceof \Illuminate\Pagination\LengthAwarePaginator && $News->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-gray-400">
                    Showing {{ $News->firstItem() }}–{{ $News->lastItem() }} of {{ $News->total() }} results
                </p>
                {{ $News->links() }}
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════
         TOAST NOTIFICATION
    ═══════════════════════════════════════════ --}}
    @if(session()->has('message'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5"
        >
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full brand-bg-teal-light">
                    <svg class="w-5 h-5 brand-text-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Success!</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </div>
            {{-- Gold progress bar --}}
            <div class="h-1 brand-bg-gold" style="animation: shrink 4s linear forwards;"></div>
        </div>
    @endif

</div>