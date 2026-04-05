<div class="min-h-screen bg-zinc-50">

    @if (session('message'))
        <div class="bg-green-100 text-green-800 px-4 py-3 text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row min-h-screen">

        {{-- LEFT PANEL: Round List --}}
        <div class="w-full md:w-1/3 bg-white border-r border-zinc-200 flex flex-col">
            <div class="px-4 py-4 border-b border-zinc-100">
                <h2 class="font-bold text-lg text-zinc-800">Today's Rounds</h2>
                <p class="text-xs text-zinc-400 mt-0.5">{{ $todayRounds->count() }} round(s) today</p>
            </div>

            <div class="overflow-y-auto flex-1">
                @forelse ($todayRounds as $round)
                    @php
                        $done = $round->items->whereIn('status', ['completed','skipped'])->count();
                        $total = $round->items->count();
                        $statusColors = [
                            'in_progress' => 'bg-blue-100 text-blue-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'abandoned' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <button wire:click="selectRound({{ $round->id }})"
                            class="w-full text-left px-4 py-3.5 border-b border-zinc-100 hover:bg-zinc-50 transition-colors
                                {{ $selectedRoundId === $round->id ? 'border-l-4 bg-teal-50' : '' }}"
                            style="{{ $selectedRoundId === $round->id ? 'border-left-color: #097b86' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-none"
                                 style="background: linear-gradient(135deg, #1e3a5f, #097b86);">
                                {{ strtoupper(substr($round->user?->name ?? '?', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-sm text-zinc-800 truncate">
                                    {{ $round->user?->name ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-zinc-400 mt-0.5">
                                    {{ $round->started_at->format('h:i A') }} · {{ $done }}/{{ $total }} items
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColors[$round->status] ?? '' }}">
                                {{ str_replace('_', ' ', $round->status) }}
                            </span>
                        </div>
                    </button>
                @empty
                    <div class="px-4 py-8 text-center text-sm text-zinc-400">
                        No rounds today yet.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT PANEL: Item Grid --}}
        <div class="flex-1 flex flex-col">
            @if ($selectedRound)
                <div class="px-4 py-3 bg-white border-b border-zinc-200 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-semibold text-zinc-700">{{ $selectedRound->user?->name }}</span>
                        {{-- Filter Tabs --}}
                        @foreach (['all' => 'All', 'pending' => 'Pending', 'flagged' => 'Flagged'] as $key => $label)
                            <button wire:click="setFilter('{{ $key }}')"
                                    class="text-sm px-3 py-1.5 rounded-lg font-medium transition-colors
                                        {{ $filter === $key ? 'bg-zinc-800 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    <button wire:click="bulkApprove({{ $selectedRound->id }})"
                            class="text-sm font-bold px-4 py-2 rounded-xl text-white"
                            style="background: linear-gradient(135deg, #1e3a5f, #097b86);">
                        ✅ Approve All Clean
                    </button>
                </div>

                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 overflow-y-auto">
                    @forelse ($filteredItems as $item)
                        @php
                            $locationName = $item->locationArea?->name ?? '—';
                            $partName = $item->part?->areaPart?->name ?? '—';
                            $isItemCR = str_contains($locationName, '| CR') || str_ends_with(trim($locationName), 'CR');
                            $borderColors = [
                                'pending' => 'border-zinc-300',
                                'approved' => 'border-green-400',
                                'flagged' => 'border-red-400',
                                'rejected' => 'border-orange-400',
                            ];
                            $border = $borderColors[$item->verification_status] ?? 'border-zinc-300';
                        @endphp
                        <div class="bg-white rounded-2xl border-2 {{ $border }} shadow-sm overflow-hidden">
                            {{-- Photo / CR badge --}}
                            @if ($item->photo_path && !$isItemCR)
                                <button wire:click="enlargePhoto('{{ $item->photo_path }}')"
                                        class="block w-full">
                                    <img src="{{ Storage::url($item->photo_path) }}"
                                         alt="Photo"
                                         class="w-full object-cover"
                                         style="height: 160px;">
                                </button>
                            @elseif ($isItemCR)
                                <div class="w-full flex items-center justify-center bg-blue-50"
                                     style="height: 80px;">
                                    <span class="text-blue-600 font-semibold text-sm">🚻 CR — Checklist Only</span>
                                </div>
                            @else
                                <div class="w-full flex items-center justify-center bg-zinc-100"
                                     style="height: 80px;">
                                    <span class="text-zinc-400 text-sm">No photo</span>
                                </div>
                            @endif

                            <div class="p-3">
                                <div class="font-bold text-sm text-zinc-800">{{ $locationName }}</div>
                                <div class="text-xs text-zinc-500 mt-0.5">{{ $partName }}</div>
                                <div class="text-xs text-zinc-400 mt-1">
                                    {{ $item->completed_at?->format('h:i A') ?? '—' }}
                                </div>

                                @if ($item->status === 'skipped')
                                    <div class="mt-2 text-xs bg-zinc-100 text-zinc-600 px-2 py-1 rounded">
                                        ⏭ Skipped: {{ $item->skip_reason }}
                                    </div>
                                @endif

                                {{-- Action buttons --}}
                                @if ($item->verification_status === 'pending' && $item->status === 'completed')
                                    <div class="mt-3 flex gap-2">
                                        <button wire:click="approveItem({{ $item->id }})"
                                                class="flex-1 bg-green-100 text-green-700 font-semibold text-sm py-2 rounded-xl hover:bg-green-200 transition-colors">
                                            ✅ Approve
                                        </button>
                                        <button wire:click="flagItem({{ $item->id }})"
                                                x-data
                                                @click="$wire.flagComment = prompt('Flag comment:') || ''"
                                                class="flex-1 bg-red-100 text-red-700 font-semibold text-sm py-2 rounded-xl hover:bg-red-200 transition-colors">
                                            ⚠️ Flag
                                        </button>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <span class="text-xs font-semibold
                                            {{ $item->verification_status === 'approved' ? 'text-green-600' : '' }}
                                            {{ $item->verification_status === 'flagged' ? 'text-red-600' : '' }}
                                            {{ $item->verification_status === 'rejected' ? 'text-orange-600' : '' }}">
                                            {{ ucfirst($item->verification_status) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-zinc-400 text-sm">
                            No items match the selected filter.
                        </div>
                    @endforelse
                </div>

            @else
                <div class="flex-1 flex items-center justify-center text-zinc-400 text-sm">
                    Select a round to begin verification.
                </div>
            @endif
        </div>

    </div>

    {{-- Photo Lightbox --}}
    @if ($enlargedPhoto)
        <div class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
             wire:click="closePhoto">
            <button class="absolute top-4 right-4 text-white text-3xl leading-none z-10"
                    wire:click.stop="closePhoto">×</button>
            <img src="{{ Storage::url($enlargedPhoto) }}"
                 alt="Photo"
                 class="max-w-full max-h-full object-contain rounded-xl"
                 wire:click.stop>
        </div>
    @endif

</div>
