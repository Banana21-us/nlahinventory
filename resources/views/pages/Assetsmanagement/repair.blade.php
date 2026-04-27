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
</style>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">Repair Management</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">{{ $userDepartmentName }} — Assets Needing Repair</h1>
                <p class="text-xs text-gray-500 mt-1">Assets with maintenance status or poor/fair condition</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center gap-3">
            <div class="p-2 rounded-lg bg-red-50 shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold">Needs Repair</p>
                <p class="text-2xl font-bold text-gray-800 leading-none">{{ $assets->where('status', 'maintenance')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center gap-3">
            <div class="p-2 rounded-lg bg-red-50 shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold">Poor Condition</p>
                <p class="text-2xl font-bold text-gray-800 leading-none">{{ $assets->where('condition_status', 'poor')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center gap-3">
            <div class="p-2 rounded-lg bg-yellow-50 shrink-0">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold">Fair Condition</p>
                <p class="text-2xl font-bold text-gray-800 leading-none">{{ $assets->where('condition_status', 'fair')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4 flex items-center justify-between">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search assets…"
                   class="search-focus pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-52 bg-white">
        </div>
    </div>

    @if($assets && $assets->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($assets as $asset)
        @php
            $needRepair = $asset->status === 'maintenance';
            $statusMap = [
                'maintenance' => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Needs Repair'],
                'poor' => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Poor'],
                'fair' => ['bg'=>'#fef3c7','color'=>'#92400e','border'=>'#fcd34d','label'=>'Fair'],
            ];
            $sc = $needRepair ? $statusMap['maintenance'] : ($statusMap[$asset->condition_status] ?? $statusMap['fair']);
        @endphp
        <div class="bg-white rounded-xl cursor-pointer hover:shadow-md transition-shadow
                    {{ $needRepair ? 'border border-gray-200 border-l-4 border-l-red-500' : ($asset->condition_status === 'poor' ? 'border border-gray-200 border-l-4 border-l-amber-500' : 'border border-gray-200 border-l-4 border-l-yellow-400') }}"
             wire:click="showDetails({{ $asset->id }})">

            {{-- Image --}}
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

            {{-- Body --}}
            <div class="p-3">
                <div class="flex items-center justify-between gap-1 mb-0.5">
                    <p class="font-bold text-gray-900 text-sm truncate">{{ $asset->asset_code }}</p>
                    <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full"
                          style="background-color:{{ $sc['bg'] }};color:{{ $sc['color'] }};border:1px solid {{ $sc['border'] }}">
                        {{ $sc['label'] }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 truncate">{{ $asset->name }}</p>
                @if($asset->location)
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $asset->location->name }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $assets->links() }}</div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="flex flex-col items-center text-gray-400">
            <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-medium">No Assets Need Repair</p>
            <p class="text-xs mt-1">All assets are in good condition.</p>
        </div>
    </div>
    @endif

    {{-- Details Modal --}}
    @if($showDetailsModal && $selectedAsset)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeDetailsModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between brand-bg-primary-light">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg brand-bg-primary">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
                                <img src="{{ Storage::url($selectedAsset->image) }}" class="max-w-full h-48 object-cover rounded-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="md:w-7/12 space-y-3 text-sm">
                            <div><p class="text-xs font-bold text-gray-400 uppercase">Name</p><p class="font-semibold text-gray-900">{{ $selectedAsset->name }}</p></div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase">Code</p><p class="font-mono text-gray-700">{{ $selectedAsset->asset_code }}</p></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><p class="text-xs font-bold text-gray-400 uppercase">Brand</p><p class="text-gray-700">{{ $selectedAsset->brand ?: '—' }}</p></div>
                                <div><p class="text-xs font-bold text-gray-400 uppercase">Model</p><p class="text-gray-700">{{ $selectedAsset->model ?: '—' }}</p></div>
                            </div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase">Serial No.</p><p class="font-mono text-gray-700">{{ $selectedAsset->serial_number ?: '—' }}</p></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-1">Status</p>
                                    @php
                                        $sm = ['maintenance'=>['#fee2e2','#991b1b','#fca5a5','Needs Repair'],'poor'=>['#fef9c3','#854d0e','#fde047','Poor'],'fair'=>['#fef3c7','#92400e','#fcd34d','Fair'],'in_use'=>['#dcfce7','#166534','#86efac','In Use'],'active'=>['#dcfce7','#166534','#86efac','Active']];
                                        [$sb,$sc2,$sb2,$sl] = $sm[$selectedAsset->status] ?? $sm['active'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                                          style="background-color:{{ $sb }};color:{{ $sc2 }};border:1px solid {{ $sb2 }}">{{ $sl }}</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-1">Condition</p>
                                    @php
                                        $cm = ['good'=>['#dcfce7','#166534','#86efac','Good'],'poor'=>['#fef9c3','#854d0e','#fde047','Poor'],'fair'=>['#fef3c7','#92400e','#fcd34d','Fair']];
                                        [$cb,$cc2,$cb2,$cl] = $cm[$selectedAsset->condition_status] ?? $cm['good'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                                          style="background-color:{{ $cb }};color:{{ $cc2 }};border:1px solid {{ $cb2 }}">{{ $cl }}</span>
                                </div>
                            </div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase">Location</p><p class="text-gray-700">{{ $selectedAsset->location?->name ?? '—' }}</p></div>
                            <div><p class="text-xs font-bold text-gray-400 uppercase">Notes</p><p class="text-xs text-gray-600">{{ $selectedAsset->notes ?: 'No notes.' }}</p></div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button wire:click="closeDetailsModal" class="px-4 py-2 text-sm font-semibold text-gray-700">Close</button>
                    <button wire:click="openRepairModal({{ $selectedAsset->id }})"
                            class="brand-btn-teal text-sm font-bold px-5 py-2 rounded-lg active:scale-95">Complete Repair</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Repair Modal --}}
    @if($showRepairModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50" wire:click="closeRepairModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
                <form wire:submit.prevent="completeRepair">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between brand-bg-teal-light">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg brand-bg-teal">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">Complete Repair</h3>
                        </div>
                        <button type="button" wire:click="closeRepairModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Issue / Problem *</label>
                            <textarea wire:model="repair_issue" rows="3" placeholder="Describe what was repaired…"
                                      class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm"></textarea>
                            @error('repair_issue') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Repair Cost</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">₱</span>
                                <input type="number" step="0.01" wire:model="repair_cost" placeholder="0.00"
                                       class="brand-focus w-full rounded-md border border-gray-300 p-2 pl-7 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Additional Notes</label>
                            <textarea wire:model="repair_notes" rows="2" placeholder="Parts replaced, technician, etc."
                                      class="brand-focus w-full rounded-md border border-gray-300 p-2 text-sm"></textarea>
                        </div>
                        <div class="p-3 brand-bg-primary-light rounded-lg">
                            <p class="text-xs text-gray-600">After completion, asset status → <strong class="text-gray-800">In Use</strong>, condition → <strong class="text-gray-800">Good</strong></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" wire:click="closeRepairModal" class="px-4 py-2 text-sm font-semibold text-gray-700">Cancel</button>
                        <button type="submit" class="brand-btn-teal text-sm font-bold px-5 py-2 rounded-lg active:scale-95">Complete Repair</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

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
        <div class="h-1" style="background-color:#f0b626;animation:shrink 4s linear forwards;"></div>
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
