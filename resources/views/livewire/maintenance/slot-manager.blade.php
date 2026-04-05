<div class="min-h-screen bg-zinc-50 pb-20">

    {{-- Active Round Banner --}}
    @if ($activeRound)
        @php
            $done = $activeRound->items->whereIn('status', ['completed','skipped'])->count();
            $total = $activeRound->items->count();
            $pct = $total > 0 ? round(($done / $total) * 100) : 0;
        @endphp
        <div class="bg-gradient-to-r from-blue-700 to-teal-600 text-white px-4 py-3">
            <div class="flex items-center justify-between gap-3 mb-2">
                <div class="text-sm font-semibold">Round in progress &mdash; {{ $done }}/{{ $total }} areas done</div>
                <a href="{{ route('maintenance.round', $activeRound->id) }}"
                   class="bg-white text-blue-700 font-bold text-sm px-4 py-2 rounded-lg whitespace-nowrap">
                    Continue &rarr;
                </a>
            </div>
            <div class="w-full bg-white/30 rounded-full h-1.5">
                <div class="h-1.5 bg-white rounded-full" style="width: {{ $pct }}%"></div>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="px-4 pt-5 pb-3">
        <h1 class="text-xl font-bold text-zinc-800">My Rounds</h1>
        <p class="text-sm text-zinc-500 mt-1">Set up your location slots and start a round.</p>
    </div>

    {{-- Slot Cards --}}
    <div class="px-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($mySlots as $slot)
            @php
                $isLocked = $slot->slotLocations->contains(
                    fn ($sl) => in_array($sl->location_area_id, $lockedLocationIds)
                );
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">

                {{-- Slot Header --}}
                <div class="px-4 pt-4 pb-2 flex items-center justify-between">
                    @if ($editingSlotId === $slot->id)
                        <div class="flex items-center gap-2 flex-1">
                            <input type="text"
                                   wire:model="editingSlotName"
                                   wire:keydown.enter="saveSlotName({{ $slot->id }}, editingSlotName)"
                                   class="border border-zinc-300 rounded-lg px-3 py-1.5 text-sm font-semibold flex-1 min-w-0"
                                   autofocus>
                            <button wire:click="saveSlotName({{ $slot->id }}, '{{ addslashes($editingSlotName) }}')"
                                    class="text-xs bg-teal-600 text-white px-3 py-1.5 rounded-lg">Save</button>
                            <button wire:click="$set('editingSlotId', null)"
                                    class="text-xs text-zinc-500 px-2 py-1.5">&#x2715;</button>
                        </div>
                    @else
                        <button wire:click="startEditName({{ $slot->id }}, '{{ addslashes($slot->slot_name) }}')"
                                class="font-bold text-zinc-800 text-base hover:text-teal-700 text-left">
                            {{ $slot->slot_name }}
                            <span class="text-zinc-400 font-normal text-xs ml-1">&#x270E;</span>
                        </button>
                        <span class="text-xs bg-zinc-100 text-zinc-500 px-2 py-1 rounded-full">
                            {{ $slot->slotLocations->count() }} locations
                        </span>
                    @endif
                </div>

                {{-- Location List with Sortable --}}
                <div class="px-3 pb-2"
                     id="slot-list-{{ $slot->id }}"
                     x-data
                     x-init="
                        if (typeof Sortable !== 'undefined') {
                            Sortable.create($el, {
                                handle: '.drag-handle',
                                animation: 150,
                                onEnd() {
                                    const ids = Array.from($el.querySelectorAll('[data-id]')).map(e => +e.dataset.id);
                                    Livewire.dispatch('sortable-updated', { slotId: {{ $slot->id }}, orderedIds: ids });
                                }
                            });
                        }
                     ">
                    @forelse ($slot->slotLocations as $index => $slotLoc)
                        @php $loc = $slotLoc->locationArea; @endphp
                        <div class="flex items-center gap-2 py-2.5 px-2 border-b border-zinc-100 last:border-0"
                             data-id="{{ $slotLoc->location_area_id }}">
                            <span class="text-zinc-400 cursor-grab text-lg select-none drag-handle touch-none">&#8801;</span>
                            <span class="text-xs font-bold text-zinc-400 w-5 text-center flex-shrink-0">{{ $index + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-zinc-800 truncate">{{ $loc?->name ?? '—' }}</div>
                                <div class="text-xs text-zinc-400">{{ $loc?->floor ?? '' }}</div>
                            </div>
                            @if (in_array($slotLoc->location_area_id, $lockedLocationIds))
                                <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Taken</span>
                            @else
                                <button wire:click="removeLocation({{ $slot->id }}, {{ $slotLoc->location_area_id }})"
                                        class="text-zinc-400 hover:text-red-500 w-9 h-9 flex items-center justify-center text-xl rounded-lg flex-shrink-0">
                                    &times;
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="py-4 text-center text-sm text-zinc-400">No locations added yet.</div>
                    @endforelse
                </div>

                {{-- Add Location --}}
                <div class="px-3 pb-3">
                    <button wire:click="openLocationPicker({{ $slot->id }})"
                            wire:loading.attr="disabled"
                            wire:target="openLocationPicker({{ $slot->id }})"
                            class="w-full border-2 border-dashed border-zinc-300 text-zinc-500 text-sm font-medium rounded-xl py-3 hover:border-teal-400 hover:text-teal-600 transition-colors">
                        <span wire:loading.remove wire:target="openLocationPicker({{ $slot->id }})">+ Add Location</span>
                        <span wire:loading wire:target="openLocationPicker({{ $slot->id }})">Loading...</span>
                    </button>
                </div>

                {{-- Start Round --}}
                <div class="px-3 pb-4">
                    @if ($isLocked)
                        <button disabled class="w-full bg-zinc-200 text-zinc-400 font-bold py-4 rounded-xl text-base cursor-not-allowed">
                            &#9654; Start Round (Locked)
                        </button>
                    @elseif ($slot->slotLocations->isEmpty())
                        <button disabled class="w-full bg-zinc-200 text-zinc-400 font-bold py-4 rounded-xl text-base cursor-not-allowed">
                            &#9654; Start Round
                        </button>
                    @else
                        <button wire:click="startRound({{ $slot->id }})"
                                wire:loading.attr="disabled"
                                wire:target="startRound({{ $slot->id }})"
                                class="w-full font-bold py-4 rounded-xl text-base text-white"
                                style="background: linear-gradient(135deg, #1e3a5f, #097b86);">
                            <span wire:loading.remove wire:target="startRound({{ $slot->id }})">&#9654; Start Round</span>
                            <span wire:loading wire:target="startRound({{ $slot->id }})">Starting&hellip;</span>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Emergency Pool --}}
    @if ($emergencyPool->isNotEmpty())
        <div class="px-4 mt-6">
            <h2 class="text-base font-bold text-zinc-700 mb-3">&#x1F6A8; Emergency Pool</h2>
            <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden">
                @foreach ($emergencyPool as $loc)
                    <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 last:border-0">
                        <div>
                            <div class="text-sm font-medium text-zinc-800">{{ $loc->name }}</div>
                            <div class="text-xs text-zinc-400">{{ $loc->floor }}</div>
                        </div>
                        <button wire:click="grabFromPool({{ $loc->id }})"
                                class="text-sm font-semibold bg-amber-100 text-amber-700 px-4 py-2 rounded-xl">
                            Grab
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Location Picker Modal --}}
    @if ($showLocationPicker)
        <div class="fixed inset-0 z-50 flex flex-col justify-end"
             x-data="{ open: false }"
             x-init="$nextTick(() => { open = true })">

            {{-- Backdrop: only closes if you tap the dark area --}}
            <div class="absolute inset-0 bg-black/50"
                 x-show="open"
                 x-transition:enter="transition duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 wire:click="closeLocationPicker"></div>

            {{-- Sheet --}}
            <div class="relative bg-white rounded-t-2xl max-h-[85vh] flex flex-col"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 @click.stop>

                {{-- Sheet Header --}}
                <div class="px-4 pt-4 pb-2 border-b border-zinc-100 flex-shrink-0">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-lg text-zinc-800">Add Location</h3>
                        <button wire:click="closeLocationPicker"
                                class="text-zinc-400 text-2xl w-10 h-10 flex items-center justify-center rounded-full hover:bg-zinc-100">
                            &times;
                        </button>
                    </div>
                    <input type="text"
                           wire:model.live.debounce.300ms="locationSearch"
                           placeholder="Search locations&hellip;"
                           class="w-full border border-zinc-300 rounded-xl px-4 py-3 text-sm">
                </div>

                {{-- Location List --}}
                <div class="overflow-y-auto flex-1">
                    @php
                        $pickerSlot = $mySlots->firstWhere('id', $pickerSlotId);
                        $slotLocIds = $pickerSlot?->slotLocations->pluck('location_area_id')->toArray() ?? [];
                    @endphp
                    @forelse ($filteredLocations as $loc)
                        @php
                            $inSlot  = in_array($loc->id, $slotLocIds);
                            $locked  = in_array($loc->id, $lockedLocationIds);
                        @endphp
                        <button wire:click="{{ $inSlot ? "removeLocation($pickerSlotId, $loc->id)" : "addLocation($pickerSlotId, $loc->id)" }}"
                                @if($locked) disabled @endif
                                class="w-full flex items-center justify-between px-4 py-3.5 border-b border-zinc-100 last:border-0 text-left
                                    {{ $locked ? 'opacity-40 cursor-not-allowed' : 'hover:bg-zinc-50 active:bg-zinc-100' }}">
                            <div>
                                <div class="text-sm font-medium text-zinc-800">{{ $loc->name }}</div>
                                <div class="text-xs text-zinc-400">{{ $loc->floor }}</div>
                            </div>
                            <span class="text-lg ml-3 flex-shrink-0">
                                @if ($locked) &#x1F512;
                                @elseif ($inSlot) &#x2705;
                                @else <span class="text-zinc-300">&#x25CB;</span>
                                @endif
                            </span>
                        </button>
                    @empty
                        <div class="py-8 text-center text-sm text-zinc-400">No locations found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

</div>
