<div class="min-h-screen bg-zinc-900 text-white flex flex-col"
     x-data="roundRunner"
     x-init="init()">

    {{-- TOP BAR --}}
    <div class="flex items-center gap-3 px-4 py-3 bg-zinc-800 border-b border-zinc-700 sticky top-0 z-10">
        <button @click="$wire.set('showCancelModal', true)"
                class="text-zinc-400 hover:text-red-400 p-2 -ml-2 rounded-lg text-sm font-medium">
            &#x2715; Cancel
        </button>
        <div class="flex-1 min-w-0">
            <div class="font-bold text-sm truncate text-white">
                {{ $currentItem?->locationArea?->name ?? 'Round Complete' }}
            </div>
            <div class="text-xs text-zinc-400 truncate">
                {{ $currentItem?->part?->areaPart?->name ?? '' }}
            </div>
        </div>
        <span class="text-sm text-zinc-400 font-mono whitespace-nowrap">
            {{ $completedCount }}/{{ $totalItems }}
        </span>
        <button @click="toggleVoice()"
                class="p-2 rounded-lg text-base"
                :class="listening ? 'bg-teal-700 text-white' : 'bg-zinc-700 text-zinc-400'">
            &#x1F399;
        </button>
    </div>

    {{-- Progress Bar --}}
    <div class="w-full bg-zinc-700 h-1.5">
        <div class="h-1.5 bg-teal-500 transition-all duration-500"
             style="width: {{ $totalItems > 0 ? round(($completedCount / $totalItems) * 100) : 0 }}%;"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        @if ($currentItem)

            {{-- ─── PHOTO MODE ─── --}}
            @unless ($isCR)
                <div class="flex-1 flex flex-col">

                    <div class="px-4 pt-4 pb-2 text-center">
                        <div class="text-lg font-bold text-white">{{ $currentItem->locationArea?->name }}</div>
                        <div class="text-sm text-zinc-400 mt-0.5">{{ $currentItem->part?->areaPart?->name }}</div>
                        <div class="inline-flex items-center gap-1 mt-2 bg-teal-900 text-teal-300 text-xs px-3 py-1 rounded-full">
                            &#x1F4F7; Take a photo to complete
                        </div>
                    </div>

                    {{-- Camera viewfinder --}}
                    <div class="relative flex-1 bg-zinc-800 mx-4 rounded-2xl overflow-hidden" style="min-height:38vh;max-height:52vh;">

                        {{-- Live video (HTTPS) --}}
                        <video id="rr-video" autoplay playsinline muted
                               class="w-full h-full object-cover"
                               x-show="cameraMode === 'live'"></video>

                        {{-- Captured preview --}}
                        <img id="rr-preview" alt="captured"
                             class="w-full h-full object-cover"
                             x-show="cameraMode === 'preview'" style="display:none;">

                        {{-- Fallback placeholder (HTTP) --}}
                        <div x-show="cameraMode === 'fallback'"
                             class="absolute inset-0 flex flex-col items-center justify-center text-zinc-500 gap-3"
                             style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-zinc-400">Tap button to take photo</span>
                        </div>

                        {{-- Loading state --}}
                        <div x-show="cameraMode === 'loading'"
                             class="absolute inset-0 flex flex-col items-center justify-center text-zinc-500 gap-2">
                            <div class="text-3xl">&#x1F4F7;</div>
                            <div class="text-sm">Starting camera&hellip;</div>
                        </div>

                        {{-- Error --}}
                        <div x-show="cameraMode === 'error'"
                             class="absolute inset-0 flex flex-col items-center justify-center text-zinc-500 gap-2 px-4"
                             style="display:none;">
                            <div class="text-3xl">&#x26A0;&#xFE0F;</div>
                            <div class="text-sm text-center" x-text="cameraError"></div>
                            <button @click="initCamera()" class="text-xs bg-teal-700 text-white px-4 py-2 rounded-lg mt-1">Retry</button>
                        </div>

                        {{-- Upload overlay --}}
                        <div x-show="uploadStatus === 'uploading'"
                             class="absolute bottom-2 left-2 right-2 bg-zinc-900/80 text-white text-xs text-center py-2 rounded-lg">
                            &#x23F3; Uploading&hellip;
                        </div>
                        <div x-show="uploadStatus === 'ok'"
                             class="absolute bottom-2 left-2 right-2 bg-green-700/90 text-white text-xs text-center py-2 rounded-lg">
                            &#x2705; Photo saved
                        </div>
                    </div>

                    {{-- Canvas (hidden) --}}
                    <canvas id="rr-canvas" class="hidden"></canvas>

                    {{-- Action bar --}}
                    <div class="flex gap-3 px-4 py-4">
                        @if ($currentItem->isSkippable())
                            <button wire:click="openSkipModal"
                                    class="flex-none bg-zinc-700 text-zinc-300 font-semibold px-5 rounded-xl"
                                    style="min-height:64px;min-width:80px;">
                                &#x23ED; Skip
                            </button>
                        @endif
                        <button @click="capturePhoto()"
                                :disabled="$wire.processing || cameraMode === 'loading'"
                                class="flex-1 font-bold text-white rounded-xl text-lg transition-all active:scale-95"
                                :class="($wire.processing || cameraMode === 'loading') ? 'opacity-40' : ''"
                                style="min-height:72px; background: linear-gradient(135deg, #1e3a5f, #097b86);">
                            <span x-show="!$wire.processing">&#x1F4F7; Capture Photo</span>
                            <span x-show="$wire.processing">Processing&hellip;</span>
                        </button>
                    </div>
                </div>
            @endunless

            {{-- ─── CHECKLIST MODE (CR) ─── --}}
            @if ($isCR)
                <div class="flex-1 flex flex-col">
                    <div class="px-4 pt-4 pb-2 text-center">
                        <div class="inline-flex items-center gap-1 bg-blue-800 text-blue-200 text-sm font-bold px-4 py-1.5 rounded-full mb-2">
                            &#x1F6BB; CR / Bathroom
                        </div>
                        <div class="text-lg font-bold text-white">{{ $currentItem->locationArea?->name }}</div>
                        <div class="text-xs text-zinc-400 mt-1">Tap each item to check it off</div>
                    </div>

                    <div class="flex-1 flex flex-col gap-2 px-4 overflow-y-auto pb-2">
                        @foreach ($locationParts as $part)
                            @php $checked = in_array($part['id'], $checkedPartIds, true); @endphp
                            <button wire:click="togglePartCheck({{ $part['id'] }})"
                                    class="w-full flex items-center gap-3 px-4 rounded-xl border-2 text-left transition-all active:scale-95"
                                    style="min-height:60px;"
                                    @class([
                                        'bg-green-700 border-green-500 text-white' => $checked,
                                        'bg-zinc-800 border-zinc-600 text-zinc-200' => !$checked,
                                    ])>
                                <span class="text-2xl flex-shrink-0">{{ $checked ? '✅' : '⬜' }}</span>
                                <span class="font-semibold text-base">{{ $part['name'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    @php
                        $allChecked = count($locationParts) > 0
                            && count(array_diff(array_column($locationParts, 'id'), $checkedPartIds)) === 0;
                    @endphp
                    <div class="px-4 py-4">
                        @if ($currentItem->isSkippable())
                            <button wire:click="openSkipModal"
                                    class="w-full bg-zinc-700 text-zinc-300 font-semibold py-3 rounded-xl mb-3 text-sm">
                                &#x23ED; Skip this area
                            </button>
                        @endif
                        <button wire:click="completeChecklist"
                                @if(!$allChecked) disabled @endif
                                class="w-full font-bold text-lg rounded-xl transition-all active:scale-95"
                                style="min-height:64px;"
                                @class([
                                    'bg-green-600 text-white' => $allChecked,
                                    'bg-zinc-700 text-zinc-500 cursor-not-allowed' => !$allChecked,
                                ])>
                            &#x2705; Submit Checklist
                        </button>
                    </div>
                </div>
            @endif

        @else
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center px-8">
                    <div class="text-6xl mb-4">&#x1F389;</div>
                    <div class="font-bold text-2xl text-white">Round Complete!</div>
                    <div class="text-sm text-zinc-400 mt-2">All areas done. Great work!</div>
                    <a href="{{ route('maintenance.slots') }}"
                       class="inline-block mt-6 bg-teal-600 text-white font-bold px-8 py-4 rounded-xl text-base">
                        Back to My Rounds &rarr;
                    </a>
                </div>
            </div>
        @endif

    </div>

    {{-- CANCEL ROUND MODAL --}}
    @if ($showCancelModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-6">
            <div class="absolute inset-0 bg-black/70" wire:click="$set('showCancelModal', false)"></div>
            <div class="relative bg-white rounded-2xl p-6 w-full max-w-sm" @click.stop>
                <div class="text-center mb-5">
                    <div class="text-4xl mb-3">&#x26A0;&#xFE0F;</div>
                    <h3 class="font-bold text-xl text-zinc-800">Cancel entire round?</h3>
                    <p class="text-sm text-zinc-600 mt-3 leading-relaxed">
                        This will <strong>cancel the whole round</strong> and you
                        will lose <strong>all progress</strong> (including completed
                        photos and checklists). All location locks will be released
                        so you can start a different slot.
                    </p>
                    <p class="text-xs text-zinc-500 mt-3">
                        Use this only if you need to switch to another slot
                        (e.g. from Slot 1 to Slot 2).
                    </p>
                </div>
                <div class="flex flex-col gap-3">
                    <button wire:click="cancelRound"
                            wire:loading.attr="disabled"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl text-base">
                        <span wire:loading.remove wire:target="cancelRound">Yes, cancel &amp; switch slot</span>
                        <span wire:loading wire:target="cancelRound">Cancelling&hellip;</span>
                    </button>
                    <button wire:click="$set('showCancelModal', false)"
                            class="w-full bg-zinc-100 text-zinc-800 font-semibold py-4 rounded-xl text-base">
                        Keep going
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- SKIP MODAL --}}
    @if ($showSkipModal)
        <div class="fixed inset-0 z-50 flex flex-col justify-end">
            <div class="absolute inset-0 bg-black/60" wire:click="$set('showSkipModal', false)"></div>
            <div class="relative bg-white rounded-t-2xl p-5" @click.stop>
                <div class="w-10 h-1 bg-zinc-300 rounded-full mx-auto mb-4"></div>
                <h3 class="font-bold text-xl text-zinc-800 mb-1">Skip this area?</h3>
                <p class="text-sm text-zinc-500 mb-4">Select a reason:</p>
                <div class="flex flex-col gap-3">
                    @foreach (['Patient present', 'Room restricted', 'Already handled by nurse'] as $reason)
                        <button wire:click="setSkipReason('{{ $reason }}')"
                                class="w-full bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold rounded-xl text-left px-4 active:bg-zinc-300"
                                style="min-height:56px;">
                            {{ $reason }}
                        </button>
                    @endforeach
                    <button wire:click="$set('showSkipModal', false)"
                            class="text-zinc-500 text-sm mt-1 py-3 text-center">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- TRANSITION OVERLAY --}}
    <div x-show="showTransitionOverlay"
         x-transition:enter="transition duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showTransitionOverlay = false"
         class="fixed inset-0 z-40 flex flex-col items-center justify-center bg-green-700 text-white cursor-pointer"
         style="display:none;">
        <div class="text-7xl mb-4">&#x2705;</div>
        <div class="text-2xl font-bold">Done!</div>
        <div class="text-base mt-2 opacity-80">Next: <span x-text="nextAreaName" class="font-semibold"></span></div>
        <div class="text-xs mt-6 opacity-50">Tap to continue</div>
    </div>

</div>

