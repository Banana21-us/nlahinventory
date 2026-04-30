@php use Illuminate\Support\Facades\Storage; @endphp

<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
    .brand-bg-teal           { background-color: #027c8b; }
    .brand-bg-teal-light     { background-color: #e6f4f5; }
    .brand-text-teal         { color: #027c8b; }
    .brand-bg-accent         { background-color: #f0b626; }
    .brand-btn-primary { background-color:#015581;color:#fff;transition:background-color .15s ease; }
    .brand-btn-primary:hover { background-color:#01406a; }
    .brand-btn-teal { background-color:#027c8b;color:#fff;transition:background-color .15s ease; }
    .brand-btn-teal:hover { background-color:#016070; }
    .search-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(2,124,139,.2);border-color:#027c8b; }
    .brand-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(1,85,129,.2);border-color:#015581; }
    @keyframes shrink { from { width:100% } to { width:0% } }
    
    /* Disposed asset styling */
    .asset-disposed {
        opacity: 0.7;
        filter: grayscale(0.3);
        position: relative;
        background: repeating-linear-gradient(
            45deg,
            rgba(0,0,0,0.02) 0px,
            rgba(0,0,0,0.02) 20px,
            rgba(0,0,0,0.04) 20px,
            rgba(0,0,0,0.04) 40px
        );
    }
    .asset-disposed::after {
        content: "DISPOSED";
        position: absolute;
        top: 10px;
        left: -25px;
        background-color: #6b21a8;
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 3px 30px;
        transform: rotate(-45deg);
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        letter-spacing: 1px;
    }
    .asset-lost {
        opacity: 0.7;
        filter: grayscale(0.3);
        position: relative;
        background: repeating-linear-gradient(
            45deg,
            rgba(0,0,0,0.02) 0px,
            rgba(0,0,0,0.02) 20px,
            rgba(0,0,0,0.04) 20px,
            rgba(0,0,0,0.04) 40px
        );
    }
    .asset-lost::after {
        content: "LOST";
        position: absolute;
        top: 10px;
        left: -25px;
        background-color: #6b7280;
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 3px 30px;
        transform: rotate(-45deg);
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        letter-spacing: 1px;
    }
    .asset-outofservice {
        opacity: 0.85;
        position: relative;
        background: repeating-linear-gradient(
            45deg,
            rgba(0,0,0,0.01) 0px,
            rgba(0,0,0,0.01) 20px,
            rgba(0,0,0,0.02) 20px,
            rgba(0,0,0,0.02) 40px
        );
    }
    .asset-outofservice::after {
        content: "🔧 IN REPAIR";
        position: absolute;
        top: 10px;
        left: -25px;
        background-color: #0284c7;
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 3px 30px;
        transform: rotate(-45deg);
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        letter-spacing: 1px;
    }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Assets Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Assign Assets</h1>
                <p class="text-xs text-gray-500 mt-1">Only available assets can be assigned</p>
            </div>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">All Assets</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    {{ $allAssets->count() }} {{ Str::plural('record', $allAssets->count()) }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                {{-- Filter buttons --}}
                <div class="flex gap-1 p-1 bg-gray-100 rounded-lg">
                    <button wire:click="$set('filter', 'all')"
                        class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors
                               {{ $filter === 'all' ? 'brand-btn-primary shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                        All
                    </button>
                    <button wire:click="$set('filter', 'unassigned')"
                        class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors
                               {{ $filter === 'unassigned' ? 'brand-btn-primary shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                        Unassigned
                    </button>
                    <button wire:click="$set('filter', 'assigned')"
                        class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors
                               {{ $filter === 'assigned' ? 'brand-btn-primary shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                        Assigned
                    </button>
                </div>
                {{-- Search --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assets…"
                        class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg w-48"/>
                </div>
            </div>
        </div>

        {{-- CARD GRID --}}
        @if($allAssets->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($allAssets as $asset)
                    @php
                        $isAssigned = $asset->department_id && $asset->location_id;
                        $isDisposed = $asset->status === 'disposed';
                        $isLost = $asset->status === 'lost';
                        $isOutOfService = $asset->status === 'out_of_service';
                        $isUnavailable = $isDisposed || $isLost;
                    @endphp
                    <div wire:key="asset-{{ $asset->id }}" class="bg-white rounded-xl overflow-hidden hover:shadow-md transition-shadow relative
                                {{ $isDisposed ? 'asset-disposed' : ($isLost ? 'asset-lost' : ($isOutOfService ? 'asset-outofservice' : '')) }}
                                {{ $isAssigned && !$isUnavailable && !$isOutOfService
                                    ? 'border border-gray-200 border-l-4 border-l-teal-500'
                                    : ($isUnavailable 
                                        ? 'border border-gray-200 border-l-4 border-l-gray-400'
                                        : ($isOutOfService
                                            ? 'border border-gray-200 border-l-4 border-l-blue-500'
                                            : 'border border-gray-200 border-l-4 border-l-amber-400')) }}">

                        {{-- Image --}}
                        <div class="h-40 bg-gray-50 flex items-center justify-center overflow-hidden {{ $isUnavailable ? 'opacity-50' : '' }}">
                            @if($asset->image && Storage::disk('public')->exists($asset->image))
                                <img src="{{ Storage::url($asset->image) }}"
                                     class="w-full h-full object-contain block p-1">
                            @else
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-3">
                            {{-- Code + badge row --}}
                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                <p class="font-bold text-gray-900 text-sm truncate">{{ $asset->asset_code }}</p>
                                @if($isDisposed)
                                    <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full"
                                          style="background-color:#f3e8ff;color:#6b21a8;border:1px solid #d8b4fe">
                                        Disposed
                                    </span>
                                @elseif($isLost)
                                    <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full"
                                          style="background-color:#f3f4f6;color:#6b7280;border:1px solid #d1d5db">
                                        Lost
                                    </span>
                                @elseif($isOutOfService)
                                    <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full"
                                          style="background-color:#dbeafe;color:#1e40af;border:1px solid #93c5fd">
                                        🔧 In Repair
                                    </span>
                                @elseif($isAssigned)
                                    <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full"
                                          style="background-color:#e6f4f5;color:#027c8b;border:1px solid #a5d8dd;">
                                        Assigned
                                    </span>
                                @else
                                    <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full"
                                          style="background-color:#fef8e7;color:#92400e;border:1px solid #fcd34d;">
                                        Free
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 truncate mb-1.5">{{ $asset->name }}</p>
                            @if($asset->department && !$isUnavailable && !$isOutOfService)
                                <p class="text-xs text-gray-400 truncate mb-2">
                                    <span class="font-semibold brand-text-primary">{{ $asset->department->name }}</span>
                                </p>
                            @elseif($isDisposed)
                                <p class="text-xs text-gray-400 truncate mb-2 italic">This asset has been disposed</p>
                            @elseif($isLost)
                                <p class="text-xs text-gray-400 truncate mb-2 italic">This asset has been lost</p>
                            @elseif($isOutOfService)
                                <p class="text-xs text-gray-400 truncate mb-2 italic">This asset is currently being repaired</p>
                            @else
                                <p class="text-xs text-gray-300 mb-2">No department</p>
                            @endif

                            {{-- Action buttons --}}
                            <div class="pt-2 border-t border-gray-100">
                                @if($isUnavailable)
                                    <button disabled
                                        class="w-full bg-gray-300 text-gray-500 text-xs font-bold py-1.5 rounded-lg cursor-not-allowed"
                                        title="Disposed/Lost assets cannot be modified">
                                        Not Available
                                    </button>
                                @elseif($isOutOfService)
                                    <button disabled
                                        class="w-full bg-blue-300 text-white text-xs font-bold py-1.5 rounded-lg cursor-not-allowed"
                                        title="Asset is currently being repaired">
                                        🔧 In Repair
                                    </button>
                                @elseif(!$isAssigned)
                                    <button wire:click="openAssignModal({{ $asset->id }})"
                                        class="w-full brand-btn-primary text-xs font-bold py-1.5 rounded-lg active:scale-95">
                                        Assign
                                    </button>
                                @else
                                    <div class="flex gap-1">
                                        <button wire:click="openTransferModal({{ $asset->id }})"
                                            class="flex-1 brand-btn-teal text-xs font-bold py-1.5 rounded-lg active:scale-95">
                                            Transfer
                                        </button>
                                        <button wire:click="confirmUnassign({{ $asset->id }})"
                                            class="flex-1 text-xs font-bold py-1.5 rounded-lg transition-colors active:scale-95"
                                            style="background-color:#fff0f0;color:#b91c1c;border:1px solid #fca5a5;">
                                            Unassign
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="p-12 text-center">
            <div class="flex flex-col items-center text-gray-400">
                <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <p class="text-sm font-medium">No assets found.</p>
                <p class="text-xs mt-1">{{ $search ? 'Try a different search term.' : 'Register assets in the Inventory page first.' }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- ASSIGN MODAL --}}
    @if($showModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg">
                <form wire:submit.prevent="assignAsset">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-primary-light">
                                <svg class="w-4 h-4 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7h12m0 0l-4-4m4 4l-4 4"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">Assign Asset</h3>
                        </div>
                        <button type="button" wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="brand-bg-primary-light rounded-lg p-3">
                            <p class="font-bold text-gray-900 text-sm">{{ $selectedAsset->asset_code }}</p>
                            <p class="text-xs text-gray-600">{{ $selectedAsset->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Department *</label>
                            <select wire:model="to_department_id"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm bg-white">
                                <option value="">Select department…</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('to_department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Location *</label>
                            <select wire:model="to_location_id"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm bg-white">
                                <option value="">Select location…</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('to_location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Remarks</label>
                            <textarea wire:model="remarks" rows="2" placeholder="Reason for assignment…"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit"
                            class="brand-btn-primary text-sm font-bold px-5 py-2 rounded-lg active:scale-95">
                            Confirm Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- TRANSFER MODAL --}}
    @if($showTransferModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeTransferModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg">
                <form wire:submit.prevent="transferAsset">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-teal-light">
                                <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">Transfer Asset</h3>
                        </div>
                        <button type="button" wire:click="closeTransferModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="brand-bg-teal-light rounded-lg p-3">
                            <p class="font-bold text-gray-900 text-sm">{{ $selectedAsset->asset_code }}</p>
                            <p class="text-xs text-gray-600">{{ $selectedAsset->name }}</p>
                            <div class="mt-1.5 text-xs text-gray-500 space-y-0.5">
                                <p>Current dept: <span class="font-semibold text-gray-700">{{ $selectedAsset->department->name ?? '—' }}</span></p>
                                <p>Current location: <span class="font-semibold text-gray-700">{{ $selectedAsset->location->name ?? '—' }}</span></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">New Department *</label>
                            <select wire:model="to_department_id"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm bg-white">
                                <option value="">Select department…</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('to_department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">New Location *</label>
                            <select wire:model="to_location_id"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm bg-white">
                                <option value="">Select location…</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('to_location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Transfer Reason</label>
                            <textarea wire:model="remarks" rows="2" placeholder="Reason for transfer…"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeTransferModal"
                            class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit"
                            class="brand-btn-teal text-sm font-bold px-5 py-2 rounded-lg active:scale-95">
                            Confirm Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- UNASSIGN CONFIRM MODAL --}}
    @if($showUnassignModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeUnassignModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-11 h-11 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Unassign Asset</h3>
                            <p class="mt-1.5 text-sm text-gray-500">
                                Are you sure you want to unassign
                                <strong>{{ $selectedAsset->asset_code }} — {{ $selectedAsset->name }}</strong>?
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" wire:click="closeUnassignModal"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="button" wire:click="unassignAsset"
                        class="px-4 py-2 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 active:scale-95">
                        Yes, Unassign
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- TOAST --}}
    @if(session()->has('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5">
        <div class="p-4 flex items-start gap-3">
            <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full brand-bg-teal-light">
                <svg class="w-5 h-5 brand-text-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-semibold text-gray-900">Done</p>
                <p class="mt-0.5 text-sm text-gray-500">{{ session('message') }}</p>
            </div>
        </div>
        <div class="h-1 brand-bg-accent" style="animation:shrink 4s linear forwards"></div>
    </div>
    @endif

    @if(session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-red-200">
        <div class="p-4 flex items-start gap-3">
            <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full bg-red-50">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-semibold text-gray-900">Error</p>
                <p class="mt-0.5 text-sm text-gray-500">{{ session('error') }}</p>
            </div>
        </div>
        <div class="h-1 bg-red-400" style="animation:shrink 5s linear forwards"></div>
    </div>
    @endif

</div>