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
    .search-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(2,124,139,.2);border-color:#027c8b; }
    .brand-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(1,85,129,.2);border-color:#015581; }
    .brand-card-hover:hover { box-shadow:0 4px 16px rgba(1,85,129,.12); }
    @keyframes shrink { from { width:100% } to { width:0% } }
</style>

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Department Assets</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">{{ $userDepartmentName }}</h1>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
            $statCards = [
                ['label' => 'Total Assets',  'value' => $assets->total(),
                 'icon'  => 'M20 7v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-4-4m4 4l-4 4',
                 'bg'    => 'brand-bg-primary-light', 'text' => 'brand-text-primary'],
                ['label' => 'In Use',         'value' => $assets->where('status', 'in_use')->count(),
                 'icon'  => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                 'bg'    => 'bg-blue-50', 'text' => 'text-blue-700'],
                ['label' => 'Maintenance',    'value' => $assets->where('status', 'maintenance')->count(),
                 'icon'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                 'bg'    => 'bg-yellow-50', 'text' => 'text-yellow-700'],
                ['label' => 'Retired',        'value' => $assets->where('status', 'retired')->count(),
                 'icon'  => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
                 'bg'    => 'bg-red-50', 'text' => 'text-red-700'],
            ];
        @endphp
        @foreach($statCards as $card)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="p-2 rounded-lg {{ $card['bg'] }} shrink-0">
                <svg class="w-5 h-5 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold text-gray-800 leading-none">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- MAIN PANEL --}}
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

        {{-- Toolbar --}}
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">Department Asset List</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    {{ $assets->count() }} shown
                </span>
            </div>
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

        {{-- CARD GRID --}}
        @if($assets && $assets->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($assets as $asset)
                @php
                    $statusMap = [
                        'in_use'      => ['bg'=>'#dbeafe','color'=>'#1e40af','border'=>'#93c5fd','label'=>'In Use'],
                        'maintenance' => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Maintenance'],
                        'retired'     => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Retired'],
                        'active'      => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'Active'],
                    ];
                    $sc = $statusMap[$asset->status] ?? $statusMap['active'];
                @endphp
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden cursor-pointer
                            hover:shadow-md brand-card-hover transition-shadow"
                     wire:click="showDetails({{ $asset->id }})">
                    <div class="h-40 bg-gray-50 flex items-center justify-center overflow-hidden">
                        @if($asset->image && Storage::disk('public')->exists($asset->image))
                            <img src="{{ Storage::url($asset->image) }}" class="w-full h-full object-contain block p-1">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="font-bold text-gray-900 text-sm truncate">{{ $asset->asset_code }}</p>
                        <p class="text-xs text-gray-600 truncate mb-2">{{ $asset->name }}</p>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                              style="background-color:{{ $sc['bg'] }};color:{{ $sc['color'] }};border:1px solid {{ $sc['border'] }}">
                            {{ $sc['label'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="px-6 pb-6">{{ $assets->links() }}</div>
        @else
        <div class="p-12 text-center">
            <div class="flex flex-col items-center text-gray-400">
                <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-sm font-medium">No assets in your department.</p>
                <p class="text-xs mt-1">{{ $search ? 'Try adjusting your search.' : 'Contact IT or admin to assign assets.' }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- DETAILS MODAL --}}
    @if($showDetailsModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeDetailsModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg brand-bg-primary-light">
                            <svg class="w-4 h-4 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Asset Details</h3>
                    </div>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="md:w-5/12 bg-gray-50 rounded-xl flex items-center justify-center min-h-40">
                            @if($selectedAsset->image && Storage::disk('public')->exists($selectedAsset->image))
                                <img src="{{ Storage::url($selectedAsset->image) }}"
                                     class="max-w-full max-h-48 object-cover rounded-lg">
                            @else
                                <div class="flex flex-col items-center text-gray-300 py-8">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs mt-2">No image</p>
                                </div>
                            @endif
                        </div>
                        <div class="md:w-7/12 space-y-3 text-sm">
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Name</p><p class="font-semibold text-gray-900">{{ $selectedAsset->name }}</p></div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Asset Code</p><p class="font-mono text-gray-700">{{ $selectedAsset->asset_code }}</p></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Brand</p><p class="text-gray-700">{{ $selectedAsset->brand ?: '—' }}</p></div>
                                <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Model</p><p class="text-gray-700">{{ $selectedAsset->model ?: '—' }}</p></div>
                            </div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Serial No.</p><p class="font-mono text-gray-700">{{ $selectedAsset->serial_number ?: '—' }}</p></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Status</p>
                                    @php
                                        $sm = ['in_use'=>['#dbeafe','#1e40af','#93c5fd','In Use'],'maintenance'=>['#fef9c3','#854d0e','#fde047','Maintenance'],'retired'=>['#fee2e2','#991b1b','#fca5a5','Retired'],'active'=>['#dcfce7','#166534','#86efac','Active']];
                                        [$sb,$sc2,$sb2,$sl] = $sm[$selectedAsset->status] ?? $sm['active'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                                          style="background-color:{{ $sb }};color:{{ $sc2 }};border:1px solid {{ $sb2 }}">{{ $sl }}</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Condition</p>
                                    @php
                                        $cm = ['good'=>['#dcfce7','#166534','#86efac','Good'],'fair'=>['#fef9c3','#854d0e','#fde047','Fair'],'poor'=>['#fee2e2','#991b1b','#fca5a5','Poor']];
                                        [$cb,$cc2,$cb2,$cl] = $cm[$selectedAsset->condition_status] ?? $cm['good'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                                          style="background-color:{{ $cb }};color:{{ $cc2 }};border:1px solid {{ $cb2 }}">{{ $cl }}</span>
                                </div>
                            </div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Location</p><p class="text-gray-700">{{ $selectedAsset->location?->name ?? '—' }}</p></div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Notes</p><p class="text-xs text-gray-600">{{ $selectedAsset->notes ?: 'No notes.' }}</p></div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button wire:click="closeDetailsModal"
                        class="px-4 py-2 text-sm font-semibold text-gray-700">Close</button>
                    <button wire:click="openUpdateModal({{ $selectedAsset->id }})"
                        class="brand-btn-primary text-sm font-bold px-5 py-2 rounded-lg active:scale-95">
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- UPDATE STATUS MODAL --}}
    @if($showUpdateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeUpdateModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
                <form wire:submit.prevent="updateAssetStatus">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-primary-light">
                                <svg class="w-4 h-4 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">Update Asset Status</h3>
                        </div>
                        <button type="button" wire:click="closeUpdateModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                            <select wire:model="update_status"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm bg-white">
                                <option value="in_use">In Use</option>
                                <option value="maintenance">Under Maintenance</option>
                                <option value="retired">Retired</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Condition</label>
                            <select wire:model="update_condition_status"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm bg-white">
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Remarks</label>
                            <textarea wire:model="update_remarks" rows="3"
                                placeholder="Reason for status change…"
                                class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeUpdateModal"
                            class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit"
                            class="brand-btn-primary text-sm font-bold px-5 py-2 rounded-lg active:scale-95">
                            Save Changes
                        </button>
                    </div>
                </form>
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
        <div class="h-1 brand-bg-accent" style="animation:shrink 4s linear forwards;"></div>
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
        <div class="h-1 bg-red-400" style="animation:shrink 5s linear forwards;"></div>
    </div>
    @endif

</div>
